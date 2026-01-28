<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <title>GIS Pendidikan - Admin</title>
</head>

<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-gray-800 text-white">
            @include('admin.layout.sidebar')
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Navigation Bar -->
            <header class="bg-white shadow">
                <div class="flex items-center justify-between px-6 py-4">
                    <div>
                        <h1 class="text-2xl font-semibold text-gray-800">GIS Pendidikan</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <!-- Profile Button -->
                        <a href="{{ route('admin.profile') }}"
                            class="bg-gray-800 hover:bg-gray-700 text-white px-4 py-2 rounded-md">
                            Profile
                        </a>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6">
                @yield('content')
            </main>
        </div>
    </div>
</body>

</html>
