@extends('layouts.app')

@section('content')
<section class="mx-auto grid min-h-[calc(100vh-180px)] max-w-6xl items-center gap-10 px-4 py-12 sm:px-6 lg:grid-cols-2 lg:px-8">
    <div>
        <p class="text-sm font-medium uppercase tracking-[0.2em] text-[#a4785b]">Daftar</p>
        <h1 class="mt-3 text-4xl font-semibold">Mulai dengan satu undangan gratis.</h1>
        <p class="mt-4 max-w-lg text-sm leading-7 text-[#706a63]">Akun pertama langsung mendapat paket Free. Anda bisa naik paket ketika butuh tamu lebih banyak, galeri lebih luas, atau custom domain.</p>
    </div>
    <form method="post" action="{{ route('register.store') }}" class="premium-surface rounded-3xl p-6 sm:p-8">
        @csrf
        <label class="block text-sm font-medium">Nama</label>
        <input class="form-input mt-2" name="name" value="{{ old('name') }}" autocomplete="name" required>
        @error('name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror

        <label class="mt-5 block text-sm font-medium">Email</label>
        <input class="form-input mt-2" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required>
        @error('email') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror

        <label class="mt-5 block text-sm font-medium">Password</label>
        <input class="form-input mt-2" name="password" type="password" autocomplete="new-password" required>
        @error('password') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror

        <label class="mt-5 block text-sm font-medium">Konfirmasi password</label>
        <input class="form-input mt-2" name="password_confirmation" type="password" autocomplete="new-password" required>

        <button class="mt-7 w-full rounded-full bg-[#24211e] px-5 py-3 text-sm font-medium text-white">Buat akun</button>
        <p class="mt-5 text-center text-sm text-[#706a63]">Sudah punya akun? <a class="font-medium text-[#7a553f]" href="{{ route('login') }}">Masuk</a></p>
    </form>
</section>
@endsection
