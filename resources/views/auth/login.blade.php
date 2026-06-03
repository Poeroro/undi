@extends('layouts.app')

@section('content')
<section class="mx-auto grid min-h-[calc(100vh-180px)] max-w-6xl items-center gap-10 px-4 py-12 sm:px-6 lg:grid-cols-2 lg:px-8">
    <div>
        <p class="text-sm font-medium uppercase tracking-[0.2em] text-[#a4785b]">Masuk</p>
        <h1 class="mt-3 text-4xl font-semibold">Kelola undangan dari dashboard yang rapi.</h1>
        <p class="mt-4 max-w-lg text-sm leading-7 text-[#706a63]">Masuk untuk mengatur acara, tamu, RSVP, ucapan, galeri, amplop digital, dan statistik pengunjung.</p>
    </div>
    <form method="post" action="{{ route('login.authenticate') }}" class="premium-surface rounded-3xl p-6 sm:p-8">
        @csrf
        <label class="block text-sm font-medium">Email</label>
        <input class="form-input mt-2" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required>
        @error('email') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror

        <label class="mt-5 block text-sm font-medium">Password</label>
        <input class="form-input mt-2" name="password" type="password" autocomplete="current-password" required>
        @error('password') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror

        <div class="mt-5 flex items-center justify-between gap-4 text-sm">
            <label class="flex items-center gap-2 text-[#706a63]">
                <input type="checkbox" name="remember" value="1" class="rounded border-[#d9cbbd]">
                Ingat saya
            </label>
            <a class="text-[#7a553f]" href="{{ route('password.request') }}">Lupa password</a>
        </div>

        <button class="mt-7 w-full rounded-full bg-[#24211e] px-5 py-3 text-sm font-medium text-white">Masuk</button>
        <p class="mt-5 text-center text-sm text-[#706a63]">Belum punya akun? <a class="font-medium text-[#7a553f]" href="{{ route('register') }}">Daftar gratis</a></p>
    </form>
</section>
@endsection
