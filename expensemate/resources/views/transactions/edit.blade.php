@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-lg">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Edit Transaction</h1>
            <a href="{{ route('transactions.index') }}" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </a>
        </div>

        <form action="{{ route('transactions.update', $transaction) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label class="block text-gray-700 mb-2 font-medium">Transaction Type</label>
                <div class="flex space-x-4">
                    <label class="inline-flex items-center">
                        <input type="radio" name="type" value="income" 
                            {{ $transaction->type === 'income' ? 'checked' : '' }}
                            class="form-radio text-green-500 h-4 w-4">
                        <span class="ml-2 text-gray-700">Income</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="type" value="expense"
                            {{ $transaction->type === 'expense' ? 'checked' : '' }}
                            class="form-radio text-red-500 h-4 w-4">
                        <span class="ml-2 text-gray-700">Expense</span>
                    </label>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 mb-2 font-medium">Amount ($)</label>
                <input type="number" name="amount" step="0.01" min="0" 
                    value="{{ old('amount', $transaction->amount) }}"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 mb-2 font-medium">Date</label>
                <input type="date" name="date" 
                    value="{{ old('date', $transaction->date->format('Y-m-d')) }}"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    required>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 mb-2 font-medium">Category</label>
                <select name="category_id" 
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" 
                            {{ $transaction->category_id == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" 
                class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-4 rounded-lg transition duration-200">
                Update Transaction
            </button>
        </form>
    </div>
</div>
@endsection