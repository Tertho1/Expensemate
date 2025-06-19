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

            <form action="{{ route('transactions.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="block text-gray-700 mb-2 font-medium">Transaction Type</label>
                    <div class="flex space-x-4">
                        <label class="inline-flex items-center">
                            <input type="radio" name="type" value="income" checked
                                class="form-radio text-green-500 h-4 w-4">
                            <span class="ml-2 text-gray-700">Income</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="type" value="expense" class="form-radio text-red-500 h-4 w-4">
                            <span class="ml-2 text-gray-700">Expense</span>
                        </label>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 mb-2 font-medium">Amount ($)</label>
                    <input type="number" name="amount" step="0.01" min="0" placeholder="0.00"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 mb-2 font-medium">Date</label>
                    <input type="date" name="date"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        required>
                </div>

                <!-- CATEGORY SELECT -->
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2 font-medium">Category</label>
                    <select id="category_id" name="category_id" class="w-full px-4 py-2 border rounded-lg" required>
                        <option value="">Select a category</option>

                        <optgroup label="Income Categories">
                            @foreach($categories['income'] ?? [] as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </optgroup>

                        <optgroup label="Expense Categories">
                            @foreach($categories['expense'] ?? [] as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </optgroup>
                    </select>

                    <!-- Add New Category Button -->
                    <button type="button" id="show-add-category"
                        class="mt-2 text-sm text-blue-600 hover:underline">+ Add New Category</button>
                </div>

                <!-- Inline Add Category Form -->
                <div id="add-category-form" class="mb-6 bg-gray-50 p-4 rounded-lg border" style="display: none;">
                    <h3 class="font-semibold mb-2 text-gray-800">Add Category</h3>
                    <input type="text" id="new-category-name" placeholder="Category name"
                        class="w-full mb-2 px-3 py-2 border rounded" required>

                    <select id="new-category-type" class="w-full mb-3 px-3 py-2 border rounded">
                        <option value="expense">Expense</option>
                        <option value="income">Income</option>
                    </select>

                    <button type="button" id="submit-new-category"
                        class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded">Save Category</button>
                </div>

                <button type="submit"
                    class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-4 rounded-lg transition duration-200">
                    Add Transaction
                </button>
            </form>
        </div>
    </div>

    <!-- JavaScript for Category AJAX -->
    <script>
        document.getElementById('show-add-category').addEventListener('click', () => {
            const form = document.getElementById('add-category-form');
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        });

        document.getElementById('submit-new-category').addEventListener('click', () => {
            const name = document.getElementById('new-category-name').value;
            const type = document.getElementById('new-category-type').value;

            fetch("{{ route('categories.store') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ name, type })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success && data.category) {
                    // Add new option to the dropdown
                    const categorySelect = document.getElementById('category_id');
                    let group = [...categorySelect.children].find(
                        optgroup => optgroup.label.toLowerCase().includes(data.category.type)
                    );

                    const newOption = document.createElement("option");
                    newOption.value = data.category.id;
                    newOption.text = data.category.name;
                    newOption.selected = true;

                    if (group) {
                        group.appendChild(newOption);
                    } else {
                        const newGroup = document.createElement("optgroup");
                        newGroup.label = data.category.type.charAt(0).toUpperCase() + data.category.type.slice(1) + " Categories";
                        newGroup.appendChild(newOption);
                        categorySelect.appendChild(newGroup);
                    }

                    // Hide and reset form
                    document.getElementById('add-category-form').style.display = 'none';
                    document.getElementById('new-category-name').value = '';
                }
            });
        });
    </script>
@endsection
