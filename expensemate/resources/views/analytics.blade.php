@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Analytics Dashboard</h1>
            <p class="text-gray-600">Insights into your financial patterns and spending habits</p>
        </div>

        <!-- Date Range Filter -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <form method="GET" action="{{ route('analytics') }}" class="flex flex-wrap gap-4 items-end">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                    <input type="date" name="start_date" value="{{ $startDate }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                    <input type="date" name="end_date" value="{{ $endDate }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                </div>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-md transition">
                    Update
                </button>
            </form>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Income</p>
                        <p class="text-2xl font-bold text-green-600">${{ number_format($totalIncome, 2) }}</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Expenses</p>
                        <p class="text-2xl font-bold text-red-600">${{ number_format($totalExpense, 2) }}</p>
                    </div>
                    <div class="bg-red-100 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 13l-5 5m0 0l-5-5m5 5V6"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Net Balance</p>
                        <p class="text-2xl font-bold {{ $balance >= 0 ? 'text-blue-600' : 'text-red-600' }}">
                            ${{ number_format($balance, 2) }}
                        </p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2-2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-8 mb-8">
            <!-- Pie Charts -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Expense Distribution</h3>
                @if($expensesByCategory->count() > 0)
                    <div style="height: 400px;">
                        <canvas id="expensePieChart"></canvas>
                    </div>
                @else
                    <div class="flex items-center justify-center h-64 text-gray-500">
                        <div class="text-center">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2-2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                </path>
                            </svg>
                            <p>No expense data for this period</p>
                        </div>
                    </div>
                @endif
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Income Distribution</h3>
                @if($incomeByCategory->count() > 0)
                    <div style="height: 400px;">
                        <canvas id="incomePieChart"></canvas>
                    </div>
                @else
                    <div class="flex items-center justify-center h-64 text-gray-500">
                        <div class="text-center">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                </path>
                            </svg>
                            <p>No income data for this period</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Daily Trend Chart -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Daily Trend</h3>
            <div style="height: 400px;">
                <canvas id="trendChart"></canvas>
            </div>
        </div>

        <!-- Category Comparison Bar Chart -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Category Comparison</h3>
            <div style="height: 400px;">
                <canvas id="categoryBarChart"></canvas>
            </div>
        </div>

        <!-- Category Breakdown (Existing Progress Bars) -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Expenses by Category -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Expenses by Category</h3>
                @if($expensesByCategory->count() > 0)
                    <div class="space-y-4">
                        @foreach($expensesByCategory as $expense)
                            <div class="category-item">
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-700">{{ $expense->name }}</span>
                                    <span class="font-semibold text-red-600">${{ number_format($expense->total, 2) }}</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-red-500 h-2 rounded-full progress-bar"
                                        data-percentage="{{ $totalExpense > 0 ? number_format(($expense->total / $totalExpense) * 100, 2) : 0 }}">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2-2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                        <p class="text-gray-500">No expense data for this period</p>
                    </div>
                @endif
            </div>

            <!-- Income by Category -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Income by Category</h3>
                @if($incomeByCategory->count() > 0)
                    <div class="space-y-4">
                        @foreach($incomeByCategory as $income)
                            <div class="category-item">
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-700">{{ $income->name }}</span>
                                    <span class="font-semibold text-green-600">${{ number_format($income->total, 2) }}</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-500 h-2 rounded-full progress-bar"
                                        data-percentage="{{ $totalIncome > 0 ? number_format(($income->total / $totalIncome) * 100, 2) : 0 }}">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                            </path>
                        </svg>
                        <p class="text-gray-500">No income data for this period</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Period Summary with Dynamic Data -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Period Summary</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-600">Total Transactions</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalTransactionCount }}</p>
                </div>
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-600">Average Transaction</p>
                    <p class="text-2xl font-bold text-gray-900">${{ number_format($averageTransaction, 2) }}</p>
                </div>
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-600">Savings Rate</p>
                    <p class="text-2xl font-bold {{ $balance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        @php
                            $savingsRate = $totalIncome > 0 ? (($totalIncome - $totalExpense) / $totalIncome) * 100 : 0;
                        @endphp
                        {{ number_format($savingsRate, 1) }}%
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- SAFE Chart Data Transfer -->
    <script>
        // Safe data transfer with error handling
        try {
            window.chartData = {!! json_encode($chartData ?? []) !!};
            console.log('Chart data loaded:', window.chartData);
        } catch (error) {
            console.error('Error parsing chart data:', error);
            window.chartData = {
                expensePie: { labels: [], data: [], colors: [] },
                incomePie: { labels: [], data: [], colors: [] },
                trend: { labels: [], income: [], expense: [] },
                categoryBar: { labels: [], income: [], expense: [] }
            };
        }
    </script>

    <!-- Include the analytics JavaScript -->
    <script src="{{ asset('js/analytics.js') }}"></script>
@endsection