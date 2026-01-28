<nav class="mt-8">
    <a href="{{ route('map.index') }}" class="block py-2 px-4  rounded">
        Peta Sebaran
    </a>
    <a href="{{ route('admin.dashboard') }}"
        class="block py-2 px-4 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-700' : 'hover:bg-gray-700' }} rounded">
        Dashboard
    </a>
    <a href="{{ route('admin.education-facility') }}"
        class="block py-2 px-4 {{ request()->routeIs('admin.education-facility') ? 'bg-gray-700' : 'hover:bg-gray-700' }} rounded">
        Fasilitas Pendidikan
    </a>
</nav>
