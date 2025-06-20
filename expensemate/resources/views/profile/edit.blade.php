@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h1 class="text-2xl font-bold text-gray-800">Profile Settings</h1>
                <p class="text-gray-600">Manage your account information and preferences</p>
            </div>

            <div class="p-6 space-y-8">
                <!-- Profile Information -->
                <div class="border-b border-gray-200 pb-8">
                    @include('profile.partials.update-profile-information-form')
                </div>

                <!-- Update Password -->
                <div class="border-b border-gray-200 pb-8">
                    @include('profile.partials.update-password-form')
                </div>

                <!-- Delete Account -->
                <div>
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
@endsection