<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Monthly Income Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">Monthly Income</h3>
                        <p class="text-3xl font-bold text-green-600">${{ number_format($monthlyIncome, 2) }}</p>
                        <p class="text-sm text-gray-500 mt-2">
                            @if($incomeChange > 0)
                                <span class="text-green-500">↑ {{ number_format(abs($incomeChange), 1) }}%</span> from last
                                month
                            @elseif($incomeChange < 0)
                                <span class="text-red-500">↓ {{ number_format(abs($incomeChange), 1) }}%</span> from last
                                month
                            @else
                                No change from last month
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Monthly Expenses Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">Monthly Expenses</h3>
                        <p class="text-3xl font-bold text-red-600">${{ number_format($monthlyExpense, 2) }}</p>
                        <p class="text-sm text-gray-500 mt-2">
                            @if($expenseChange > 0)
                                <span class="text-red-500">↑ {{ number_format(abs($expenseChange), 1) }}%</span> from last
                                month
                            @elseif($expenseChange < 0)
                                <span class="text-green-500">↓ {{ number_format(abs($expenseChange), 1) }}%</span> from last
                                month
                            @else
                                No change from last month
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Balance Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">Current Balance</h3>
                        <p class="text-3xl font-bold {{ $balance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            ${{ number_format($balance, 2) }}
                        </p>
                        <p class="text-sm text-gray-500 mt-2">
                            Total: ${{ number_format($totalIncome, 2) }} in / ${{ number_format($totalExpense, 2) }} out
                        </p>
                    </div>
                </div>
            </div>

            <!-- Recent Transactions -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-700">Recent Transactions</h3>
                        <a href="{{ route('transactions.index') }}"
                            class="text-sm text-blue-600 hover:text-blue-800">View All</a>
                    </div>

                    @if($recentTransactions->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Date</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Category</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Amount</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($recentTransactions as $transaction)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $transaction->date->format('M d, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $transaction->type === 'income' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ $transaction->category->name }}
                                                </span>
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm {{ $transaction->type === 'income' ? 'text-green-600' : 'text-red-600' }}">
                                                {{ $transaction->type === 'income' ? '+' : '-' }}${{ number_format($transaction->amount, 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('transactions.show', $transaction) }}"
                                                    class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                                                <a href="{{ route('transactions.edit', $transaction) }}"
                                                    class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-gray-500">No transactions yet.</p>
                            <a href="{{ route('transactions.create') }}"
                                class="mt-2 inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition">
                                Add Your First Transaction
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Links -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">Quick Actions</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <a href="{{ route('transactions.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest text-center hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition">
                            Add Transaction
                        </a>
                        <a href="{{ route('analytics') }}"
                            class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest text-center hover:bg-purple-700 active:bg-purple-900 focus:outline-none focus:border-purple-900 focus:ring ring-purple-300 disabled:opacity-25 transition">
                            View Analytics
                        </a>
                        <a href="{{ route('export') }}"
                            class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest text-center hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition">
                            Export Reports
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>