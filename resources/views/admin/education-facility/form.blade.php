@extends('admin.layout.layout')

@section('content')
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold mb-4">Tambah Data Fasilitas Pendidikan</h2>
        <hr class="my-5">
        <form action="{{ route('admin.education-facility.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="flex space-x-4">
                <!-- Left Column for Input Fields -->
                <div class="w-1/2">
                    <div class="mb-4">
                        <label for="name" class="block text-md font-medium mb-1 text-gray-700">Nama Fasilitas</label>
                        <input type="text" id="name" name="name"
                            class="p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-full"
                            required>
                    </div>
                    <div class="flex space-x-4">
                        <div class="w-1/2">
                            <div class="mb-4">
                                <label for="klas" class="block text-md font-medium mb-1 text-gray-700">Jenis
                                    Fasilitas</label>
                                <select id="klas" name="klas"
                                    class="p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-full"
                                    required>
                                    <option value="">Pilih Jenis Fasilitas</option>
                                    <option value="universitas">Formal</option>
                                    <option value="universitas">Non Formal</option>
                                </select>
                            </div>
                        </div>
                        <div class="w-1/2">
                            <div class="mb-4">
                                <label for="klas" class="block text-md font-medium mb-1 text-gray-700">-</label>
                                <select id="klas" name="klas"
                                    class="p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-full"
                                    required>
                                    <option value="">Pilih Kelas</option>
                                    <option value="universitas">Universitas</option>
                                    <option value="sma">SMA</option>
                                    <option value="smp">SMP</option>
                                    <option value="sd">SD</option>
                                    <option value="tk">TK</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="address" class="block text-md font-medium mb-1 text-gray-700">Alamat</label>
                        <textarea id="address" name="address" rows="3"
                            class="p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-full"
                            required></textarea>
                    </div>

                    <div class="mb-4">
                        <label for="image" class="block text-md font-medium mb-1 text-gray-700">Gambar/Foto</label>
                        <input type="file" id="image" name="image"
                            class="p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-full"
                            accept="image/*">
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block text-md font-medium mb-1 text-gray-700">Deskripsi</label>
                        <textarea id="description" name="description" rows="6"
                            class="p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-full"
                            required></textarea>
                    </div>

                    <div class="mb-4">
                        <label for="latlong" class="block text-md font-medium mb-1 text-gray-700">Latitude &
                            Longitude</label>
                        <input type="text" id="latlong" name="latlong"
                            class="p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-full"
                            readonly required>
                    </div>
                </div>

                <!-- Right Column for Map -->
                <div class="w-1/2">
                    <div id="map" style="height: 600px; width: 100%;"></div>
                </div>
            </div>
        </form>
    </div>
@endsection

@vite('resources/js/gis/initial-map.js')
@vite('resources/js/admin/form-education')
