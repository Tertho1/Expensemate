@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-lg">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Transaction Details</h1>
            <a href="{{ route('transactions.index') }}" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </a>
        </div>

        <div class="space-y-4">
            <div class="flex justify-between items-center pb-4 border-b">
                <span class="text-gray-600 font-medium">Type:</span>
                <span class="px-3 py-1 rounded-full text-sm font-medium 
                    {{ $transaction->type === 'income' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ ucfirst($transaction->type) }}
                </span>
            </div>

            <div class="flex justify-between items-center pb-4 border-b">
                <span class="text-gray-600 font-medium">Amount:</span>
                <span class="text-xl font-bold {{ $transaction->type === 'income' ? 'text-green-600' : 'text-red-600' }}">
                    {{ $transaction->type === 'income' ? '+' : '-' }}${{ number_format($transaction->amount, 2) }}
                </span>
            </div>

            <div class="flex justify-between items-center pb-4 border-b">
                <span class="text-gray-600 font-medium">Date:</span>
                <span class="text-gray-800">{{ $transaction->date->format('F j, Y') }}</span>
            </div>

            <div class="flex justify-between items-center pb-4 border-b">
                <span class="text-gray-600 font-medium">Category:</span>
                <span class="text-gray-800">{{ $transaction->category->name }}</span>
            </div>

            <div class="flex justify-between items-center pb-4 border-b">
                <span class="text-gray-600 font-medium">Created:</span>
                <span class="text-gray-800">{{ $transaction->created_at->format('M j, Y \a\t g:i a') }}</span>
            </div>

            <div class="flex justify-between items-center pb-4">
                <span class="text-gray-600 font-medium">Last Updated:</span>
                <span class="text-gray-800">{{ $transaction->updated_at->format('M j, Y \a\t g:i a') }}</span>
            </div>
        </div>

        <div class="flex justify-end space-x-3 mt-8">
            <a href="{{ route('transactions.edit', $transaction) }}" 
                class="flex items-center px-4 py-2 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                </svg>
                Edit
            </a>
            <form action="{{ route('transactions.destroy', $transaction) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" 
                    class="flex items-center px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600"
                    onclick="return confirm('Are you sure you want to delete this transaction?')">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    Delete
                </button>
            </form>
        </div>
    </div>
</div>
@endsection