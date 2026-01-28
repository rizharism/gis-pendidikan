@extends('admin.layout.layout')

@section('content')
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold mb-4">Dashboard</h2>
        <p>Summary Data Fasilitas Pendidikan di Kota Blitar</p>

        <!-- Dashboard Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
            <div class="bg-blue-50 p-4 rounded-lg">
                <h3 class="text-lg font-medium text-blue-800">Total Fasilitas Pendidikan</h3>
                <p class="text-3xl font-bold text-blue-600">42</p>
            </div>
            <div class="bg-green-50 p-4 rounded-lg">
                <h3 class="text-lg font-medium text-green-800">SMA/SMK/MA</h3>
                <p class="text-3xl font-bold text-green-600">38</p>
            </div>
            <div class="bg-purple-50 p-4 rounded-lg">
                <h3 class="text-lg font-medium text-purple-800">SMP/MTS</h3>
                <p class="text-3xl font-bold text-purple-600">5</p>
            </div>
            <div class="bg-orange-50 p-4 rounded-lg">
                <h3 class="text-lg font-medium text-purple-800">SD/MI</h3>
                <p class="text-3xl font-bold text-purple-600">5</p>
            </div>
            <div class="bg-red-50 p-4 rounded-lg">
                <h3 class="text-lg font-medium text-purple-800">TK/RA/PAUD</h3>
                <p class="text-3xl font-bold text-purple-600">5</p>
            </div>
        </div>
    </div>
@endsection
