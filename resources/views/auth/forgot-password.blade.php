@extends('layouts.app')

@section('content')
<section class="mx-auto flex min-h-[calc(100vh-180px)] max-w-xl items-center px-4 py-12 sm:px-6 lg:px-8">
    <div class="premium-surface w-full rounded-3xl p-6 sm:p-8">
        <p class="text-sm font-medium uppercase tracking-[0.2em] text-[#a4785b]">Reset password</p>
        <h1 class="mt-3 text-3xl font-semibold">Pemulihan akun</h1>
        <p class="mt-4 text-sm leading-7 text-[#706a63]">Struktur route reset password sudah disiapkan. Hubungkan mailer Laravel untuk mengirim tautan reset dari environment produksi Anda.</p>
        <a class="mt-7 inline-flex rounded-full bg-[#24211e] px-5 py-3 text-sm font-medium text-white" href="{{ route('login') }}">Kembali masuk</a>
    </div>
</section>
@endsection
