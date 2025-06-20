
@extends('layouts.app')

@section('title', 'Help Center')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-6 py-16">
        <div class="text-center mb-16">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Help Center</h1>
            <p class="text-xl text-gray-600">Everything you need to know about ExpenseMate</p>
        </div>

        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-xl shadow-sm p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-8">Common Questions</h2>
                
                <div class="space-y-6">
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">How do I add a new transaction?</h3>
                        <p class="text-gray-600">Navigate to the Transactions page and click the "Add Transaction" button. Fill in the required details including amount, category, date, and optional notes.</p>
                    </div>

                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Can I create custom categories?</h3>
                        <p class="text-gray-600">Yes! Go to the Categories page where you can add, edit, or delete categories to match your specific needs.</p>
                    </div>

                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">How do I export my data?</h3>
                        <p class="text-gray-600">Visit the Export page where you can download your transaction data in CSV, Excel, or PDF formats with custom date ranges.</p>
                    </div>

                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Is my financial data secure?</h3>
                        <p class="text-gray-600">Absolutely! We use bank-level encryption and security measures to protect your financial information. Your data is never shared with third parties.</p>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Can I access ExpenseMate on mobile devices?</h3>
                        <p class="text-gray-600">Yes! ExpenseMate is fully responsive and works seamlessly on all devices including smartphones and tablets.</p>
                    </div>
                </div>

                <div class="mt-12 text-center">
                    <p class="text-gray-600 mb-4">Still need help?</p>
                    <a href="{{ route('contact') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition duration-300">
                        Contact Support
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection