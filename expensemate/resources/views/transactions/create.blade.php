@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-lg">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Add New Transaction</h1>
                <a href="{{ route('transactions.index') }}" class="text-gray-500 hover:text-gray-700">
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

            <form action="{{ route('transactions.store') }}" method="POST" id="transaction-form">
                @csrf

                <div class="mb-4">
                    <label class="block text-gray-700 mb-2 font-medium">Transaction Type</label>
                    <div class="flex space-x-4">
                        <label class="inline-flex items-center">
                            <input type="radio" name="type" value="income" 
                                class="form-radio text-green-500 h-4 w-4" 
                                {{ old('type', 'income') == 'income' ? 'checked' : '' }}>
                            <span class="ml-2 text-gray-700">Income</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="type" value="expense" 
                                class="form-radio text-red-500 h-4 w-4"
                                {{ old('type') == 'expense' ? 'checked' : '' }}>
                            <span class="ml-2 text-gray-700">Expense</span>
                        </label>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 mb-2 font-medium">Amount ($)</label>
                    <input type="number" name="amount" step="0.01" min="0" placeholder="0.00"
                        value="{{ old('amount') }}"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 mb-2 font-medium">Date</label>
                    <input type="date" name="date" value="{{ old('date', date('Y-m-d')) }}"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 mb-2 font-medium">Category</label>
                    <select id="category_id" name="category_id" class="w-full px-4 py-2 border rounded-lg" required>
                        <option value="">Select a category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>

                    <!-- Add New Category Button -->
                    <button type="button" id="show-add-category"
                        class="mt-2 text-sm text-blue-600 hover:underline">+ Add New Category</button>
                </div>

                <!-- Inline Add Category Form -->
                <div id="add-category-form" class="mb-6 bg-gray-50 p-4 rounded-lg border hidden">
                    <h3 class="font-semibold mb-2 text-gray-800">Add New Category</h3>
                    <input type="text" id="new-category-name" placeholder="Category name"
                        class="w-full mb-3 px-3 py-2 border rounded">
                    
                    <!-- Error message display -->
                    <div id="category-error" class="hidden bg-red-100 border border-red-400 text-red-700 px-3 py-2 rounded mb-3 text-sm"></div>

                    <div class="flex space-x-2">
                        <button type="button" id="submit-new-category"
                            class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded flex-1">Save Category</button>
                        <button type="button" id="cancel-add-category"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-700 py-2 px-4 rounded">Cancel</button>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 mb-2 font-medium">Note (Optional)</label>
                    <textarea name="note" rows="3" placeholder="Add a note about this transaction..."
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('note') }}</textarea>
                </div>

                <button type="submit"
                    class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-4 rounded-lg transition duration-200">
                    Add Transaction
                </button>
            </form>
        </div>
    </div>

    <!-- JavaScript for Category Management -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const showAddCategoryBtn = document.getElementById('show-add-category');
            const addCategoryForm = document.getElementById('add-category-form');
            const cancelAddCategoryBtn = document.getElementById('cancel-add-category');
            const submitNewCategoryBtn = document.getElementById('submit-new-category');
            const newCategoryNameInput = document.getElementById('new-category-name');
            const categorySelect = document.getElementById('category_id');
            const categoryError = document.getElementById('category-error');

            // Show add category form
            showAddCategoryBtn.addEventListener('click', function() {
                addCategoryForm.classList.remove('hidden');
                newCategoryNameInput.focus();
                hideError();
            });

            // Cancel add category
            cancelAddCategoryBtn.addEventListener('click', function() {
                addCategoryForm.classList.add('hidden');
                newCategoryNameInput.value = '';
                hideError();
            });

            // Hide error message
            function hideError() {
                categoryError.classList.add('hidden');
                categoryError.textContent = '';
            }

            // Show error message
            function showError(message) {
                categoryError.textContent = message;
                categoryError.classList.remove('hidden');
            }

            // Submit new category
            submitNewCategoryBtn.addEventListener('click', function() {
                const name = newCategoryNameInput.value.trim();
                
                if (!name) {
                    showError('Please enter a category name');
                    return;
                }

                // Disable button to prevent double clicks
                submitNewCategoryBtn.disabled = true;
                submitNewCategoryBtn.textContent = 'Saving...';
                hideError();

                fetch("{{ route('categories.store') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Accept": "application/json"
                    },
                    body: JSON.stringify({ name: name })
                })
                .then(response => {
                    // Don't throw error for 422 status, we want to handle validation errors
                    return response.json().then(data => {
                        return { status: response.status, data: data };
                    });
                })
                .then(result => {
                    if (result.status === 200 && result.data.success && result.data.category) {
                        // Success: Add new option to the dropdown
                        const newOption = document.createElement("option");
                        newOption.value = result.data.category.id;
                        newOption.text = result.data.category.name;
                        newOption.selected = true;
                        categorySelect.appendChild(newOption);

                        // Hide and reset form
                        addCategoryForm.classList.add('hidden');
                        newCategoryNameInput.value = '';
                        
                        // Show success message (optional)
                        showError('Category added successfully!');
                        categoryError.classList.remove('bg-red-100', 'border-red-400', 'text-red-700');
                        categoryError.classList.add('bg-green-100', 'border-green-400', 'text-green-700');
                        
                        // Hide success message after 3 seconds
                        setTimeout(() => {
                            hideError();
                            categoryError.classList.remove('bg-green-100', 'border-green-400', 'text-green-700');
                            categoryError.classList.add('bg-red-100', 'border-red-400', 'text-red-700');
                        }, 3000);
                        
                    } else if (result.status === 422) {
                        // Validation error (duplicate category)
                        showError(result.data.message || 'Category already exists!');
                    } else {
                        // Other errors
                        showError(result.data.message || 'Error creating category. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showError('Network error. Please try again.');
                })
                .finally(() => {
                    // Re-enable button
                    submitNewCategoryBtn.disabled = false;
                    submitNewCategoryBtn.textContent = 'Save Category';
                });
            });

            // Form validation before submit
            document.getElementById('transaction-form').addEventListener('submit', function(e) {
                const amount = document.querySelector('input[name="amount"]').value;
                const date = document.querySelector('input[name="date"]').value;
                const categoryId = document.querySelector('select[name="category_id"]').value;
                const type = document.querySelector('input[name="type"]:checked');

                if (!amount || !date || !categoryId || !type) {
                    e.preventDefault();
                    alert('Please fill in all required fields');
                    return false;
                }

                if (parseFloat(amount) <= 0) {
                    e.preventDefault();
                    alert('Amount must be greater than 0');
                    return false;
                }
            });
        });
    </script>
@endsection