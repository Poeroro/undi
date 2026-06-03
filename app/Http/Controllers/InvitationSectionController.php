<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class InvitationSectionController extends Controller
{
    public function guests(Request $request, Invitation $invitation): View
    {
        $this->authorizeOwner($request, $invitation);

        return view('dashboard.invitations.guests', [
            'invitation' => $invitation,
            'guests' => $invitation->guests()->latest()->paginate(25),
        ]);
    }

    public function storeGuest(Request $request, Invitation $invitation): RedirectResponse
    {
        $this->authorizeOwner($request, $invitation);
        $plan = $request->user()->subscription?->plan;
        $limit = $plan?->guest_limit ?: 50;

        abort_if($invitation->guests()->count() >= $limit, 422, 'Limit tamu paket Anda sudah tercapai.');

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'whatsapp' => ['nullable', 'string', 'max:40'],
            'email' => ['nullable', 'email', 'max:160'],
            'category' => ['required', 'string', 'max:60'],
            'max_companions' => ['required', 'integer', 'min:1', 'max:20'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $invitation->guests()->create([...$data, 'personal_token' => Str::random(32)]);

        return back()->with('status', 'Tamu ditambahkan.');
    }

    public function rsvps(Request $request, Invitation $invitation): View
    {
        $this->authorizeOwner($request, $invitation);

        return view('dashboard.invitations.rsvps', [
            'invitation' => $invitation,
            'rsvps' => $invitation->rsvps()->latest()->paginate(25),
            'summary' => [
                'attending' => $invitation->rsvps()->where('attendance', 'attending')->sum('guests_count'),
                'declined' => $invitation->rsvps()->where('attendance', 'declined')->count(),
                'maybe' => $invitation->rsvps()->where('attendance', 'maybe')->count(),
            ],
        ]);
    }

    public function messages(Request $request, Invitation $invitation): View
    {
        $this->authorizeOwner($request, $invitation);

        return view('dashboard.invitations.messages', [
            'invitation' => $invitation,
            'messages' => $invitation->messages()->latest()->paginate(25),
        ]);
    }

    public function approveMessage(Request $request, Invitation $invitation, int $message): RedirectResponse
    {
        $this->authorizeOwner($request, $invitation);
        $invitation->messages()->whereKey($message)->update(['is_visible' => true, 'approved_at' => now()]);

        return back()->with('status', 'Ucapan disetujui.');
    }

    public function gallery(Request $request, Invitation $invitation): View
    {
        $this->authorizeOwner($request, $invitation);

        return view('dashboard.invitations.gallery', [
            'invitation' => $invitation,
            'gallery' => $invitation->galleries()->paginate(25),
        ]);
    }

    public function storeGallery(Request $request, Invitation $invitation): RedirectResponse
    {
        $this->authorizeOwner($request, $invitation);
        $plan = $request->user()->subscription?->plan;
        $limit = $plan?->gallery_limit ?: 8;

        abort_if($invitation->galleries()->where('type', 'image')->count() >= $limit, 422, 'Limit galeri paket Anda sudah tercapai.');

        $data = $request->validate([
            'type' => ['required', 'in:image,video'],
            'image_path' => ['nullable', 'string', 'max:500'],
            'video_url' => ['nullable', 'url', 'max:1000'],
            'caption' => ['nullable', 'string', 'max:160'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $invitation->galleries()->create($data);

        return back()->with('status', 'Item galeri ditambahkan.');
    }

    public function stories(Request $request, Invitation $invitation): View
    {
        $this->authorizeOwner($request, $invitation);

        return view('dashboard.invitations.stories', [
            'invitation' => $invitation,
            'stories' => $invitation->stories()->paginate(25),
        ]);
    }

    public function storeStory(Request $request, Invitation $invitation): RedirectResponse
    {
        $this->authorizeOwner($request, $invitation);
        $data = $request->validate([
            'title' => ['required', 'string', 'max:140'],
            'story_date' => ['nullable', 'date'],
            'description' => ['required', 'string', 'max:1200'],
            'image_path' => ['nullable', 'string', 'max:500'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $invitation->stories()->create($data);

        return back()->with('status', 'Timeline ditambahkan.');
    }

    public function gifts(Request $request, Invitation $invitation): View
    {
        $this->authorizeOwner($request, $invitation);

        return view('dashboard.invitations.gifts', [
            'invitation' => $invitation,
            'gifts' => $invitation->giftAccounts()->paginate(25),
        ]);
    }

    public function storeGift(Request $request, Invitation $invitation): RedirectResponse
    {
        $this->authorizeOwner($request, $invitation);
        $data = $request->validate([
            'type' => ['required', 'in:bank,e_wallet,qris'],
            'provider_name' => ['nullable', 'string', 'max:100'],
            'account_holder' => ['nullable', 'string', 'max:140'],
            'account_number' => ['nullable', 'string', 'max:100'],
            'qris_path' => ['nullable', 'string', 'max:500'],
            'instructions' => ['nullable', 'string', 'max:500'],
            'is_visible' => ['nullable', 'boolean'],
        ]);

        $invitation->giftAccounts()->create($data);

        return back()->with('status', 'Amplop digital ditambahkan.');
    }

    public function statistics(Request $request, Invitation $invitation): View
    {
        $this->authorizeOwner($request, $invitation);
        $views = $invitation->views();
        $rsvps = $invitation->rsvps();

        return view('dashboard.invitations.statistics', [
            'invitation' => $invitation,
            'stats' => [
                'views' => $views->count(),
                'unique_views' => $views->distinct('ip_hash')->count('ip_hash'),
                'shares' => $invitation->share_count,
                'conversion' => max(0, $invitation->view_count) ? round(($rsvps->count() / max(1, $invitation->view_count)) * 100, 1) : 0,
                'last_viewed_at' => $invitation->last_viewed_at,
            ],
        ]);
    }

    private function authorizeOwner(Request $request, Invitation $invitation): void
    {
        abort_unless($invitation->user_id === $request->user()->id || $request->user()->hasRole('super-admin'), 403);
    }
}
