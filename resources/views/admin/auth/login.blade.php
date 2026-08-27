<!DOCTYPE html>
<html lang="id" class="h-full" x-data="{ darkMode: true }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login Admin — PT Jaringan Teknologi Sejahtera</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo/jts-favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/scss/app.scss', 'resources/js/admin.ts'])
</head>
<body class="h-full bg-ink-950 font-sans antialiased overflow-hidden">

<div class="fixed inset-0 bg-grid-pattern opacity-[0.04] pointer-events-none" style="background-size:40px 40px" aria-hidden="true"></div>
<div class="fixed top-1/4 -left-32 w-96 h-96 rounded-full bg-brand/10 blur-[120px] pointer-events-none animate-pulse" aria-hidden="true"></div>
<div class="fixed bottom-1/4 -right-32 w-96 h-96 rounded-full bg-brand/8 blur-[120px] pointer-events-none animate-pulse" style="animation-delay:1.5s" aria-hidden="true"></div>

<div class="min-h-full flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="flex justify-center mb-6">
            <a href="{{ route('home') }}" class="flex flex-col items-center gap-3 group" aria-label="JTS Home">
                <img src="{{ asset('images/logo/jts-logo-mark-square.png') }}"
                     alt="Logo PT Jaringan Teknologi Sejahtera"
                     class="h-20 w-20 object-contain group-hover:scale-105 transition-transform duration-300 drop-shadow-[0_0_25px_rgba(250,134,0,0.35)]">
            </a>
        </div>

        <h1 class="text-center text-2xl font-bold text-white mb-2">Masuk ke Dashboard</h1>
        <p class="text-center text-ink-400 text-sm mb-8">Akses dibatasi untuk staf resmi PT JTS</p>
    </div>

    <div class="sm:mx-auto sm:w-full sm:max-w-md px-4">
        <div class="glass-card-dark rounded-2xl p-8 border border-white/8 shadow-glass">

            @if($errors->any())
                <div class="alert-error mb-6" role="alert">
                    <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    <div>
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.submit') }}" x-data="{ loading: false, showPw: false }" @submit="loading = true">
                @csrf

                <div class="mb-5">
                    <label for="email" class="block text-sm font-medium text-ink-300 mb-1.5">Email</label>
                    <input id="email" name="email" type="email" autocomplete="email" required autofocus value="{{ old('email') }}"
                           class="w-full px-4 py-3 bg-ink-900/60 border border-ink-700 text-white placeholder-ink-600 rounded-xl focus:outline-none focus:border-brand transition-colors text-sm"
                           placeholder="admin@ptjts.id">
                </div>

                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-ink-300 mb-1.5">Password</label>
                    <div class="relative">
                        <input id="password" name="password" :type="showPw ? 'text' : 'password'" autocomplete="current-password" required
                               class="w-full px-4 py-3 pr-12 bg-ink-900/60 border border-ink-700 text-white placeholder-ink-600 rounded-xl focus:outline-none focus:border-brand transition-colors text-sm"
                               placeholder="••••••••">
                        <button type="button" @click="showPw = !showPw" class="absolute right-3 top-1/2 -translate-y-1/2 text-ink-500 hover:text-ink-300 transition-colors" :aria-label="showPw ? 'Sembunyikan password' : 'Tampilkan password'">
                            <svg x-show="!showPw" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="showPw" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-ink-600 bg-ink-900 text-brand focus:ring-brand">
                        <span class="text-sm text-ink-400">Ingat saya</span>
                    </label>
                </div>

                <button type="submit" :disabled="loading"
                        class="w-full flex items-center justify-center gap-2 py-3 bg-brand hover:bg-brand-600 disabled:opacity-60 text-white font-semibold rounded-xl transition-all duration-300 hover:shadow-glow text-sm">
                    <svg x-show="loading" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                    </svg>
                    <span x-show="!loading">Masuk ke Dashboard</span>
                    <span x-show="loading" x-cloak>Memproses...</span>
                </button>
            </form>

            <div class="mt-6 pt-6 border-t border-white/5 text-center">
                <a href="{{ route('home') }}" class="text-xs text-ink-500 hover:text-brand transition-colors">← Kembali ke Website Utama</a>
            </div>
        </div>

        <p class="text-center text-xs text-ink-600 mt-6">© {{ date('Y') }} PT Jaringan Teknologi Sejahtera. Akses tidak sah dilarang.</p>
    </div>
</div>
</body>
</html>
