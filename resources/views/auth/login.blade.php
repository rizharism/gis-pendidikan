<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login – {{ \App\Models\Setting::get('app_name', 'GIS Pendidikan') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased">

{{-- Full‑screen split layout --}}
<div class="min-h-screen grid grid-cols-1 lg:grid-cols-2">

    {{-- ── LEFT PANEL – Decorative ──────────────────────────────── --}}
    <div class="hidden lg:flex relative bg-brand-dark overflow-hidden flex-col items-center justify-center p-12">

        {{-- Background grid pattern --}}
        <div class="absolute inset-0 opacity-10 bg-[repeating-linear-gradient(0deg,transparent,transparent_39px,rgba(255,255,255,.3)_39px,rgba(255,255,255,.3)_40px),repeating-linear-gradient(90deg,transparent,transparent_39px,rgba(255,255,255,.3)_39px,rgba(255,255,255,.3)_40px)]">
        </div>

        {{-- Glowing circle accent --}}
        <div class="absolute top-1/4 -left-20 w-80 h-80 rounded-full bg-brand-accent/20 blur-3xl"></div>
        <div class="absolute bottom-1/4 -right-20 w-80 h-80 rounded-full bg-white/5 blur-3xl"></div>

        {{-- Map pin icon --}}
        <div class="relative z-10 text-center">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-3xl bg-white/10 backdrop-blur-sm border border-white/20 mb-6">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>

            <h1 class="text-3xl font-black text-white tracking-tight leading-tight">
                {{ \App\Models\Setting::get('app_name', 'GIS Pendidikan') }}
            </h1>
            <p class="text-white/60 mt-2 text-base">Kota Blitar</p>

            <div class="mt-10 space-y-3 text-left max-w-xs">
                @foreach([
                    ['📍', 'Peta Sebaran Fasilitas Pendidikan'],
                    ['🎓', 'Data SD, SMP, SMA & Universitas'],
                    ['📊', 'Dashboard Statistik Interaktif'],
                ] as [$icon, $text])
                    <div class="flex items-center gap-3 bg-white/10 rounded-2xl px-4 py-3">
                        <span class="text-lg">{{ $icon }}</span>
                        <span class="text-white/80 text-sm font-medium">{{ $text }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ── RIGHT PANEL – Login Form ─────────────────────────────── --}}
    <div class="flex items-center justify-center bg-slate-50 p-8">
        <div class="w-full max-w-sm">

            {{-- Mobile logo (hidden on lg) --}}
            <div class="lg:hidden text-center mb-8">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-brand-dark mb-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-slate-800">{{ \App\Models\Setting::get('app_name', 'GIS Pendidikan') }}</h2>
                <p class="text-sm text-slate-400">Kota Blitar</p>
            </div>

            {{-- Heading --}}
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Selamat Datang 👋</h2>
                <p class="text-sm text-slate-500 mt-1">Masuk ke panel administrasi.</p>
            </div>

            {{-- Session status --}}
            @if (session('status'))
                <div class="mb-5 px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-100 text-sm text-emerald-700 font-medium">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Validation errors --}}
            @if ($errors->any())
                <div class="mb-5 px-4 py-3 rounded-xl bg-rose-50 border border-rose-100 text-sm text-rose-700 font-medium">
                    {{ $errors->first() }}
                </div>
            @endif

            {{-- Login form --}}
            <form method="POST" action="{{ route('login') }}" class="space-y-5" x-data="{ showPwd: false }">
                @csrf

                {{-- Email --}}
                <div>
                    <label for="email"
                           class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Email</label>
                    <input id="email" type="email" name="email"
                           value="{{ old('email') }}"
                           required autofocus autocomplete="username"
                           class="w-full rounded-xl border @error('email') border-rose-400 @else border-slate-200 @enderror bg-white px-4 py-2.5 text-sm text-slate-800 shadow-sm focus:border-brand-accent focus:ring-brand-accent transition"
                           placeholder="admin@example.com">
                    @error('email')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label for="password"
                           class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Password</label>
                    <div class="relative">
                        <input id="password" :type="showPwd ? 'text' : 'password'" name="password"
                               required autocomplete="current-password"
                               class="w-full rounded-xl border @error('password') border-rose-400 @else border-slate-200 @enderror bg-white px-4 py-2.5 pr-10 text-sm text-slate-800 shadow-sm focus:border-brand-accent focus:ring-brand-accent transition"
                               placeholder="••••••••">
                        <button type="button" @click="showPwd = !showPwd"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                            <svg x-show="!showPwd" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="showPwd" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember me --}}
                <div class="flex items-center">
                    <label for="remember_me" class="inline-flex items-center gap-2 cursor-pointer">
                        <input id="remember_me" type="checkbox" name="remember"
                               class="rounded border-slate-300 text-brand-dark shadow-sm focus:ring-brand-accent">
                        <span class="text-sm text-slate-600">Ingat saya</span>
                    </label>
                </div>

                {{-- Submit --}}
                <button type="submit"
                        class="w-full py-3 bg-brand-dark hover:bg-brand-accent text-white text-sm font-bold rounded-xl shadow-md shadow-brand-dark/20 transition-all duration-200">
                    Masuk
                </button>
            </form>

            {{-- Back to map --}}
            <p class="mt-6 text-center text-sm text-slate-400">
                <a href="{{ route('map.index') }}" class="text-brand-dark hover:text-brand-accent font-medium transition">
                    ← Kembali ke Peta
                </a>
            </p>

        </div>
    </div>
</div>

</body>
</html>
