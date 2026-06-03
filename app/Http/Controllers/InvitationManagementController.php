<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\InvitationTemplate;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class InvitationManagementController extends Controller
{
    public function index(Request $request): View
    {
        return view('dashboard.invitations.index', [
            'invitations' => $request->user()->invitations()->with('template')->latest()->paginate(12),
        ]);
    }

    public function create(Request $request): View
    {
        $templates = $this->activeTemplates();
        $selectedTemplate = $templates->firstWhere('id', (int) $request->query('template_id'));
        $selectedTheme = $selectedTemplate
            ? ($selectedTemplate->default_theme ?: data_get(config('undi.template_skins'), "{$selectedTemplate->slug}.default_theme", []))
            : [];

        return view('dashboard.invitations.form', [
            'invitation' => new Invitation([
                'template_id' => $selectedTemplate?->id,
                'timezone' => 'Asia/Jakarta',
                'theme_color' => $selectedTheme['color'] ?? '#a4785b',
                'theme_font' => array_key_exists($selectedTheme['font'] ?? '', config('undi.theme_fonts')) ? $selectedTheme['font'] : 'Georgia',
                'music_enabled' => false,
                'status' => 'draft',
            ]),
            'templates' => $templates,
            'templateDefaults' => $this->templateDefaults($templates),
            'themeFonts' => config('undi.theme_fonts'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->ensureInvitationLimit($request);

        $data = $this->validatedData($request);
        $data['slug'] = Str::slug($data['slug'] ?: $data['title']);
        $data['user_id'] = $request->user()->id;

        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $invitation = Invitation::create($data);

        return redirect()->route('dashboard.invitations.edit', $invitation)->with('status', 'Undangan berhasil dibuat.');
    }

    public function edit(Request $request, Invitation $invitation): View
    {
        $this->authorizeOwner($request, $invitation);
        $templates = $this->activeTemplates();

        return view('dashboard.invitations.form', [
            'invitation' => $invitation,
            'templates' => $templates,
            'templateDefaults' => $this->templateDefaults($templates),
            'themeFonts' => config('undi.theme_fonts'),
        ]);
    }

    public function update(Request $request, Invitation $invitation): RedirectResponse
    {
        $this->authorizeOwner($request, $invitation);
        $data = $this->validatedData($request, $invitation);
        $data['slug'] = Str::slug($data['slug'] ?: $data['title']);

        if (array_key_exists('password', $data)) {
            $data['password'] = filled($data['password']) ? Hash::make($data['password']) : $invitation->password;
        }

        $invitation->update($data);

        return back()->with('status', 'Perubahan undangan disimpan.');
    }

    private function validatedData(Request $request, ?Invitation $invitation = null): array
    {
        return $request->validate([
            'template_id' => ['nullable', 'exists:invitation_templates,id'],
            'title' => ['required', 'string', 'max:160'],
            'slug' => ['nullable', 'string', 'max:180', Rule::unique('invitations', 'slug')->ignore($invitation)],
            'event_type' => ['required', 'string', 'max:60'],
            'primary_name' => ['required', 'string', 'max:120'],
            'secondary_name' => ['nullable', 'string', 'max:120'],
            'host_name' => ['nullable', 'string', 'max:160'],
            'event_date' => ['required', 'date'],
            'event_time' => ['nullable', 'date_format:H:i'],
            'timezone' => ['required', 'string', 'max:60'],
            'venue_name' => ['nullable', 'string', 'max:160'],
            'venue_address' => ['nullable', 'string', 'max:1000'],
            'maps_url' => ['nullable', 'url', 'max:1000'],
            'maps_embed_url' => ['nullable', 'url', 'max:1000'],
            'description' => ['nullable', 'string', 'max:1500'],
            'status' => ['required', 'in:draft,active,paused,expired'],
            'password' => ['nullable', 'string', 'max:80'],
            'theme_color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'theme_font' => ['required', 'string', Rule::in(array_keys(config('undi.theme_fonts')))],
            'youtube_url' => ['nullable', 'url', 'max:1000'],
            'share_message_template' => ['nullable', 'string', 'max:2500'],
            'music_enabled' => ['nullable', 'boolean'],
        ]);
    }

    private function activeTemplates(): Collection
    {
        return InvitationTemplate::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'slug', 'description', 'default_theme', 'is_premium']);
    }

    private function templateDefaults(Collection $templates): array
    {
        return $templates
            ->mapWithKeys(function (InvitationTemplate $template): array {
                $theme = $template->default_theme ?: data_get(config('undi.template_skins'), "{$template->slug}.default_theme", []);

                return [
                    $template->id => [
                        'name' => $template->name,
                        'description' => $template->description,
                        'theme_color' => $theme['color'] ?? '#a4785b',
                        'theme_font' => array_key_exists($theme['font'] ?? '', config('undi.theme_fonts')) ? $theme['font'] : 'Georgia',
                        'is_premium' => $template->is_premium,
                    ],
                ];
            })
            ->all();
    }

    private function ensureInvitationLimit(Request $request): void
    {
        $plan = $request->user()->subscription?->plan;
        $limit = $plan?->invitation_limit ?: 1;

        abort_if($request->user()->invitations()->count() >= $limit, 422, 'Limit undangan paket Anda sudah tercapai.');
    }

    private function authorizeOwner(Request $request, Invitation $invitation): void
    {
        abort_unless($invitation->user_id === $request->user()->id || $request->user()->hasRole('super-admin'), 403);
    }
}
