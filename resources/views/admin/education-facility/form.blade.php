@extends('admin.layout.layout')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight">
                    {{ isset($facility) ? 'Edit Fasilitas Pendidikan' : 'Tambah Fasilitas Pendidikan' }}
                </h2>
                <p class="text-slate-500 text-sm font-medium mt-1">
                    {{ isset($facility) ? 'Perbarui informasi data sekolah yang sudah ada.' : 'Lengkapi formulir di bawah untuk menambahkan data baru.' }}
                </p>
            </div>
            <a href="{{ route('admin.education-facility') }}" class="text-sm font-bold text-slate-500 hover:text-slate-800 transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 19l-7-7 7-7m8 14l-7-7 7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Kembali
            </a>
        </div>

        @if (session('error'))
            <div class="p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-2xl text-sm font-bold flex items-center gap-3 animate-fade-in-down mb-6">
                <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ isset($facility) ? route('admin.education-facility.update', $facility->id) : route('admin.education-facility.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($facility))
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- Left Column: Input Fields -->
                <div class="lg:col-span-7 space-y-6">
                    <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm space-y-5">
                        <div class="grid grid-cols-1 gap-5">
                            <div>
                                <label for="name" class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">Nama Fasilitas</label>
                                <input type="text" id="name" name="name" value="{{ old('name', $facility->name ?? '') }}"
                                    class="w-full rounded-xl border-slate-200 focus:border-brand-accent focus:ring-brand-accent text-sm py-2.5 px-4 bg-slate-50/50"
                                    placeholder="Masukkan nama sekolah..." required>
                                @error('name') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="klas" class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">Jenjang Pendidikan</label>
                                <select id="klas" name="klas"
                                    class="w-full rounded-xl border-slate-200 focus:border-brand-accent focus:ring-brand-accent text-sm py-2.5 px-4 bg-slate-50/50"
                                    required>
                                    <option value="">Pilih Jenjang</option>
                                    <option value="sd" {{ old('klas', $facility->klas ?? '') == 'sd' ? 'selected' : '' }}>SD</option>
                                    <option value="smp" {{ old('klas', $facility->klas ?? '') == 'smp' ? 'selected' : '' }}>SMP</option>
                                    <option value="sma" {{ old('klas', $facility->klas ?? '') == 'sma' ? 'selected' : '' }}>SMA/SMK</option>
                                    <option value="universitas" {{ old('klas', $facility->klas ?? '') == 'universitas' ? 'selected' : '' }}>Universitas</option>
                                </select>
                                @error('klas') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="address" class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">Alamat Lengkap</label>
                                <textarea id="address" name="address" rows="2"
                                    class="w-full rounded-xl border-slate-200 focus:border-brand-accent focus:ring-brand-accent text-sm py-2.5 px-4 bg-slate-50/50"
                                    placeholder="Jl. Contoh No. 123..." required>{{ old('address', $facility->address ?? '') }}</textarea>
                                @error('address') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div x-data="{ 
                                imageUrl: '{{ isset($facility) && $facility->image ? Storage::disk('public')->url($facility->image) : asset('assets/images/default.png') }}',
                                fileChosen(event) {
                                    const file = event.target.files[0];
                                    if (file) {
                                        this.imageUrl = URL.createObjectURL(file);
                                    }
                                }
                            }">
                                <label for="image" class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">Foto Fasilitas</label>
                                <div class="mt-1 flex flex-col items-center justify-center border-2 border-slate-200 border-dashed rounded-2xl bg-slate-50/50 hover:bg-slate-50 transition-colors overflow-hidden relative group min-h-[160px]">
                                    <!-- Image Preview -->
                                    <template x-if="imageUrl">
                                        <div class="absolute inset-0 w-full h-full">
                                            <img :src="imageUrl" class="w-full h-full object-cover">
                                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                                <p class="text-white text-xs font-bold font-mono">KLIK UNTUK MENGGANTI FOTO</p>
                                            </div>
                                        </div>
                                    </template>

                                    <!-- Placeholder / Upload Box -->
                                    <div class="space-y-1 text-center py-5" x-show="!imageUrl">
                                        <svg class="mx-auto h-10 w-10 text-slate-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex text-sm text-slate-600 justify-center">
                                            <span class="relative cursor-pointer bg-transparent rounded-md font-bold text-brand-accent hover:text-brand-dark">
                                                <span>Pilih file foto</span>
                                            </span>
                                        </div>
                                        <p class="text-[10px] text-slate-500 italic uppercase font-black">PNG, JPG UP TO 2MB</p>
                                    </div>
                                    
                                    <!-- Single File Input -->
                                    <input id="image" name="image" type="file" 
                                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" 
                                           accept="image/*" @change="fileChosen">
                                </div>
                                @error('image') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="description" class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">Deskripsi Singkat</label>
                                <textarea id="description" name="description" rows="4"
                                    class="w-full rounded-xl border-slate-200 focus:border-brand-accent focus:ring-brand-accent text-sm py-2.5 px-4 bg-slate-50/50"
                                    required placeholder="Tuliskan deskripsi sekolah...">{{ old('description', $facility->description ?? '') }}</textarea>
                                @error('description') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Map & Coordinates -->
                <div class="lg:col-span-5 space-y-6">
                    <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm overflow-hidden flex flex-col h-full">
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-4">Titik Lokasi (Map)</label>
                        <div id="map" class="w-full h-[400px] rounded-2xl border border-slate-100 mb-5 z-0"></div>
                        
                        <div class="mb-6">
                            <label for="latlong" class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">Koordinat (Latitude, Longitude)</label>
                            <input type="text" id="latlong" name="latlong" value="{{ old('latlong', isset($facility) ? $facility->latitude . ', ' . $facility->longitude : '') }}"
                                class="w-full rounded-xl border-slate-100 bg-slate-50 text-slate-700 text-xs font-mono py-2.5 px-4"
                                readonly required placeholder="Klik pada peta untuk mengambil lokasi">
                            @error('latlong') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="mt-auto flex gap-3">
                            <button type="submit" class="flex-1 bg-brand-dark hover:bg-brand-accent text-white font-bold py-3 px-6 rounded-2xl transition-all shadow-lg shadow-brand-dark/10">
                                {{ isset($facility) ? 'Perbarui Data' : 'Simpan Data' }}
                            </button>
                            <button type="reset" class="px-6 py-3 rounded-2xl border border-slate-200 text-slate-500 font-bold hover:bg-slate-50 transition-all">
                                Reset
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@vite('resources/js/gis/initial-map.js')
@vite('resources/js/admin/form-education')

