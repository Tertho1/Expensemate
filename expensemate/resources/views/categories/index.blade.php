@extends('layouts.app')
@section('title', 'Categories')
@section('content')
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h1 class="text-2xl font-bold text-gray-800">Manage Categories</h1>
                    <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 mx-6 mt-4 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 mx-6 mt-4 rounded">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Add New Category Form -->
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold mb-4">Add New Category</h3>
                <form action="{{ route('categories.store') }}" method="POST" class="flex gap-4">
                    @csrf
                    <input type="text" name="name" placeholder="Category name"
                        class="flex-1 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                        value="{{ old('name') }}" required>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition">
                        Add Category
                    </button>
                </form>
            </div>

            <!-- Categories List -->
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4">Your Categories</h3>

                @if($categories->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($categories as $category)
                            <div class="border rounded-lg p-4 flex justify-between items-center hover:bg-gray-50">
                                <div>
                                    <h4 class="font-medium text-gray-900">{{ $category->name }}</h4>
                                    <p class="text-sm text-gray-500">
                                        @if($category->user_id === null)
                                            <span class="text-blue-600">Global Category</span>
                                        @else
                                            <span class="text-green-600">Your Category</span>
                                        @endif
                                    </p>
                                    <p class="text-xs text-gray-400">
                                        {{ $category->transactions->count() }} transaction(s)
                                    </p>
                                </div>

                                @if($category->user_id === Auth::id())
                                    <form action="{{ route('categories.destroy', $category) }}" method="POST"
                                        onsubmit="return confirm('Are you sure? This will permanently delete this category.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 p-2 rounded transition"
                                            title="Delete category">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </form>
                                @else
                                    <span class="text-gray-400 text-sm">Protected</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-gray-500">No categories found.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection