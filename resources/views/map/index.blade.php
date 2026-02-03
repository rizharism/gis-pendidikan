<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>GIS Pendidikan - Peta Sekolah</title>
    @vite(['resources/css/app.css'])
</head>

<body class="bg-slate-50">
    <div id="map-container">
        <div id="dock">
            <input type="text" id="search-input" list="school-suggestions" placeholder="Cari nama sekolah..."
                autocomplete="off" />

            <datalist id="school-suggestions">
                <!-- Options will be populated by JavaScript -->
            </datalist>

            <div class="h-6 w-px bg-slate-600/30 mx-1"></div>

            <a href="#" class="font-medium text-slate-200">Peta</a>
            <a href="#" class="font-medium text-slate-200">Informasi</a>

            @auth
                <a href="{{ route('admin.dashboard') }}" class="font-bold text-indigo-300">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="font-medium text-slate-200">Login</a>
            @endauth
        </div>

        <div id="map"></div>
    </div>
    @vite('resources/js/gis/initial-map.js')
    @vite('resources/js/map/map.js')
</body>

</html>
