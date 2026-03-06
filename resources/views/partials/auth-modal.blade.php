{{--
    Auth Modal – Login / Forgot / Reset password
    Requires Alpine.js and authModal() function registered on window.
    Include this partial on any page that needs the modal:
        @include('partials.auth-modal')
--}}

{{-- CSRF meta (needed by authModal.js) --}}
@if(!isset($csrfMetaAlreadyRendered))
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endif

<div x-data="authModal()" @open-auth-modal.window="openModal()" @keydown.escape.window="closeModal()">

    {{-- No standalone trigger needed – the map page dispatches 'open-auth-modal' event --}}

    {{-- ─── BACKDROP + MODAL ───────────────────────────────────────── --}}
    <div x-show="open"
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[9999] flex items-center justify-center p-4">

        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="closeModal()"></div>

        {{-- Card --}}
        <div class="relative z-10 w-full max-w-sm bg-white rounded-3xl shadow-2xl overflow-hidden"
             x-trap="open">

            {{-- ── Decorative header bar ──────────────────────────────── --}}
            <div class="h-1.5 w-full bg-gradient-to-r from-brand-dark to-brand-accent"></div>

            {{-- ── Toast notification ─────────────────────────────────── --}}
            <div x-show="toast"
                 x-cloak
                 x-transition
                 class="mx-6 mt-4 px-4 py-2.5 rounded-xl text-sm font-medium text-center"
                 :class="toastIsError ? 'bg-rose-50 text-rose-700 border border-rose-100' : 'bg-emerald-50 text-emerald-700 border border-emerald-100'"
                 x-text="toast"></div>

            {{-- ════════════════════════════════════════════════════════ --}}
            {{-- VIEW 1: LOGIN                                            --}}
            {{-- ════════════════════════════════════════════════════════ --}}
            <template x-if="mode === 'login'">
                <div class="p-6 sm:p-8">
                    {{-- Logo / Title --}}
                    <div class="text-center mb-6">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-brand-dark mb-3">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-slate-800">Masuk ke Dashboard</h2>
                        <p class="text-sm text-slate-400">GIS Pendidikan Kota Blitar</p>
                    </div>

                    {{-- Error --}}
                    <div x-show="error" x-text="error"
                         x-cloak
                         class="mb-4 px-4 py-2.5 bg-rose-50 border border-rose-100 text-rose-700 text-sm rounded-xl"></div>

                    {{-- Form --}}
                    <form @submit.prevent="login()" class="space-y-4">
                        <div>
                            <label for="auth-email" class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Email</label>
                            <input id="auth-email" type="email" x-model="email" required autofocus
                                   class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-800 focus:border-brand-accent focus:ring-brand-accent transition"
                                   placeholder="admin@example.com">
                        </div>

                        <div>
                            <label for="auth-password" class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Password</label>
                            <div class="relative">
                                <input id="auth-password" :type="showPassword ? 'text' : 'password'" x-model="password" required
                                       class="w-full rounded-xl border border-slate-200 px-4 py-2.5 pr-10 text-sm text-slate-800 focus:border-brand-accent focus:ring-brand-accent transition"
                                       placeholder="••••••••">
                                <button type="button" @click="showPassword = !showPassword"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                    <svg x-show="!showPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <svg x-show="showPassword" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                </button>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" x-model="remember" class="rounded border-slate-300 text-brand-dark focus:ring-brand-accent">
                                <span class="text-sm text-slate-600">Ingat saya</span>
                            </label>
                            <button type="button" @click="resetEmail = email; mode = 'forgot'; error = ''"
                                    class="text-sm text-brand-dark hover:text-brand-accent font-medium transition">
                                Lupa password?
                            </button>
                        </div>

                        <button type="submit"
                                :disabled="loading"
                                class="w-full py-3 bg-brand-dark hover:bg-brand-accent text-white text-sm font-bold rounded-xl shadow-md shadow-brand-dark/20 transition-all duration-200 flex items-center justify-center gap-2"
                                :class="loading ? 'opacity-70 cursor-not-allowed' : ''">
                            <svg x-show="loading" x-cloak class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            <span x-text="loading ? 'Masuk...' : 'Masuk'"></span>
                        </button>
                    </form>
                </div>
            </template>

            {{-- ════════════════════════════════════════════════════════ --}}
            {{-- VIEW 2: FORGOT – enter email & request code             --}}
            {{-- ════════════════════════════════════════════════════════ --}}
            <template x-if="mode === 'forgot'">
                <div class="p-6 sm:p-8">
                    <div class="text-center mb-6">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-amber-100 mb-3">
                            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-slate-800">Lupa Password</h2>
                        <p class="text-sm text-slate-400 mt-1">Masukkan email Anda untuk menerima kode verifikasi 6 digit.</p>
                    </div>

                    <div x-show="error" x-text="error"
                         x-cloak
                         class="mb-4 px-4 py-2.5 bg-rose-50 border border-rose-100 text-rose-700 text-sm rounded-xl"></div>

                    <form @submit.prevent="sendCode()" class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Email</label>
                            <input type="email" x-model="resetEmail" required autofocus
                                   class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-800 focus:border-brand-accent focus:ring-brand-accent transition"
                                   placeholder="Masukkan email Anda">
                        </div>

                        <button type="submit"
                                :disabled="loading"
                                class="w-full py-3 bg-brand-dark hover:bg-brand-accent text-white text-sm font-bold rounded-xl shadow-md shadow-brand-dark/20 transition-all duration-200 flex items-center justify-center gap-2"
                                :class="loading ? 'opacity-70 cursor-not-allowed' : ''">
                            <svg x-show="loading" x-cloak class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            <span x-text="loading ? 'Mengirim...' : 'Kirim Kode'"></span>
                        </button>

                        <button type="button" @click="mode = 'login'; error = ''"
                                class="w-full text-center text-sm text-slate-500 hover:text-slate-700 transition">
                            ← Kembali ke Login
                        </button>
                    </form>
                </div>
            </template>

            {{-- ════════════════════════════════════════════════════════ --}}
            {{-- VIEW 3: RESET – enter code + new password               --}}
            {{-- ════════════════════════════════════════════════════════ --}}
            <template x-if="mode === 'reset'">
                <div class="p-6 sm:p-8">
                    <div class="text-center mb-6">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-emerald-100 mb-3">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-slate-800">Reset Password</h2>
                        <p class="text-sm text-slate-400 mt-1">Masukkan kode 6 digit yang dikirim ke <strong x-text="resetEmail"></strong>.</p>
                    </div>

                    <div x-show="error" x-text="error"
                         x-cloak
                         class="mb-4 px-4 py-2.5 bg-rose-50 border border-rose-100 text-rose-700 text-sm rounded-xl"></div>

                    <form @submit.prevent="resetPassword()" class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Kode Verifikasi</label>
                            <input type="text" x-model="code" maxlength="6" pattern="\d{6}" required autofocus
                                   class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-800 tracking-[0.4em] text-center font-bold focus:border-brand-accent focus:ring-brand-accent transition"
                                   placeholder="● ● ● ● ● ●">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Password Baru</label>
                            <div class="relative">
                                <input :type="showNewPassword ? 'text' : 'password'" x-model="newPassword" required minlength="8"
                                       class="w-full rounded-xl border border-slate-200 px-4 py-2.5 pr-10 text-sm text-slate-800 focus:border-brand-accent focus:ring-brand-accent transition"
                                       placeholder="Min. 8 karakter">
                                <button type="button" @click="showNewPassword = !showNewPassword"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                    <svg x-show="!showNewPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <svg x-show="showNewPassword" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                </button>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Konfirmasi Password</label>
                            <input type="password" x-model="confirmPassword" required
                                   class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-800 focus:border-brand-accent focus:ring-brand-accent transition"
                                   placeholder="Ulangi password baru">
                        </div>

                        <button type="submit"
                                :disabled="loading"
                                class="w-full py-3 bg-brand-dark hover:bg-brand-accent text-white text-sm font-bold rounded-xl shadow-md shadow-brand-dark/20 transition-all duration-200 flex items-center justify-center gap-2"
                                :class="loading ? 'opacity-70 cursor-not-allowed' : ''">
                            <svg x-show="loading" x-cloak class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            <span x-text="loading ? 'Memproses...' : 'Reset Password'"></span>
                        </button>

                        <div class="flex justify-between text-sm">
                            <button type="button" @click="sendCode()"
                                    class="text-brand-dark hover:text-brand-accent font-medium transition"
                                    :disabled="loading">
                                Kirim ulang kode
                            </button>
                            <button type="button" @click="mode = 'forgot'; error = ''"
                                    class="text-slate-500 hover:text-slate-700 transition">
                                Ganti email
                            </button>
                        </div>
                    </form>
                </div>
            </template>

        </div>
    </div>
</div>
