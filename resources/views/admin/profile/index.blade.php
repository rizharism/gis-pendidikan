@extends('admin.layout.layout')

@section('content')
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold mb-4">User Profile</h2>

        <div class="flex items-center mb-6">
            <img src="https://ui-avatars.com/api/?name=Admin&background=4F46E5&color=fff" alt="Profile"
                class="w-16 h-16 rounded-full mr-4">
            <div>
                <h3 class="text-lg font-medium">Admin User</h3>
                <p class="text-gray-500">admin@example.com</p>
            </div>
        </div>

        <div class="border-t border-gray-200 pt-4">
            <h4 class="text-md font-medium mb-2">Account Information</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Name</label>
                    <p class="mt-1 text-sm text-gray-900">Admin User</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <p class="mt-1 text-sm text-gray-900">admin@example.com</p>
                </div>
            </div>
        </div>

        <div class="mt-6">
            <button class="bg-gray-800 hover:bg-gray-700 text-white px-4 py-2 rounded-md">
                Edit Profile
            </button>
        </div>
    </div>
@endsection
