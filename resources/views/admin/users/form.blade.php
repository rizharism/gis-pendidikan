@extends('admin.layout.layout')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight">
                    {{ isset($user) ? 'Edit Pengguna' : 'Tambah Pengguna' }}
                </h2>
                <p class="text-slate-500 text-sm font-medium mt-1">
                    {{ isset($user) ? 'Perbarui informasi dan hak akses pengguna.' : 'Lengkapi formulir untuk menambahkan pengguna baru.' }}
                </p>
            </div>
            <a href="{{ route('admin.users.index') }}" class="text-sm font-bold text-slate-500 hover:text-slate-800 transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 19l-7-7 7-7m8 14l-7-7 7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Kembali
            </a>
        </div>

        @if (session('error'))
            <div class="p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-2xl text-sm font-bold flex items-center gap-3">
                <svg class="w-5 h-5 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ isset($user) ? route('admin.users.update', $user->id) : route('admin.users.store') }}" method="POST">
            @csrf
            @if(isset($user))
                @method('PUT')
            @endif

            <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Name --}}
                    <div class="md:col-span-2">
                        <label for="name" class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">Nama Lengkap</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name ?? '') }}"
                            class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-4 bg-slate-50/50"
                            placeholder="Masukkan nama lengkap..." required>
                        @error('name') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Email --}}
                    <div class="md:col-span-2">
                        <label for="email" class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">Alamat Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email ?? '') }}"
                            class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-4 bg-slate-50/50"
                            placeholder="contoh@email.com" required>
                        @error('email') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">
                            Password
                            @if(isset($user))
                                <span class="text-slate-400 font-medium normal-case">(kosongkan jika tidak ingin mengubah)</span>
                            @endif
                        </label>
                        <input type="password" id="password" name="password"
                            class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-4 bg-slate-50/50"
                            placeholder="Min. 8 karakter" {{ isset($user) ? '' : 'required' }}>
                        @error('password') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Confirm Password --}}
                    <div>
                        <label for="password_confirmation" class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">Konfirmasi Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-4 bg-slate-50/50"
                            placeholder="Ulangi password" {{ isset($user) ? '' : 'required' }}>
                    </div>

                    {{-- Role --}}
                    <div class="md:col-span-2">
                        <label for="role" class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">Hak Akses (Role)</label>
                        <select id="role" name="role"
                            class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-4 bg-slate-50/50"
                            required>
                            <option value="">Pilih Role</option>
                            <option value="admin" {{ old('role', $user->role ?? '') === 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="super-admin" {{ old('role', $user->role ?? '') === 'super-admin' ? 'selected' : '' }}>Super Admin</option>
                        </select>
                        <p class="text-slate-400 text-xs mt-1.5 italic">
                            <strong>Admin:</strong> Dapat mengelola data fasilitas. &nbsp;|&nbsp;
                            <strong>Super Admin:</strong> Dapat mengakses semua fitur termasuk manajemen pengguna.
                        </p>
                        @error('role') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                </div>

                <div class="flex gap-3 mt-8 pt-6 border-t border-slate-100">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-2xl transition-all shadow-lg shadow-indigo-100">
                        {{ isset($user) ? 'Perbarui Pengguna' : 'Simpan Pengguna' }}
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="px-8 py-3 rounded-2xl border border-slate-200 text-slate-500 font-bold hover:bg-slate-50 transition-all">
                        Batal
                    </a>
                </div>
            </div>
        </form>
    </div>
@endsection
