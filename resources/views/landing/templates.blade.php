@extends('layouts.app')

@section('content')
<section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
    <p class="text-sm font-medium uppercase tracking-[0.2em] text-[#a4785b]">Template</p>
    <h1 class="mt-3 max-w-3xl text-4xl font-semibold">Template mobile-first untuk berbagai acara.</h1>
    <div class="mt-10 grid gap-5 md:grid-cols-3">
        @foreach ($templates as $template)
            <article class="overflow-hidden rounded-2xl border border-[#eadfd4] bg-white">
                <img class="h-64 w-full object-cover" src="{{ $template->preview_image_path }}" alt="{{ $template->name }}" loading="lazy">
                <div class="p-6">
                    <div class="flex items-center justify-between gap-3">
                        <h2 class="font-semibold">{{ $template->name }}</h2>
                        <span class="rounded-full bg-[#f4eee7] px-3 py-1 text-xs text-[#706a63]">{{ config('undi.event_types')[$template->category] ?? ucfirst($template->category) }}</span>
                    </div>
                    <p class="mt-3 text-sm leading-6 text-[#706a63]">{{ $template->description }}</p>
                    <a class="mt-5 inline-flex w-full justify-center rounded-full bg-[#24211e] px-4 py-2.5 text-sm font-medium text-white" href="{{ route('dashboard.invitations.create', ['template_id' => $template->id]) }}">Pakai template</a>
                </div>
            </article>
        @endforeach
    </div>
    <div class="mt-8">{{ $templates->links() }}</div>
</section>
@endsection
