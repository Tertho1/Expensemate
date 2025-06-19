@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-2xl">
        <div class="bg-white rounded-lg shadow">
            <!-- Header -->
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h1 class="text-2xl font-bold text-gray-800">Transaction Details</h1>
                    <div class="flex space-x-2">
                        <a href="{{ route('transactions.edit', $transaction) }}"
                            class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg>
                            Edit
                        </a>
                        <a href="{{ route('transactions.index') }}"
                            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                            Back to List
                        </a>
                    </div>
                </div>
            </div>

            <!-- Transaction Details -->
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Transaction Type</label>
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                {{ $transaction->type === 'income' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                @if($transaction->type === 'income')
                                    <path fill-rule="evenodd"
                                        d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd" />
                                @else
                                    <path fill-rule="evenodd"
                                        d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                @endif
                            </svg>
                            {{ ucfirst($transaction->type) }}
                        </span>
                    </div>

                    <!-- Amount -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Amount</label>
                        <div
                            class="text-3xl font-bold {{ $transaction->type === 'income' ? 'text-green-600' : 'text-red-600' }}">
                            {{ $transaction->type === 'income' ? '+' : '-' }}${{ number_format($transaction->amount, 2) }}
                        </div>
                    </div>

                    <!-- Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                        <div class="text-lg text-gray-900">{{ $transaction->date->format('F d, Y') }}</div>
                        <div class="text-sm text-gray-500">{{ $transaction->date->diffForHumans() }}</div>
                    </div>

                    <!-- Category -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                            {{ $transaction->category->name }}
                        </span>
                    </div>
                </div>

                <!-- Note Section (Only show if note exists) -->
                @if($transaction->note)
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Note</label>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-gray-800 whitespace-pre-wrap">{{ $transaction->note }}</p>
                        </div>
                    </div>
                @endif

                <!-- Metadata -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-500">
                        <div>
                            <span class="font-medium">Created:</span>
                            {{ $transaction->created_at->format('M d, Y \a\t g:i A') }}
                        </div>
                        @if($transaction->updated_at != $transaction->created_at)
                            <div>
                                <span class="font-medium">Last Updated:</span>
                                {{ $transaction->updated_at->format('M d, Y \a\t g:i A') }}
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-6 pt-6 border-t border-gray-200 flex justify-between">
                    <form action="{{ route('transactions.destroy', $transaction) }}" method="POST"
                        onsubmit="return confirm('Are you sure you want to delete this transaction? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                </path>
                            </svg>
                            Delete Transaction
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection