<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin — Maison Parfum</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; }
        .font-display { font-family: 'Playfair Display', Georgia, serif; }
    </style>
</head>
<body class="flex min-h-screen items-center justify-center bg-stone-950 px-4">

    <div class="w-full max-w-md">
        <div class="mb-8 text-center">
            <span class="text-3xl text-amber-400">✦</span>
            <h1 class="mt-2 font-display text-3xl text-amber-200">Maison Parfum</h1>
            <p class="mt-2 text-sm text-stone-400">Panel Administrasi</p>
        </div>

        @if(session('success'))
            <div class="mb-4 rounded-lg border border-emerald-600 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-300">
                {{ session('success') }}
            </div>
        @endif

        <div class="rounded-2xl border border-stone-800 bg-stone-900 p-8">
            <h2 class="font-display text-xl text-white">Masuk</h2>
            <p class="mt-1 text-sm text-stone-500">Gunakan kredensial admin Anda.</p>

            <form method="POST" action="{{ route('admin.login.submit') }}" class="mt-6 space-y-4">
                @csrf

                <div>
                    <label for="email" class="mb-1 block text-sm font-medium text-stone-300">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                           class="w-full rounded-lg border border-stone-700 bg-stone-800 px-4 py-2.5 text-sm text-white placeholder-stone-500 focus:border-amber-500 focus:outline-none">
                    @error('email')
                        <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="mb-1 block text-sm font-medium text-stone-300">Password</label>
                    <input type="password" id="password" name="password" required
                           class="w-full rounded-lg border border-stone-700 bg-stone-800 px-4 py-2.5 text-sm text-white placeholder-stone-500 focus:border-amber-500 focus:outline-none">
                    @error('password')
                        <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <label class="flex items-center gap-2 text-sm text-stone-400">
                    <input type="checkbox" name="remember" class="rounded border-stone-600 bg-stone-800 text-amber-500 focus:ring-amber-500">
                    Ingat saya
                </label>

                <button type="submit" class="w-full rounded-full bg-amber-500 px-6 py-3 text-sm font-semibold text-stone-950 transition hover:bg-amber-400">
                    Masuk
                </button>
            </form>
        </div>

        <p class="mt-6 text-center text-xs text-stone-500">
            <a href="{{ route('home') }}" class="hover:text-amber-400">← Kembali ke Website</a>
        </p>
    </div>

</body>
</html>