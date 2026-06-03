<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Models\Invitation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class PublicInvitationController extends Controller
{
    public function show(Request $request, string $slug): View
    {
        $invitation = Invitation::query()
            ->with([
                'template',
                'galleries' => fn ($query) => $query->where('is_active', true),
                'stories',
                'giftAccounts' => fn ($query) => $query->where('is_visible', true),
                'messages' => fn ($query) => $query->where('is_visible', true)->latest()->take(12),
            ])
            ->where('slug', $slug)
            ->whereIn('status', ['active', 'draft'])
            ->firstOrFail();

        if ($invitation->password && ! $request->session()->get("invitation-unlocked-{$invitation->id}")) {
            return view('invitations.locked', compact('invitation'));
        }

        $guest = $request->filled('guest')
            ? Guest::query()->where('personal_token', $request->query('guest'))->whereBelongsTo($invitation)->first()
            : null;

        $this->trackView($request, $invitation, $guest);

        return view($invitation->template?->view_path ?: 'invitations.templates.elegant', [
            'invitation' => $invitation,
            'guest' => $guest,
            'guestName' => $guest?->name ?: $request->query('to'),
            'eventDateTime' => $invitation->eventDateTime(),
        ]);
    }

    public function unlock(Request $request, string $slug): RedirectResponse
    {
        $invitation = Invitation::query()->where('slug', $slug)->firstOrFail();
        $request->validate(['password' => ['required', 'string']]);

        if (! Hash::check($request->password, $invitation->password) && $request->password !== $invitation->password) {
            return back()->withErrors(['password' => 'Password undangan tidak sesuai.']);
        }

        $request->session()->put("invitation-unlocked-{$invitation->id}", true);

        return redirect()->route('invitations.show', $invitation->slug);
    }

    public function rsvp(Request $request, string $slug): RedirectResponse
    {
        $invitation = Invitation::query()->where('slug', $slug)->firstOrFail();
        $guest = $request->filled('guest_token')
            ? Guest::query()->where('personal_token', $request->input('guest_token'))->whereBelongsTo($invitation)->first()
            : null;

        $maxGuests = $guest?->max_companions ?: 20;
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'attendance' => ['required', 'in:attending,declined,maybe'],
            'guests_count' => ['required', 'integer', 'min:1', 'max:'.$maxGuests],
            'notes' => ['nullable', 'string', 'max:600'],
        ]);

        $invitation->rsvps()->create([
            ...$data,
            'guest_id' => $guest?->id,
            'ip_address' => $request->ip(),
            'user_agent' => (string) $request->userAgent(),
        ]);

        return back()->with('rsvp_status', 'Terima kasih, konfirmasi kehadiran sudah kami catat.');
    }

    public function message(Request $request, string $slug): RedirectResponse
    {
        $invitation = Invitation::query()->where('slug', $slug)->firstOrFail();
        $guest = $request->filled('guest_token')
            ? Guest::query()->where('personal_token', $request->input('guest_token'))->whereBelongsTo($invitation)->first()
            : null;

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'message' => ['required', 'string', 'max:800'],
        ]);

        $invitation->messages()->create([
            'guest_id' => $guest?->id,
            'name' => strip_tags($data['name']),
            'message' => strip_tags($data['message']),
            'is_visible' => false,
            'ip_address' => $request->ip(),
            'user_agent' => (string) $request->userAgent(),
        ]);

        return back()->with('message_status', 'Ucapan sudah terkirim dan akan tampil setelah disetujui.');
    }

    public function share(Request $request, string $slug): RedirectResponse
    {
        $invitation = Invitation::query()->where('slug', $slug)->firstOrFail();
        $guest = $request->filled('guest')
            ? Guest::query()->where('personal_token', $request->query('guest'))->whereBelongsTo($invitation)->first()
            : null;

        $invitation->increment('share_count');

        return redirect()->away('https://wa.me/?text='.urlencode($invitation->shareMessageFor($guest ?: $request->query('to'))));
    }

    private function trackView(Request $request, Invitation $invitation, ?Guest $guest): void
    {
        DB::transaction(function () use ($request, $invitation, $guest): void {
            $ipHash = hash('sha256', (string) $request->ip());
            $alreadySeen = $invitation->views()
                ->where('ip_hash', $ipHash)
                ->where('session_id', $request->session()->getId())
                ->where('viewed_at', '>=', now()->subHours(12))
                ->exists();

            $invitation->views()->create([
                'guest_id' => $guest?->id,
                'session_id' => $request->session()->getId(),
                'ip_hash' => $ipHash,
                'user_agent' => (string) $request->userAgent(),
                'path' => $request->path(),
                'referrer' => $request->headers->get('referer'),
                'viewed_at' => now(),
            ]);

            $invitation->forceFill([
                'view_count' => $alreadySeen ? $invitation->view_count : $invitation->view_count + 1,
                'last_viewed_at' => now(),
            ])->save();
        });
    }
}
