@extends('admin.layout.layout')

@section('content')
    <div class="space-y-6" x-data="{
        // Opening Hours Modal
        showHoursModal: false,
        hours: {{ json_encode(old('opening_hours_data') ? json_decode(old('opening_hours_data'), true) : (isset($facility) && $facility->opening_hours ? $facility->opening_hours : [])) }},
        newDay: '',
        newOpen: '08:00',
        newClose: '16:00',
        dayOptions: [
            { value: 'Senin', label: 'Senin' },
            { value: 'Selasa', label: 'Selasa' },
            { value: 'Rabu', label: 'Rabu' },
            { value: 'Kamis', label: 'Kamis' },
            { value: 'Jumat', label: 'Jumat' },
            { value: 'Sabtu', label: 'Sabtu' },
            { value: 'Minggu', label: 'Minggu' },
        ],
        addHour() {
            if (!this.newDay || !this.newOpen || !this.newClose) return;
            this.hours.push({ day: this.newDay, open: this.newOpen, close: this.newClose });
            this.newDay = '';
            this.newOpen = '08:00';
            this.newClose = '16:00';
        },
        removeHour(index) {
            this.hours.splice(index, 1);
        },
    
        // Gallery Modal
        showGalleryModal: false,
        existingGallery: {{ json_encode(isset($facility) && $facility->gallery ? $facility->gallery : []) }},
        newGalleryFiles: [],
        newGalleryPreviews: [],
        removedGallery: [],
        addGalleryFiles(event) {
            const files = Array.from(event.target.files);
            files.forEach(file => {
                this.newGalleryFiles.push(file);
                this.newGalleryPreviews.push(URL.createObjectURL(file));
            });
            event.target.value = '';
        },
        removeNewGallery(index) {
            this.newGalleryFiles.splice(index, 1);
            this.newGalleryPreviews.splice(index, 1);
        },
        removeExistingGallery(path) {
            this.removedGallery.push(path);
            this.existingGallery = this.existingGallery.filter(p => p !== path);
        },
    
        // Video URL + Thumbnail
        videoUrl: '{{ old('video_url', $facility->video_url ?? '') }}',
        get videoThumbnail() {
            if (!this.videoUrl) return '';
            const ytMatch = this.videoUrl.match(/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/);
            if (ytMatch) return 'https://img.youtube.com/vi/' + ytMatch[1] + '/mqdefault.jpg';
            return '';
        },
    }">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">
                    {{ isset($facility) ? 'Edit Fasilitas Pendidikan' : 'Tambah Fasilitas Pendidikan' }}
                </h2>
                <p class="text-slate-500 dark:text-slate-400 text-sm font-medium mt-1">
                    {{ isset($facility) ? 'Perbarui informasi data sekolah yang sudah ada.' : 'Lengkapi formulir di bawah untuk menambahkan data baru.' }}
                </p>
            </div>
            <a href="{{ route('admin.education-facility') }}"
                class="text-sm font-bold text-slate-500 hover:text-slate-800 dark:hover:text-white transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M10 19l-7-7 7-7m8 14l-7-7 7-7" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
                Kembali
            </a>
        </div>

        @if (session('error'))
            <div
                class="p-4 bg-rose-50 dark:bg-rose-900/30 border border-rose-100 dark:border-rose-800 text-rose-700 dark:text-rose-300 rounded-2xl text-sm font-bold flex items-center gap-3 animate-fade-in-down mb-6">
                <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        <form
            action="{{ isset($facility) ? route('admin.education-facility.update', $facility->id) : route('admin.education-facility.store') }}"
            method="POST" enctype="multipart/form-data" x-ref="mainForm"
            @submit.prevent="
                const form = $refs.mainForm;
                const fd   = new FormData(form);

                // Remove the empty hidden galleryInput entry (if any) and replace with actual files
                fd.delete('gallery[]');
                newGalleryFiles.forEach(f => fd.append('gallery[]', f));

                // Ensure removed gallery paths are in FormData
                removedGallery.forEach(p => fd.append('remove_gallery[]', p));

                // Ensure opening_hours_data is up to date
                fd.set('opening_hours_data', JSON.stringify(hours));

                fetch(form.action, {
                    method: 'POST',
                    body: fd,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    redirect: 'follow',
                }).then(res => {
                    if (res.ok) {
                        return res.json().then(data => {
                            if (data.success && data.redirect_url) {
                                window.location.href = data.redirect_url;
                            }
                        });
                    } else if (res.status === 422) {
                        // Validation errors from Laravel
                        return res.json().then(data => {
                            const errors = data.errors || {};
                            const messages = Object.values(errors).flat().join('\n');
                            alert('Validasi gagal:\n' + messages);
                        });
                    } else {
                        return res.json().then(data => {
                            alert(data.message || 'Terjadi kesalahan pada server.');
                        }).catch(() => alert('Terjadi kesalahan pada server.'));
                    }
                }).catch(err => {
                    console.error('Upload error:', err);
                    alert('Terjadi kesalahan saat mengunggah data. Periksa konsol untuk detail.');
                });
              ">
            @csrf
            @if (isset($facility))
                @method('PUT')
            @endif

            <!-- Hidden fields for opening hours and removed gallery -->
            <input type="hidden" name="opening_hours_data" :value="JSON.stringify(hours)">
            <template x-for="(path, i) in removedGallery" :key="'rm-' + i">
                <input type="hidden" name="remove_gallery[]" :value="path">
            </template>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                <!-- Left Column: Input Fields -->
                <div class="lg:col-span-7 space-y-6 lg:overflow-y-auto lg:max-h-[calc(90vh-10rem)] pr-1">

                    {{-- Section 1: Informasi Dasar --}}
                    <div
                        class="bg-white dark:bg-slate-800 p-5 sm:p-8 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-sm space-y-5">
                        <h3
                            class="text-sm font-black text-slate-700 dark:text-white uppercase tracking-widest flex items-center gap-2">
                            <svg class="w-4 h-4 text-brand-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            Informasi Dasar
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="md:col-span-2">
                                <label for="name"
                                    class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">Nama
                                    Fasilitas</label>
                                <input type="text" id="name" name="name"
                                    value="{{ old('name', $facility->name ?? '') }}"
                                    class="w-full rounded-xl border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white dark:placeholder-slate-400 focus:border-brand-accent focus:ring-brand-accent text-sm py-2.5 px-4 bg-slate-50/50"
                                    placeholder="Masukkan nama sekolah..." required>
                                @error('name')
                                    <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="klas"
                                    class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">Jenjang
                                    Pendidikan</label>
                                <select id="klas" name="klas"
                                    class="w-full rounded-xl border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:border-brand-accent focus:ring-brand-accent text-sm py-2.5 px-4 bg-slate-50/50"
                                    required>
                                    <option value="">Pilih Jenjang</option>
                                    <option value="sd"
                                        {{ old('klas', $facility->klas ?? '') == 'sd' ? 'selected' : '' }}>SD</option>
                                    <option value="smp"
                                        {{ old('klas', $facility->klas ?? '') == 'smp' ? 'selected' : '' }}>SMP</option>
                                    <option value="sma"
                                        {{ old('klas', $facility->klas ?? '') == 'sma' ? 'selected' : '' }}>SMA/SMK
                                    </option>
                                    <option value="universitas"
                                        {{ old('klas', $facility->klas ?? '') == 'universitas' ? 'selected' : '' }}>
                                        Universitas</option>
                                </select>
                                @error('klas')
                                    <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="accreditation"
                                    class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">Akreditasi</label>
                                <select id="accreditation" name="accreditation"
                                    class="w-full rounded-xl border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:border-brand-accent focus:ring-brand-accent text-sm py-2.5 px-4 bg-slate-50/50">
                                    <option value="">Belum Diketahui</option>
                                    <option value="A"
                                        {{ old('accreditation', $facility->accreditation ?? '') == 'A' ? 'selected' : '' }}>
                                        A (Unggul)</option>
                                    <option value="B"
                                        {{ old('accreditation', $facility->accreditation ?? '') == 'B' ? 'selected' : '' }}>
                                        B (Baik)</option>
                                    <option value="C"
                                        {{ old('accreditation', $facility->accreditation ?? '') == 'C' ? 'selected' : '' }}>
                                        C (Cukup)</option>
                                    <option value="D"
                                        {{ old('accreditation', $facility->accreditation ?? '') == 'D' ? 'selected' : '' }}>
                                        D (Kurang)</option>
                                </select>
                                @error('accreditation')
                                    <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="school_code"
                                    class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">Kode
                                    NPSN</label>
                                <input type="text" id="school_code" name="school_code"
                                    value="{{ old('school_code', $facility->school_code ?? '') }}"
                                    class="w-full rounded-xl border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white dark:placeholder-slate-400 focus:border-brand-accent focus:ring-brand-accent text-sm py-2.5 px-4 bg-slate-50/50"
                                    placeholder="Contoh: 20501234">
                                @error('school_code')
                                    <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="principal_name"
                                    class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">Kepala
                                    Sekolah / Rektor</label>
                                <input type="text" id="principal_name" name="principal_name"
                                    value="{{ old('principal_name', $facility->principal_name ?? '') }}"
                                    class="w-full rounded-xl border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white dark:placeholder-slate-400 focus:border-brand-accent focus:ring-brand-accent text-sm py-2.5 px-4 bg-slate-50/50"
                                    placeholder="Nama kepala sekolah / Rektor...">
                                @error('principal_name')
                                    <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Section 2: Kontak --}}
                    <div
                        class="bg-white dark:bg-slate-800 p-5 sm:p-8 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-sm space-y-5">
                        <h3
                            class="text-sm font-black text-slate-700 dark:text-white uppercase tracking-widest flex items-center gap-2">
                            <svg class="w-4 h-4 text-brand-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            Kontak
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label for="phone"
                                    class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">Telepon</label>
                                <input type="text" id="phone" name="phone"
                                    value="{{ old('phone', $facility->phone ?? '') }}"
                                    class="w-full rounded-xl border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white dark:placeholder-slate-400 focus:border-brand-accent focus:ring-brand-accent text-sm py-2.5 px-4 bg-slate-50/50"
                                    placeholder="+62 812 3456 7890" pattern="^[\d\s\-\+\(\)]+$">
                                @error('phone')
                                    <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email"
                                    class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">Email</label>
                                <input type="email" id="email" name="email"
                                    value="{{ old('email', $facility->email ?? '') }}"
                                    class="w-full rounded-xl border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white dark:placeholder-slate-400 focus:border-brand-accent focus:ring-brand-accent text-sm py-2.5 px-4 bg-slate-50/50"
                                    placeholder="sekolah@email.com">
                                @error('email')
                                    <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="website"
                                    class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">Website</label>
                                <input type="url" id="website" name="website"
                                    value="{{ old('website', $facility->website ?? '') }}"
                                    class="w-full rounded-xl border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white dark:placeholder-slate-400 focus:border-brand-accent focus:ring-brand-accent text-sm py-2.5 px-4 bg-slate-50/50"
                                    placeholder="https://www.sekolah.sch.id">
                                @error('website')
                                    <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Section 3: Kapasitas --}}
                    <div
                        class="bg-white dark:bg-slate-800 p-5 sm:p-8 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-sm space-y-5">
                        <h3
                            class="text-sm font-black text-slate-700 dark:text-white uppercase tracking-widest flex items-center gap-2">
                            <svg class="w-4 h-4 text-brand-accent" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Kapasitas
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label for="student_capacity"
                                    class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">Jumlah
                                    Siswa / Mahasiswa</label>
                                <input type="number" id="student_capacity" name="student_capacity"
                                    value="{{ old('student_capacity', $facility->student_capacity ?? '') }}"
                                    class="w-full rounded-xl border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white dark:placeholder-slate-400 focus:border-brand-accent focus:ring-brand-accent text-sm py-2.5 px-4 bg-slate-50/50"
                                    placeholder="0" min="0">
                                @error('student_capacity')
                                    <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="teacher_count"
                                    class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">Jumlah
                                    Guru / Dosen</label>
                                <input type="number" id="teacher_count" name="teacher_count"
                                    value="{{ old('teacher_count', $facility->teacher_count ?? '') }}"
                                    class="w-full rounded-xl border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white dark:placeholder-slate-400 focus:border-brand-accent focus:ring-brand-accent text-sm py-2.5 px-4 bg-slate-50/50"
                                    placeholder="0" min="0">
                                @error('teacher_count')
                                    <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Section 4: Media --}}
                    <div
                        class="bg-white dark:bg-slate-800 p-5 sm:p-8 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-sm space-y-5">
                        <h3
                            class="text-sm font-black text-slate-700 dark:text-white uppercase tracking-widest flex items-center gap-2">
                            <svg class="w-4 h-4 text-brand-accent" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Media
                        </h3>

                        {{-- Gallery Thumbnail Strip --}}
                        <div>
                            <label
                                class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-3">Galeri
                                Foto</label>
                            <div class="flex flex-wrap gap-3 mb-3">
                                {{-- Existing gallery thumbnails --}}
                                <template x-for="(path, i) in existingGallery" :key="'eg-' + i">
                                    <div
                                        class="w-20 h-20 rounded-xl overflow-hidden border-2 border-slate-200 dark:border-slate-600 relative group">
                                        <img :src="'/storage/' + path" class="w-full h-full object-cover">
                                        <button type="button" @click="removeExistingGallery(path)"
                                            class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </template>

                                {{-- New gallery previews --}}
                                <template x-for="(url, i) in newGalleryPreviews" :key="'ng-' + i">
                                    <div
                                        class="w-20 h-20 rounded-xl overflow-hidden border-2 border-emerald-400 dark:border-emerald-500 relative group">
                                        <img :src="url" class="w-full h-full object-cover">
                                        <button type="button" @click="removeNewGallery(i)"
                                            class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                        <div
                                            class="absolute top-1 right-1 bg-emerald-500 text-white text-[8px] font-bold px-1 rounded">
                                            NEW</div>
                                    </div>
                                </template>

                                {{-- Add Photo Button --}}
                                <label
                                    class="w-20 h-20 rounded-xl border-2 border-dashed border-slate-300 dark:border-slate-600 hover:border-brand-accent dark:hover:border-brand-accent bg-slate-50/50 dark:bg-slate-700/50 flex flex-col items-center justify-center cursor-pointer transition-colors">
                                    <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    <span class="text-[9px] text-slate-400 font-bold mt-0.5">TAMBAH</span>
                                    <input type="file" class="hidden" accept="image/*" multiple
                                        @change="addGalleryFiles($event)">
                                </label>
                            </div>
                            {{-- Hidden file input used on form submit --}}
                            <input type="file" name="gallery[]" multiple class="hidden" x-ref="galleryInput">
                            @error('gallery')
                                <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            @error('gallery.*')
                                <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Video URL with Thumbnail --}}
                        <div>
                            <label for="video_url"
                                class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">Video
                                (URL YouTube)</label>
                            <input type="url" id="video_url" name="video_url" x-model="videoUrl"
                                class="w-full rounded-xl border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white dark:placeholder-slate-400 focus:border-brand-accent focus:ring-brand-accent text-sm py-2.5 px-4 bg-slate-50/50"
                                placeholder="https://www.youtube.com/watch?v=...">
                            @error('video_url')
                                <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                            @enderror

                            {{-- Video Thumbnail Preview --}}
                            <template x-if="videoThumbnail">
                                <div
                                    class="mt-3 relative rounded-xl overflow-hidden border border-slate-200 dark:border-slate-600 max-w-xs">
                                    <img :src="videoThumbnail" class="w-full h-auto">
                                    <a :href="videoUrl" target="_blank"
                                        class="absolute inset-0 flex items-center justify-center bg-black/30 hover:bg-black/40 transition-colors">
                                        <div
                                            class="w-12 h-12 bg-red-600 rounded-full flex items-center justify-center shadow-lg">
                                            <svg class="w-5 h-5 text-white ml-0.5" fill="currentColor"
                                                viewBox="0 0 24 24">
                                                <path d="M8 5v14l11-7z" />
                                            </svg>
                                        </div>
                                    </a>
                                </div>
                            </template>
                        </div>
                    </div>

                    {{-- Section 5: Jam Operasional --}}
                    <div
                        class="bg-white dark:bg-slate-800 p-5 sm:p-8 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-sm space-y-5">
                        <div class="flex items-center justify-between">
                            <h3
                                class="text-sm font-black text-slate-700 dark:text-white uppercase tracking-widest flex items-center gap-2">
                                <svg class="w-4 h-4 text-brand-accent" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Jam Operasional
                            </h3>
                            <button type="button" @click="showHoursModal = true"
                                class="text-xs font-bold text-brand-accent hover:text-brand-dark dark:hover:text-white bg-blue-50 dark:bg-brand-accent/20 hover:bg-blue-100 dark:hover:bg-brand-accent/30 px-3 py-1.5 rounded-lg transition-colors">
                                Atur Jadwal
                            </button>
                        </div>

                        {{-- Schedule Summary --}}
                        <div x-show="hours.length > 0" class="space-y-1.5">
                            <template x-for="(h, i) in hours" :key="'hs-' + i">
                                <div
                                    class="flex items-center justify-between py-2 px-3 rounded-lg bg-slate-50 dark:bg-slate-700/50 text-sm">
                                    <span class="font-semibold text-slate-700 dark:text-slate-200" x-text="h.day"></span>
                                    <span class="text-slate-500 dark:text-slate-400 font-mono text-xs"
                                        x-text="h.open + ' – ' + h.close"></span>
                                </div>
                            </template>
                        </div>
                        <p x-show="hours.length === 0" class="text-sm text-slate-400 dark:text-slate-500 italic">Belum ada
                            jadwal. Klik "Atur Jadwal" untuk menambahkan.</p>
                    </div>

                    {{-- Section 6: Deskripsi & Alamat --}}
                    <div
                        class="bg-white dark:bg-slate-800 p-5 sm:p-8 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-sm space-y-5">
                        <h3
                            class="text-sm font-black text-slate-700 dark:text-white uppercase tracking-widest flex items-center gap-2">
                            <svg class="w-4 h-4 text-brand-accent" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Deskripsi & Alamat
                        </h3>
                        <div class="space-y-5">
                            <div>
                                <label for="address"
                                    class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">Alamat
                                    Lengkap</label>
                                <textarea id="address" name="address" rows="2"
                                    class="w-full rounded-xl border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white dark:placeholder-slate-400 focus:border-brand-accent focus:ring-brand-accent text-sm py-2.5 px-4 bg-slate-50/50"
                                    placeholder="Jl. Contoh No. 123..." required>{{ old('address', $facility->address ?? '') }}</textarea>
                                @error('address')
                                    <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="description"
                                    class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">Deskripsi
                                    Singkat</label>
                                <textarea id="description" name="description" rows="4"
                                    class="w-full rounded-xl border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white dark:placeholder-slate-400 focus:border-brand-accent focus:ring-brand-accent text-sm py-2.5 px-4 bg-slate-50/50"
                                    required placeholder="Tuliskan deskripsi sekolah...">{{ old('description', $facility->description ?? '') }}</textarea>
                                @error('description')
                                    <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Map & Coordinates -->
                <div class="lg:col-span-5 space-y-6 sticky top-6">
                    <div
                        class="bg-white dark:bg-slate-800 p-8 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden flex flex-col h-full">
                        <label
                            class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-4">Titik
                            Lokasi (Map)</label>
                        <div id="map"
                            class="w-full h-[400px] rounded-2xl border border-slate-100 dark:border-slate-600 mb-5 z-0">
                        </div>

                        <div class="mb-6">
                            <label for="latlong"
                                class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">Koordinat
                                (Latitude, Longitude)</label>
                            <input type="text" id="latlong" name="latlong"
                                value="{{ old('latlong', isset($facility) ? $facility->latitude . ', ' . $facility->longitude : '') }}"
                                class="w-full rounded-xl border-slate-100 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs font-mono py-2.5 px-4"
                                readonly required placeholder="Klik pada peta untuk mengambil lokasi">
                            @error('latlong')
                                <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-auto flex gap-3">
                            <button type="submit"
                                class="flex-1 bg-brand-dark hover:bg-brand-accent text-white font-bold py-3 px-6 rounded-2xl transition-all shadow-lg shadow-brand-dark/10">
                                {{ isset($facility) ? 'Perbarui Data' : 'Simpan Data' }}
                            </button>
                            <button type="reset"
                                class="px-6 py-3 rounded-2xl border border-slate-200 dark:border-slate-600 text-slate-500 dark:text-slate-300 font-bold hover:bg-slate-50 dark:hover:bg-slate-700 transition-all">
                                Reset
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        {{-- ============================================ --}}
        {{-- OPENING HOURS MODAL --}}
        {{-- ============================================ --}}
        <div x-show="showHoursModal" x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            @click.self="showHoursModal = false">

            <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-2xl w-full max-w-lg mx-4 overflow-hidden"
                @click.stop>
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white">Atur Jam Operasional</h3>
                    <button type="button" @click="showHoursModal = false"
                        class="text-slate-400 hover:text-slate-600 dark:hover:text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="p-6 space-y-4 max-h-[60vh] overflow-y-auto">
                    {{-- Existing schedule rows --}}
                    <template x-for="(h, i) in hours" :key="'hm-' + i">
                        <div class="flex items-center gap-2 p-3 rounded-xl bg-slate-50 dark:bg-slate-700/50">
                            <span class="text-sm font-semibold text-slate-700 dark:text-slate-200 w-20"
                                x-text="h.day"></span>
                            <span class="text-xs text-slate-400 font-mono flex-1"
                                x-text="h.open + ' – ' + h.close"></span>
                            <button type="button" @click="removeHour(i)"
                                class="p-1 text-rose-400 hover:text-rose-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </template>

                    <p x-show="hours.length === 0" class="text-sm text-slate-400 italic text-center py-4">Belum ada
                        jadwal.</p>

                    {{-- Add New Row --}}
                    <div class="border-t border-slate-100 dark:border-slate-700 pt-4">
                        <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-3">
                            Tambah Hari</p>
                        <div class="flex items-end gap-2">
                            <div class="flex-1">
                                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Hari</label>
                                <select x-model="newDay"
                                    class="w-full rounded-lg border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white text-sm py-2 px-3">
                                    <option value="">Pilih Hari</option>
                                    <template x-for="d in dayOptions" :key="d.value">
                                        <option :value="d.value" x-text="d.label"></option>
                                    </template>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Buka</label>
                                <input type="time" x-model="newOpen"
                                    class="rounded-lg border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white text-sm py-2 px-3">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Tutup</label>
                                <input type="time" x-model="newClose"
                                    class="rounded-lg border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white text-sm py-2 px-3">
                            </div>
                            <button type="button" @click="addHour()"
                                class="bg-brand-dark hover:bg-brand-accent text-white px-3 py-2 rounded-lg transition-colors shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700 flex justify-end">
                    <button type="button" @click="showHoursModal = false"
                        class="bg-brand-dark hover:bg-brand-accent text-white font-bold py-2.5 px-6 rounded-xl transition-all text-sm">
                        Selesai
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@vite('resources/js/gis/initial-map.js')
@vite('resources/js/admin/form-education.js')
