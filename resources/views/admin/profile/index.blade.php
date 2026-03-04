@extends('admin.layout.layout')

@section('content')
<div class="space-y-8">

    {{-- Page Header --}}
    <div class="flex items-center gap-4">
        <div class="w-14 h-14 bg-brand-dark rounded-2xl flex items-center justify-center text-white shadow-lg shadow-brand-dark/10 shrink-0">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
        </div>
        <div>
            <h2 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">Pengaturan Profil</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400 font-medium mt-1">Perbarui informasi akun dan detail login Anda.</p>
        </div>
    </div>

    {{-- Flash Messages (full-width, above grid) --}}
    @if (session('status') === 'profile-updated')
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
             x-transition:leave="transition ease-in duration-300" x-transition:leave-end="opacity-0 -translate-y-2"
             class="p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl text-sm font-bold flex items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Profil berhasil diperbarui.</span>
            </div>
            <button @click="show = false" class="text-emerald-600 hover:text-emerald-800 p-1 rounded-lg hover:bg-emerald-100 shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    @endif

    @if (session('status') === 'password-updated')
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
             x-transition:leave="transition ease-in duration-300" x-transition:leave-end="opacity-0 -translate-y-2"
             class="p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl text-sm font-bold flex items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Kata sandi berhasil diperbarui.</span>
            </div>
            <button @click="show = false" class="text-emerald-600 hover:text-emerald-800 p-1 rounded-lg hover:bg-emerald-100 shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
             x-transition:leave="transition ease-in duration-300" x-transition:leave-end="opacity-0 -translate-y-2"
             class="p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-2xl text-sm font-bold flex items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
            <button @click="show = false" class="text-rose-600 hover:text-rose-800 p-1 rounded-lg hover:bg-rose-100 shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    @endif

    {{-- 2-Column Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">

        {{-- LEFT: Informasi Akun --}}
        <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-700/50">
                <h3 class="text-base font-bold text-slate-800 dark:text-white tracking-tight">Informasi Akun</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-medium mt-1 italic">Pastikan data Anda selalu mutakhir.</p>
            </div>

            <div class="p-6">
                <form method="POST" action="{{ route('admin.profile.update') }}" class="space-y-5">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label for="name" class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">Nama Lengkap</label>
                        <input type="text" name="name" id="name" value="{{ old('name', Auth::user()->name) }}"
                            class="w-full rounded-xl border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 focus:border-brand-accent focus:ring-brand-accent text-sm py-2.5 px-4 bg-slate-50/50"
                            required placeholder="Masukkan nama Anda...">
                        @error('name')
                            <p class="mt-2 text-xs text-rose-600 font-medium italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">Alamat Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', Auth::user()->email) }}"
                            class="w-full rounded-xl border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 focus:border-brand-accent focus:ring-brand-accent text-sm py-2.5 px-4 bg-slate-50/50"
                            required placeholder="email@contoh.com">
                        @error('email')
                            <p class="mt-2 text-xs text-rose-600 font-medium italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-4 border-t border-slate-100 dark:border-slate-700 flex items-center justify-between gap-4">
                        <p class="text-xs text-slate-400 dark:text-slate-500 italic">Perubahan langsung tersimpan.</p>
                        <button type="submit"
                            class="bg-brand-dark hover:bg-brand-accent text-white font-bold py-2.5 px-6 rounded-xl transition-all shadow-lg shadow-brand-dark/10 active:scale-95 text-sm">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- RIGHT: Ubah Kata Sandi --}}
        <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-700/50">
                <h3 class="text-base font-bold text-slate-800 dark:text-white tracking-tight">Ubah Kata Sandi</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-medium mt-1 italic">Gunakan kata sandi yang kuat dan unik.</p>
            </div>

            <div class="p-6">
                <form method="POST" action="{{ route('admin.profile.password') }}" class="space-y-5"
                      x-data="{ showCurrent: false, showNew: false, showConfirm: false }">
                    @csrf
                    @method('PATCH')

                    {{-- Current Password --}}
                    <div>
                        <label for="current_password" class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">
                            Kata Sandi Saat Ini
                        </label>
                        <div class="relative">
                            <input :type="showCurrent ? 'text' : 'password'"
                                   name="current_password" id="current_password" autocomplete="current-password"
                                   class="w-full rounded-xl border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 focus:border-brand-accent focus:ring-brand-accent text-sm py-2.5 px-4 pr-10 bg-slate-50/50"
                                   placeholder="Masukkan kata sandi saat ini...">
                            <button type="button" @click="showCurrent = !showCurrent"
                                    class="absolute inset-y-0 right-3 flex items-center text-slate-400 hover:text-slate-600">
                                <svg x-show="!showCurrent" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg x-show="showCurrent" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                </svg>
                            </button>
                        </div>
                        @error('current_password')
                            <p class="mt-2 text-xs text-rose-600 font-medium italic">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- New Password --}}
                    <div>
                        <label for="password" class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">
                            Kata Sandi Baru
                        </label>
                        <div class="relative">
                            <input :type="showNew ? 'text' : 'password'"
                                   name="password" id="password" autocomplete="new-password"
                                   class="w-full rounded-xl border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 focus:border-brand-accent focus:ring-brand-accent text-sm py-2.5 px-4 pr-10 bg-slate-50/50"
                                   placeholder="Min. 8 karakter...">
                            <button type="button" @click="showNew = !showNew"
                                    class="absolute inset-y-0 right-3 flex items-center text-slate-400 hover:text-slate-600">
                                <svg x-show="!showNew" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg x-show="showNew" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-2 text-xs text-rose-600 font-medium italic">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Confirm Password --}}
                    <div>
                        <label for="password_confirmation" class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">
                            Konfirmasi Kata Sandi
                        </label>
                        <div class="relative">
                            <input :type="showConfirm ? 'text' : 'password'"
                                   name="password_confirmation" id="password_confirmation" autocomplete="new-password"
                                   class="w-full rounded-xl border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 focus:border-brand-accent focus:ring-brand-accent text-sm py-2.5 px-4 pr-10 bg-slate-50/50"
                                   placeholder="Ulangi kata sandi baru...">
                            <button type="button" @click="showConfirm = !showConfirm"
                                    class="absolute inset-y-0 right-3 flex items-center text-slate-400 hover:text-slate-600">
                                <svg x-show="!showConfirm" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg x-show="showConfirm" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                </svg>
                            </button>
                        </div>
                        @error('password_confirmation')
                            <p class="mt-2 text-xs text-rose-600 font-medium italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-4 border-t border-slate-100 dark:border-slate-700 flex items-center justify-between gap-4">
                        <p class="text-xs text-slate-400 dark:text-slate-500 italic">Minimal 8 karakter.</p>
                        <button type="submit"
                            class="bg-brand-dark hover:bg-brand-accent text-white font-bold py-2.5 px-6 rounded-xl transition-all shadow-lg shadow-brand-dark/10 active:scale-95 text-sm">
                            Perbarui Kata Sandi
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
