@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h1 class="text-2xl font-bold text-gray-800">Settings</h1>
                <p class="text-gray-600">Manage your account preferences and application settings</p>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Quick Actions -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-800">Quick Actions</h3>

                        <a href="{{ route('profile.edit') }}"
                            class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                            <svg class="w-6 h-6 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <div>
                                <p class="font-medium text-gray-900">Edit Profile</p>
                                <p class="text-sm text-gray-500">Update your personal information</p>
                            </div>
                        </a>

                        <a href="{{ route('categories.index') }}"
                            class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                            <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                </path>
                            </svg>
                            <div>
                                <p class="font-medium text-gray-900">Manage Categories</p>
                                <p class="text-sm text-gray-500">Add, edit, or delete transaction categories</p>
                            </div>
                        </a>

                        <a href="{{ route('export') }}"
                            class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                            <svg class="w-6 h-6 text-purple-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            <div>
                                <p class="font-medium text-gray-900">Export Data</p>
                                <p class="text-sm text-gray-500">Download your transaction data</p>
                            </div>
                        </a>
                    </div>

                    <!-- Account Information -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-800">Account Information</h3>

                        <div class="p-4 bg-gray-50 rounded-lg">
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Name:</span>
                                    <span class="font-medium">{{ Auth::user()->name }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Email:</span>
                                    <span class="font-medium">{{ Auth::user()->email }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Member since:</span>
                                    <span class="font-medium">{{ Auth::user()->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="p-4 border border-yellow-200 bg-yellow-50 rounded-lg">
                            <h4 class="font-medium text-yellow-800 mb-2">Application Information</h4>
                            <div class="text-sm text-yellow-700 space-y-1">
                                <p>• Version: 1.0.0</p>
                                <p>• Database: {{ config('database.default') }}</p>
                                <p>• Environment: {{ config('app.env') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection