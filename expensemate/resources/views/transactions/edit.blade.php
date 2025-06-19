@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-lg">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Edit Transaction</h1>
                <a href="{{ route('transactions.index', $transaction) }}" class="text-gray-500 hover:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </a>
            </div>

            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('transactions.update', $transaction) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block text-gray-700 mb-2 font-medium">Transaction Type</label>
                    <div class="flex space-x-4">
                        <label class="inline-flex items-center">
                            <input type="radio" name="type" value="income" 
                                class="form-radio text-green-500 h-4 w-4" 
                                {{ old('type', $transaction->type) == 'income' ? 'checked' : '' }}>
                            <span class="ml-2 text-gray-700">Income</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="type" value="expense" 
                                class="form-radio text-red-500 h-4 w-4"
                                {{ old('type', $transaction->type) == 'expense' ? 'checked' : '' }}>
                            <span class="ml-2 text-gray-700">Expense</span>
                        </label>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 mb-2 font-medium">Amount ($)</label>
                    <input type="number" name="amount" step="0.01" min="0" placeholder="0.00"
                        value="{{ old('amount', $transaction->amount) }}"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 mb-2 font-medium">Date</label>
                    <input type="date" name="date" value="{{ old('date', $transaction->date->format('Y-m-d')) }}"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 mb-2 font-medium">Category</label>
                    <select name="category_id" class="w-full px-4 py-2 border rounded-lg" required>
                        <option value="">Select a category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" 
                                {{ old('category_id', $transaction->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 mb-2 font-medium">Note (Optional)</label>
                    <textarea name="note" rows="3" placeholder="Add a note about this transaction..."
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('note', $transaction->note) }}</textarea>
                </div>

                <div class="flex space-x-4">
                    <button type="submit"
                        class="flex-1 bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-4 rounded-lg transition duration-200">
                        Update Transaction
                    </button>
                    <a href="{{ route('transactions.index', $transaction) }}"
                        class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-4 rounded-lg transition duration-200 text-center">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection