@extends('admin.layout.layout')

@section('content')
<div class="py-2">

    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-800 dark:text-white tracking-tight">Pengaturan Aplikasi</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Konfigurasi umum, peta, dan tampilan aplikasi.</p>
    </div>

    {{-- Success message --}}
    @if(session('status'))
        <div class="mb-6 flex items-center gap-3 px-4 py-3 bg-green-50 border border-green-200 rounded-2xl text-sm text-green-700 font-medium">
            <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            {{ session('status') }}
        </div>
    @endif

    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- ─── GENERAL ──────────────────────────────────────────── --}}
        <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden">
            <div class="bg-gradient-to-r from-brand-dark to-brand-accent px-6 py-4">
                <h2 class="text-sm font-bold text-white uppercase tracking-widest">Umum</h2>
            </div>

            <div class="p-6 space-y-6">

                {{-- App Name --}}
                <div>
                    <label for="app_name" class="block text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-widest mb-2">Nama Aplikasi</label>
                    <input type="text" id="app_name" name="app_name"
                        value="{{ old('app_name', $settings['app_name']) }}"
                        class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 text-sm text-slate-800 dark:text-white px-4 py-2.5 focus:border-brand-accent focus:ring-brand-accent transition"
                        placeholder="GIS Pendidikan">
                    @error('app_name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- Logo Upload --}}
                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-widest mb-2">Logo Aplikasi</label>
                    <div class="flex items-center gap-6">
                        {{-- Preview --}}
                        <div class="w-16 h-16 rounded-2xl bg-slate-100 border border-slate-200 flex items-center justify-center overflow-hidden shrink-0">
                            @if($settings['app_logo_path'])
                                <img id="logo-preview"
                                     src="{{ Storage::disk('public')->url($settings['app_logo_path']) }}"
                                     class="w-full h-full object-cover" alt="Logo">
                            @else
                                {{-- Default placeholder logo --}}
                                <div id="logo-preview-placeholder" class="w-full h-full bg-gradient-to-br from-brand-dark to-brand-accent flex items-center justify-center">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <img id="logo-preview" src="" class="w-full h-full object-cover hidden" alt="Logo">
                            @endif
                        </div>

                        <div class="flex-1">
                            <label for="app_logo"
                                class="inline-flex items-center gap-2 cursor-pointer px-4 py-2 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 dark:text-slate-200 text-sm font-semibold rounded-xl transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                </svg>
                                Pilih Gambar
                            </label>
                            <input type="file" id="app_logo" name="app_logo" accept="image/*" class="hidden"
                                onchange="previewLogo(this)">
                            <p class="mt-1.5 text-xs text-slate-400">JPG, PNG, GIF, SVG – maks 2 MB. Logo default akan digunakan jika kosong.</p>
                        </div>
                    </div>
                    @error('app_logo')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- Development Mode Toggle --}}
                <div class="flex items-center justify-between gap-4 py-1">
                    <div>
                        <p class="text-sm font-semibold text-slate-700 dark:text-slate-200">Mode Pengembangan</p>
                        <p class="text-xs text-slate-400 mt-0.5">Tampilkan banner "Development Mode" di sidebar.</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="dev_mode" value="1" class="sr-only peer"
                            {{ $settings['dev_mode'] === '1' ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-brand-accent rounded-full peer
                            peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute
                            after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full
                            after:h-5 after:w-5 after:transition-all peer-checked:bg-brand-dark"></div>
                    </label>
                </div>
            </div>
        </div>

        {{-- ─── MAP OPTIONS ──────────────────────────────────────── --}}
        <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden">
            <div class="bg-gradient-to-r from-brand-dark to-brand-accent px-6 py-4">
                <h2 class="text-sm font-bold text-white uppercase tracking-widest">Opsi Peta</h2>
            </div>

            <div class="p-6 space-y-6">

                {{-- Default Basemap --}}
                <div>
                    <label for="default_basemap" class="block text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-widest mb-2">Basemap Default</label>
                    <select id="default_basemap" name="default_basemap"
                        class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 text-sm text-slate-800 dark:text-white px-4 py-2.5 focus:border-brand-accent focus:ring-brand-accent transition">
                        <option value="osm"         {{ $settings['default_basemap'] === 'osm'         ? 'selected' : '' }}>OpenStreetMap</option>
                        <option value="satellite"   {{ $settings['default_basemap'] === 'satellite'   ? 'selected' : '' }}>Satellite (Esri)</option>
                        <option value="topographic" {{ $settings['default_basemap'] === 'topographic' ? 'selected' : '' }}>Topographic (Esri)</option>
                    </select>
                    @error('default_basemap')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- Layer Control Collapsed --}}
                <div class="flex items-center justify-between gap-4 py-1">
                    <div>
                        <p class="text-sm font-semibold text-slate-700 dark:text-slate-200">Layer Control – Collapsed by Default</p>
                        <p class="text-xs text-slate-400 mt-0.5">Jika aktif, panel layer akan tersembunyi saat peta pertama kali dibuka.</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="layer_control_collapsed" value="1" class="sr-only peer"
                            {{ $settings['layer_control_collapsed'] === '1' ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-brand-accent rounded-full peer
                            peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute
                            after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full
                            after:h-5 after:w-5 after:transition-all peer-checked:bg-brand-dark"></div>
                    </label>
                </div>

            </div>
        </div>

        {{-- ─── APPEARANCE ───────────────────────────────────────── --}}
        <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden">
            <div class="bg-gradient-to-r from-brand-dark to-brand-accent px-6 py-4">
                <h2 class="text-sm font-bold text-white uppercase tracking-widest">Tampilan</h2>
            </div>

            <div class="p-6">
                <div class="flex items-center justify-between gap-4 py-1">
                    <div>
                        <p class="text-sm font-semibold text-slate-700 dark:text-slate-200">Mode Gelap (Dark Mode)</p>
                        <p class="text-xs text-slate-400 mt-0.5">Aktifkan tema gelap untuk seluruh panel admin.</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="theme_toggle" name="theme_mode_toggle" class="sr-only peer"
                            {{ $settings['theme_mode'] === 'dark' ? 'checked' : '' }}
                            onchange="this.form.theme_mode.value = this.checked ? 'dark' : 'light'">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-brand-accent rounded-full peer
                            peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute
                            after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full
                            after:h-5 after:w-5 after:transition-all peer-checked:bg-brand-dark"></div>
                    </label>
                </div>
                {{-- Hidden actual value --}}
                <input type="hidden" name="theme_mode" value="{{ $settings['theme_mode'] }}">
            </div>
        </div>

        {{-- Submit --}}
        <div class="flex justify-end">
            <button type="submit"
                class="inline-flex items-center gap-2 px-6 py-3 bg-brand-dark hover:bg-brand-accent text-white text-sm font-bold rounded-2xl shadow-lg shadow-brand-dark/20 transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Simpan Pengaturan
            </button>
        </div>
    </form>
</div>

<script>
function previewLogo(input) {
    const file = input.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function(e) {
        const preview = document.getElementById('logo-preview');
        const placeholder = document.getElementById('logo-preview-placeholder');
        if (placeholder) placeholder.classList.add('hidden');
        preview.src = e.target.result;
        preview.classList.remove('hidden');
    };
    reader.readAsDataURL(file);
}
</script>
@endsection

