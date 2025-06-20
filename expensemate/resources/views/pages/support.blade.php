
@extends('layouts.app')

@section('title', 'Support Center')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-6 py-16">
        <div class="text-center mb-16">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Support Center</h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Find answers to common questions and get help with ExpenseMate
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- FAQ -->
            <div class="bg-white rounded-xl shadow-sm p-8 border border-gray-100">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Frequently Asked Questions</h3>
                <p class="text-gray-600 mb-6">Find quick answers to the most common questions about ExpenseMate.</p>
                <a href="#" class="text-blue-600 hover:text-blue-800 font-medium">Browse FAQ →</a>
            </div>

            <!-- Contact Support -->
            <div class="bg-white rounded-xl shadow-sm p-8 border border-gray-100">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Contact Support</h3>
                <p class="text-gray-600 mb-6">Get in touch with our support team for personalized assistance.</p>
                <a href="{{ route('contact') }}" class="text-green-600 hover:text-green-800 font-medium">Contact Us →</a>
            </div>

            <!-- Documentation -->
            <div class="bg-white rounded-xl shadow-sm p-8 border border-gray-100">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Documentation</h3>
                <p class="text-gray-600 mb-6">Comprehensive guides and tutorials to help you get the most out of ExpenseMate.</p>
                <a href="{{ route('documentation') }}" class="text-purple-600 hover:text-purple-800 font-medium">Read Docs →</a>
            </div>
        </div>
    </div>
</div>
@endsection