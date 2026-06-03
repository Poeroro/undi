<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $invitation->title }} - Password</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#faf7f2] text-[#24211e] antialiased">
    <main class="mx-auto flex min-h-screen max-w-md items-center px-4 py-10">
        <form method="post" action="{{ route('invitations.unlock', $invitation->slug) }}" class="premium-surface w-full rounded-3xl p-7">
            @csrf
            <p class="text-sm font-medium uppercase tracking-[0.2em] text-[#a4785b]">Undangan privat</p>
            <h1 class="mt-3 text-3xl font-semibold">{{ $invitation->title }}</h1>
            <p class="mt-3 text-sm leading-6 text-[#706a63]">Masukkan password untuk membuka halaman undangan.</p>
            <input class="form-input mt-6" name="password" type="password" required autofocus>
            @error('password') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            <button class="mt-5 w-full rounded-full bg-[#24211e] px-5 py-3 text-sm font-medium text-white">Buka undangan</button>
        </form>
    </main>
</body>
</html>
