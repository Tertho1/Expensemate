@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Financial Analytics</h1>
        
        <!-- Date Range Filter -->
        <form action="{{ route('analytics') }}" method="GET" class="flex space-x-4">
            <div>
                <label class="block text-sm text-gray-600">Start Date</label>
                <input type="date" name="start_date" value="{{ $startDate }}" 
                    class="border rounded px-2 py-1">
            </div>
            <div>
                <label class="block text-sm text-gray-600">End Date</label>
                <input type="date" name="end_date" value="{{ $endDate }}" 
                    class="border rounded px-2 py-1">
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-blue-500 text-white px-4 py-1 rounded">Apply</button>
            </div>
        </form>
    </div>
    
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Income Card -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Total Income</h3>
            <p class="text-3xl font-bold text-green-600">${{ number_format($totalIncome, 2) }}</p>
        </div>
        
        <!-- Expense Card -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Total Expenses</h3>
            <p class="text-3xl font-bold text-red-600">${{ number_format($totalExpense, 2) }}</p>
        </div>
        
        <!-- Balance Card -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Balance</h3>
            <p class="text-3xl font-bold {{ $balance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                ${{ number_format($balance, 2) }}
            </p>
        </div>
    </div>
    
    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Pie Chart: Expenses by Category -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Expenses by Category</h3>
            <div class="h-64">
                <canvas id="expensesPieChart"></canvas>
                @if($expensesByCategory->isEmpty())
                    <div class="flex h-full items-center justify-center">
                        <p class="text-gray-500">No expense data available for the selected period</p>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Pie Chart: Income by Category -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Income by Category</h3>
            <div class="h-64">
                <canvas id="incomePieChart"></canvas>
                @if($incomeByCategory->isEmpty())
                    <div class="flex h-full items-center justify-center">
                        <p class="text-gray-500">No income data available for the selected period</p>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Bar Chart: Daily Transactions -->
        <div class="bg-white p-6 rounded-lg shadow lg:col-span-2">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Daily Transactions</h3>
            <div class="h-80">
                <canvas id="dailyTransactionsChart"></canvas>
                @if($dailyTransactions->isEmpty())
                    <div class="flex h-full items-center justify-center">
                        <p class="text-gray-500">No transaction data available for the selected period</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    @php
    // Format expense category data for JS
    $expenseCategoryNames = $expensesByCategory->pluck('name');
    $expenseCategoryValues = $expensesByCategory->pluck('total');
    
    // Format income category data for JS
    $incomeCategoryNames = $incomeByCategory->pluck('name');
    $incomeCategoryValues = $incomeByCategory->pluck('total');
    
    // Format daily transactions data for JS
    $dates = [];
    $incomeValues = [];
    $expenseValues = [];
    
    foreach($dailyTransactions as $date => $transactions) {
        $dates[] = \Carbon\Carbon::parse($date)->format("M d");
        
        $dailyIncome = 0;
        $dailyExpense = 0;
        
        foreach($transactions as $transaction) {
            if($transaction->type === 'income') {
                $dailyIncome += $transaction->total;
            } else {
                $dailyExpense += $transaction->total;
            }
        }
        
        $incomeValues[] = $dailyIncome;
        $expenseValues[] = $dailyExpense;
    }
    
    // Create a data object for the charts
    $chartData = [
        'hasExpenseData' => !$expensesByCategory->isEmpty(),
        'hasIncomeData' => !$incomeByCategory->isEmpty(),
        'hasDailyData' => !$dailyTransactions->isEmpty(),
        'expenseCategoryNames' => $expenseCategoryNames,
        'expenseCategoryValues' => $expenseCategoryValues,
        'incomeCategoryNames' => $incomeCategoryNames,
        'incomeCategoryValues' => $incomeCategoryValues,
        'dates' => $dates,
        'incomeValues' => $incomeValues,
        'expenseValues' => $expenseValues
    ];
    
    // Convert to JSON (once)
    $chartDataJson = json_encode($chartData);
    @endphp
    
    <!-- Hidden element to store data -->
    <div id="chart-data" style="display: none;" data-chart="{{ htmlspecialchars($chartDataJson) }}"></div>
</div>

<!-- Chart.js and Analytics Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('js/analytics.js') }}"></script>
<script>
    // Use standard DOM methods to get the data (no Blade/PHP in this script)
    document.addEventListener('DOMContentLoaded', function() {
        const dataElement = document.getElementById('chart-data');
        const chartData = JSON.parse(dataElement.getAttribute('data-chart'));
        initializeCharts(chartData);
    });
</script>
@endsection
