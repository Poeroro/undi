@extends('layouts.app')

@section('content')
@php
    $selectedTemplate = old('template_id', $invitation->template_id);
    $selectedTemplateDefault = $selectedTemplate ? ($templateDefaults[$selectedTemplate] ?? null) : null;
@endphp
<section class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
    @include('dashboard.partials.nav')
    @if ($invitation->exists)
        @include('dashboard.invitations.partials.section-nav', ['invitation' => $invitation])
    @endif

    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-sm text-[#706a63]">{{ $invitation->exists ? 'Perbarui data acara' : 'Buat undangan baru' }}</p>
            <h1 class="mt-2 text-3xl font-semibold">{{ $invitation->exists ? $invitation->title : 'Detail undangan' }}</h1>
        </div>
        @if ($invitation->exists)
            <a class="inline-flex justify-center rounded-full border border-[#d9cbbd] px-5 py-3 text-sm" href="{{ $invitation->publicUrl() }}" target="_blank">Preview publik</a>
        @endif
    </div>

    @if ($errors->any())
        <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ $errors->first() }}</div>
    @endif

    <form method="post" action="{{ $invitation->exists ? route('dashboard.invitations.update', $invitation) : route('dashboard.invitations.store') }}" class="rounded-3xl border border-[#eadfd4] bg-white/75 p-5 sm:p-7" data-template-form data-template-defaults='@json($templateDefaults)'>
        @csrf
        @if ($invitation->exists) @method('put') @endif

        <div class="grid gap-5 md:grid-cols-2">
            <label class="block text-sm font-medium">Judul undangan
                <input class="form-input mt-2" name="title" value="{{ old('title', $invitation->title) }}" required>
            </label>
            <label class="block text-sm font-medium">Slug URL
                <input class="form-input mt-2" name="slug" value="{{ old('slug', $invitation->slug) }}" placeholder="andi-sinta">
            </label>
            <label class="block text-sm font-medium">Template
                <select class="form-input mt-2" name="template_id" data-template-select>
                    <option value="">Pilih template</option>
                    @foreach ($templates as $template)
                        <option value="{{ $template->id }}" @selected($selectedTemplate == $template->id)>{{ $template->name }}{{ $template->is_premium ? ' - Premium' : '' }}</option>
                    @endforeach
                </select>
                <span class="mt-2 flex items-center gap-2 text-xs text-[#706a63]">
                    <span class="size-3 rounded-full border border-[#d9cbbd]" data-template-swatch style="background: {{ $selectedTemplateDefault['theme_color'] ?? old('theme_color', $invitation->theme_color ?: '#a4785b') }}"></span>
                    <span data-template-summary>{{ $selectedTemplateDefault['description'] ?? 'Belum ada template dipilih.' }}</span>
                </span>
            </label>
            <label class="block text-sm font-medium">Jenis acara
                <select class="form-input mt-2" name="event_type">
                    @foreach (config('undi.event_types') as $value => $label)
                        <option value="{{ $value }}" @selected(old('event_type', $invitation->event_type) === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </label>
            <label class="block text-sm font-medium">Nama utama
                <input class="form-input mt-2" name="primary_name" value="{{ old('primary_name', $invitation->primary_name) }}" required>
            </label>
            <label class="block text-sm font-medium">Nama pasangan / sub acara
                <input class="form-input mt-2" name="secondary_name" value="{{ old('secondary_name', $invitation->secondary_name) }}">
            </label>
            <label class="block text-sm font-medium">Nama keluarga / host
                <input class="form-input mt-2" name="host_name" value="{{ old('host_name', $invitation->host_name) }}">
            </label>
            <label class="block text-sm font-medium">Tanggal acara
                <input class="form-input mt-2" type="date" name="event_date" value="{{ old('event_date', $invitation->event_date?->format('Y-m-d')) }}" required>
            </label>
            <label class="block text-sm font-medium">Waktu acara
                <input class="form-input mt-2" type="time" name="event_time" value="{{ old('event_time', substr((string) $invitation->event_time, 0, 5)) }}">
            </label>
            <label class="block text-sm font-medium">Zona waktu
                <input class="form-input mt-2" name="timezone" value="{{ old('timezone', $invitation->timezone ?: 'Asia/Jakarta') }}" required>
            </label>
            <label class="block text-sm font-medium">Lokasi
                <input class="form-input mt-2" name="venue_name" value="{{ old('venue_name', $invitation->venue_name) }}">
            </label>
            <label class="block text-sm font-medium">Google Maps URL
                <input class="form-input mt-2" name="maps_url" value="{{ old('maps_url', $invitation->maps_url) }}">
            </label>
            <label class="block text-sm font-medium md:col-span-2">Alamat lengkap
                <textarea class="form-input mt-2 min-h-28" name="venue_address">{{ old('venue_address', $invitation->venue_address) }}</textarea>
            </label>
            <label class="block text-sm font-medium md:col-span-2">Deskripsi acara
                <textarea class="form-input mt-2 min-h-28" name="description">{{ old('description', $invitation->description) }}</textarea>
            </label>
            <label class="block text-sm font-medium">Status
                <select class="form-input mt-2" name="status">
                    @foreach (['draft' => 'Draft', 'active' => 'Aktif', 'paused' => 'Nonaktif', 'expired' => 'Kedaluwarsa'] as $value => $label)
                        <option value="{{ $value }}" @selected(old('status', $invitation->status) === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </label>
            <label class="block text-sm font-medium">Password opsional
                <input class="form-input mt-2" name="password" type="password" placeholder="{{ $invitation->exists ? 'Kosongkan jika tidak diubah' : '' }}">
            </label>
            <label class="block text-sm font-medium">Warna tema
                <input class="form-input mt-2 h-12" name="theme_color" type="color" value="{{ old('theme_color', $invitation->theme_color ?: '#a4785b') }}">
            </label>
            <label class="block text-sm font-medium">Font tema
                <select class="form-input mt-2" name="theme_font" data-template-font>
                    @foreach ($themeFonts as $value => $font)
                        <option value="{{ $value }}" style="font-family: {{ $font['family'] }}" @selected(old('theme_font', $invitation->theme_font ?: 'Georgia') === $value)>{{ $font['label'] }}</option>
                    @endforeach
                </select>
            </label>
            <label class="block text-sm font-medium md:col-span-2">YouTube URL
                <input class="form-input mt-2" name="youtube_url" value="{{ old('youtube_url', $invitation->youtube_url) }}">
            </label>
            <label class="block text-sm font-medium md:col-span-2">Template pesan WhatsApp
                <textarea class="form-input mt-2 min-h-36" name="share_message_template">{{ old('share_message_template', $invitation->share_message_template ?: config('undi.share_message')) }}</textarea>
            </label>
        </div>

        <label class="mt-5 flex items-center gap-3 text-sm text-[#514a43]">
            <input type="hidden" name="music_enabled" value="0">
            <input type="checkbox" name="music_enabled" value="1" @checked(old('music_enabled', $invitation->music_enabled))>
            Aktifkan musik latar
        </label>

        <div class="mt-8 flex justify-end">
            <button class="rounded-full bg-[#24211e] px-6 py-3 text-sm font-medium text-white">Simpan</button>
        </div>
    </form>
</section>

<script>
    (() => {
        const form = document.querySelector('[data-template-form]');

        if (! form) {
            return;
        }

        const defaults = JSON.parse(form.dataset.templateDefaults || '{}');
        const templateSelect = form.querySelector('[data-template-select]');
        const colorInput = form.querySelector('input[name="theme_color"]');
        const fontSelect = form.querySelector('[data-template-font]');
        const swatch = form.querySelector('[data-template-swatch]');
        const summary = form.querySelector('[data-template-summary]');

        templateSelect?.addEventListener('change', () => {
            const template = defaults[templateSelect.value];

            if (! template) {
                return;
            }

            colorInput.value = template.theme_color;
            fontSelect.value = template.theme_font;
            swatch.style.background = template.theme_color;
            summary.textContent = template.description || `${template.name} dipilih.`;
        });
    })();
</script>
@endsection
