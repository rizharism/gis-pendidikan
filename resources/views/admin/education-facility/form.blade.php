@extends('admin.layout.layout')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Tambah Fasilitas Pendidikan</h2>
                <p class="text-slate-500 text-sm font-medium mt-1">Lengkapi formulir di bawah untuk menambahkan data baru.</p>
            </div>
            <a href="{{ route('admin.education-facility') }}" class="text-sm font-bold text-slate-500 hover:text-slate-800 transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 19l-7-7 7-7m8 14l-7-7 7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Kembali
            </a>
        </div>

        <form action="{{ route('admin.education-facility.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- Left Column: Input Fields -->
                <div class="lg:col-span-7 space-y-6">
                    <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm space-y-5">
                        <div class="grid grid-cols-1 gap-5">
                            <div>
                                <label for="name" class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">Nama Fasilitas</label>
                                <input type="text" id="name" name="name"
                                    class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-4 bg-slate-50/50"
                                    placeholder="Masukkan nama sekolah..." required>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label for="klas_type" class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">Jenis Fasilitas</label>
                                    <select id="klas_type" name="klas_type"
                                        class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-4 bg-slate-50/50"
                                        required>
                                        <option value="">Pilih Jenis</option>
                                        <option value="formal">Formal</option>
                                        <option value="non-formal">Non Formal</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="klas" class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">Jenjang</label>
                                    <select id="klas" name="klas"
                                        class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-4 bg-slate-50/50"
                                        required>
                                        <option value="">Pilih Jenjang</option>
                                        <option value="universitas">Universitas</option>
                                        <option value="sma">SMA/SMK</option>
                                        <option value="smp">SMP</option>
                                        <option value="sd">SD</option>
                                        <option value="tk">TK</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label for="address" class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">Alamat Lengkap</label>
                                <textarea id="address" name="address" rows="2"
                                    class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-4 bg-slate-50/50"
                                    placeholder="Jl. Contoh No. 123..." required></textarea>
                            </div>

                            <div x-data="{ 
                                imageUrl: null,
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
                                                <p class="text-white text-xs font-bold">Klik untuk ganti foto</p>
                                            </div>
                                        </div>
                                    </template>

                                    <!-- Placeholder / Upload Box -->
                                    <div class="space-y-1 text-center py-5" x-show="!imageUrl">
                                        <svg class="mx-auto h-10 w-10 text-slate-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex text-sm text-slate-600 justify-center">
                                            <label for="image" class="relative cursor-pointer bg-transparent rounded-md font-bold text-indigo-600 hover:text-indigo-500 focus-within:outline-none">
                                                <span>Upload a file</span>
                                                <input id="image" name="image" type="file" class="sr-only" accept="image/*" @change="fileChosen">
                                            </label>
                                        </div>
                                        <p class="text-xs text-slate-500 italic">PNG, JPG up to 2MB</p>
                                    </div>
                                    
                                    <!-- Hidden Input trigger when preview is shown -->
                                    <input id="image" x-show="imageUrl" name="image" type="file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/*" @change="fileChosen">
                                </div>
                            </div>

                            <div>
                                <label for="description" class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">Deskripsi Singkat</label>
                                <textarea id="description" name="description" rows="4"
                                    class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-4 bg-slate-50/50"
                                    required placeholder="Tuliskan deskripsi sekolah..."></textarea>
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
                            <input type="text" id="latlong" name="latlong"
                                class="w-full rounded-xl border-slate-100 bg-slate-50 text-slate-500 text-xs font-mono py-2.5 px-4"
                                readonly required placeholder="Klik pada peta untuk mengambil lokasi">
                        </div>

                        <div class="mt-auto flex gap-3">
                            <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-2xl transition-all shadow-lg shadow-indigo-100">
                                Simpan Data
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
