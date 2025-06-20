@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8" data-chart-data="{{ json_encode($chartData) }}"
        data-has-expense-data="{{ $expensesByCategory->count() > 0 ? 'true' : 'false' }}"
        data-has-income-data="{{ $incomeByCategory->count() > 0 ? 'true' : 'false' }}">

        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Analytics Dashboard</h1>
            <p class="text-gray-600">Insights into your financial patterns and spending habits</p>
        </div>

        <!-- Date Range Filter with Quick Filters -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <!-- Quick Filter Buttons -->
            <div class="mb-6">
                <h4 class="text-sm font-medium text-gray-700 mb-3">Quick Filters</h4>
                <div class="flex flex-wrap gap-2">
                    <button type="button" onclick="setQuickFilter('thisMonth')" 
                        class="quick-filter-btn px-4 py-2 text-sm bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition"
                        data-filter="thisMonth">
                        This Month
                    </button>
                    <button type="button" onclick="setQuickFilter('previousMonth')" 
                        class="quick-filter-btn px-4 py-2 text-sm bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition"
                        data-filter="previousMonth">
                        Previous Month
                    </button>
                    <button type="button" onclick="setQuickFilter('thisYear')" 
                        class="quick-filter-btn px-4 py-2 text-sm bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition"
                        data-filter="thisYear">
                        This Year
                    </button>
                    <button type="button" onclick="setQuickFilter('previousYear')" 
                        class="quick-filter-btn px-4 py-2 text-sm bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition"
                        data-filter="previousYear">
                        Previous Year
                    </button>
                    <button type="button" onclick="setQuickFilter('allTime')" 
                        class="quick-filter-btn px-4 py-2 text-sm bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition"
                        data-filter="allTime">
                        All Time
                    </button>
                </div>
            </div>

            <!-- Date Range Form -->
            <form method="GET" action="{{ route('analytics') }}" id="analytics-form" class="flex flex-wrap gap-4 items-end">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                    <input type="date" name="start_date" id="start_date" value="{{ $startDate }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                    <input type="date" name="end_date" id="end_date" value="{{ $endDate }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                </div>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-md transition">
                    Apply Filter
                </button>
            </form>
            
            <!-- Current Filter Display -->
            <div class="mt-4 p-3 bg-gray-50 rounded-md">
                <p class="text-sm text-gray-600">
                    <span class="font-medium">Current Period:</span> 
                    <span id="current-period-display">{{ \Carbon\Carbon::parse($startDate)->format('M j, Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('M j, Y') }}</span>
                </p>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Income</p>
                        <p class="text-2xl font-bold text-green-600">৳{{ number_format($totalIncome, 2) }}</p>
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
                        <p class="text-2xl font-bold text-red-600">৳{{ number_format($totalExpense, 2) }}</p>
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
                            ৳{{ number_format($balance, 2) }}
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
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            <!-- Overall Transaction Distribution -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Income vs Expenses</h3>
                <div style="height: 300px;">
                    <canvas id="overallPieChart"></canvas>
                </div>
            </div>

            <!-- Expense Distribution -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Expense Categories</h3>
                @if($expensesByCategory->count() > 0)
                    <div style="height: 300px;">
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
                            <p>No expense data</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Income Distribution -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Income Categories</h3>
                @if($incomeByCategory->count() > 0)
                    <div style="height: 300px;">
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
                            <p>No income data</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Daily Trend Chart -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Daily Financial Trend</h3>
            <p class="text-sm text-gray-600 mb-4">Track your daily income and expenses to identify spending patterns</p>
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

        <!-- Category Breakdown -->
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
                                    <span class="font-semibold text-red-600">৳{{ number_format($expense->total, 2) }}</span>
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
                                    <span class="font-semibold text-green-600">৳{{ number_format($income->total, 2) }}</span>
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

        <!-- Period Summary -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Period Summary</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-600">Total Transactions</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalTransactionCount }}</p>
                </div>
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-600">Average Transaction</p>
                    <p class="text-2xl font-bold text-gray-900">৳{{ number_format($averageTransaction, 2) }}</p>
                </div>
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-600">Savings Rate</p>
                    <p class="text-2xl font-bold {{ $balance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ number_format($totalIncome > 0 ? (($totalIncome - $totalExpense) / $totalIncome) * 100 : 0, 1) }}%
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

    <!-- Chart Data -->
    <script id="chart-data" type="application/json">{!! json_encode($chartData ?? []) !!}</script>

    <script>
        // GLOBAL FUNCTIONS (Available before DOMContentLoaded)
        function setQuickFilter(filterType) {
            console.log('🔄 setQuickFilter called with:', filterType);
            const now = new Date();
            let startDate, endDate;
            
            switch(filterType) {
                case 'thisMonth':
                    startDate = new Date(now.getFullYear(), now.getMonth(), 1);
                    endDate = new Date(now.getFullYear(), now.getMonth() + 1, 0);
                    break;
                    
                case 'previousMonth':
                    startDate = new Date(now.getFullYear(), now.getMonth() - 1, 1);
                    endDate = new Date(now.getFullYear(), now.getMonth(), 0);
                    break;
                    
                case 'thisYear':
                    startDate = new Date(now.getFullYear(), 0, 1);
                    endDate = new Date(now.getFullYear(), 11, 31);
                    break;
                    
                case 'previousYear':
                    startDate = new Date(now.getFullYear() - 1, 0, 1);
                    endDate = new Date(now.getFullYear() - 1, 11, 31);
                    break;
                    
                case 'allTime':
                    startDate = new Date('2020-01-01');
                    endDate = new Date();
                    break;
                    
                default:
                    console.error('❌ Unknown filter type:', filterType);
                    return;
            }
            
            document.getElementById('start_date').value = formatDateForInput(startDate);
            document.getElementById('end_date').value = formatDateForInput(endDate);
            
            updateQuickFilterButtons(filterType);
            updateCurrentPeriodDisplay(startDate, endDate);
            
            document.getElementById('analytics-form').submit();
        }
        
        function formatDateForInput(date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        }
        
        function updateQuickFilterButtons(activeFilter) {
            console.log('🔄 Updating quick filter buttons, active:', activeFilter);
            // Reset all buttons to default state
            document.querySelectorAll('.quick-filter-btn').forEach(btn => {
                btn.classList.remove('bg-blue-500', 'text-white', 'active');
                btn.classList.add('bg-gray-200', 'text-gray-700');
            });
            
            // Activate the selected button
            const activeBtn = document.querySelector(`[data-filter="${activeFilter}"]`);
            if (activeBtn) {
                activeBtn.classList.remove('bg-gray-200', 'text-gray-700');
                activeBtn.classList.add('bg-blue-500', 'text-white', 'active');
                console.log('✅ Activated button:', activeFilter);
            } else {
                console.warn('⚠️ Button not found for filter:', activeFilter);
            }
        }
        
        function updateCurrentPeriodDisplay(startDate, endDate) {
            const options = { year: 'numeric', month: 'short', day: 'numeric' };
            const startStr = startDate.toLocaleDateString('en-US', options);
            const endStr = endDate.toLocaleDateString('en-US', options);
            document.getElementById('current-period-display').textContent = `${startStr} - ${endStr}`;
        }
        
        // ENHANCED: Fixed detectCurrentFilter function with better date comparison
        function detectCurrentFilter() {
            console.log('🔍 Detecting current filter...');
            
            const startDateValue = document.getElementById('start_date').value;
            const endDateValue = document.getElementById('end_date').value;
            
            if (!startDateValue || !endDateValue) {
                console.log('⚠️ No date values found');
                return;
            }
            
            const startDate = new Date(startDateValue);
            const endDate = new Date(endDateValue);
            const now = new Date();
            
            console.log('📅 Current dates:', {
                start: startDateValue,
                end: endDateValue,
                startParsed: startDate,
                endParsed: endDate
            });
            
            // This Month
            const thisMonthStart = new Date(now.getFullYear(), now.getMonth(), 1);
            const thisMonthEnd = new Date(now.getFullYear(), now.getMonth() + 1, 0);
            console.log('🗓️ This Month:', thisMonthStart.toISOString().split('T')[0], 'to', thisMonthEnd.toISOString().split('T')[0]);
            
            if (isSameDate(startDate, thisMonthStart) && isSameDate(endDate, thisMonthEnd)) {
                console.log('✅ Detected: thisMonth');
                updateQuickFilterButtons('thisMonth');
                return;
            }
            
            // Previous Month
            const prevMonthStart = new Date(now.getFullYear(), now.getMonth() - 1, 1);
            const prevMonthEnd = new Date(now.getFullYear(), now.getMonth(), 0);
            console.log('🗓️ Previous Month:', prevMonthStart.toISOString().split('T')[0], 'to', prevMonthEnd.toISOString().split('T')[0]);
            
            if (isSameDate(startDate, prevMonthStart) && isSameDate(endDate, prevMonthEnd)) {
                console.log('✅ Detected: previousMonth');
                updateQuickFilterButtons('previousMonth');
                return;
            }
            
            // This Year
            const thisYearStart = new Date(now.getFullYear(), 0, 1);
            const thisYearEnd = new Date(now.getFullYear(), 11, 31);
            console.log('🗓️ This Year:', thisYearStart.toISOString().split('T')[0], 'to', thisYearEnd.toISOString().split('T')[0]);
            
            if (isSameDate(startDate, thisYearStart) && isSameDate(endDate, thisYearEnd)) {
                console.log('✅ Detected: thisYear');
                updateQuickFilterButtons('thisYear');
                return;
            }
            
            // Previous Year
            const prevYearStart = new Date(now.getFullYear() - 1, 0, 1);
            const prevYearEnd = new Date(now.getFullYear() - 1, 11, 31);
            console.log('🗓️ Previous Year:', prevYearStart.toISOString().split('T')[0], 'to', prevYearEnd.toISOString().split('T')[0]);
            
            if (isSameDate(startDate, prevYearStart) && isSameDate(endDate, prevYearEnd)) {
                console.log('✅ Detected: previousYear');
                updateQuickFilterButtons('previousYear');
                return;
            }
            
            // All Time (very broad range)
            const allTimeStart = new Date('2020-01-01');
            if (isSameDate(startDate, allTimeStart) && endDate >= now) {
                console.log('✅ Detected: allTime');
                updateQuickFilterButtons('allTime');
                return;
            }
            
            console.log('🔍 No quick filter match found - custom date range');
            // No quick filter matches - it's a custom range, so no button should be active
            document.querySelectorAll('.quick-filter-btn').forEach(btn => {
                btn.classList.remove('bg-blue-500', 'text-white', 'active');
                btn.classList.add('bg-gray-200', 'text-gray-700');
            });
        }
        
        // Helper function to compare dates (only year, month, day)
        function isSameDate(date1, date2) {
            return date1.getFullYear() === date2.getFullYear() &&
                   date1.getMonth() === date2.getMonth() &&
                   date1.getDate() === date2.getDate();
        }

        // ENHANCED COLOR PALETTES FOR DIFFERENT CATEGORIES
        function getExpenseColors() {
            const colors = [
                '#EF4444', '#F97316', '#F59E0B', '#8B5CF6', '#EC4899', '#10B981', '#3B82F6', '#6366F1', '#84CC16', '#F472B6',
                '#06B6D4', '#8B5A2B', '#DC2626', '#EA580C', '#D97706', '#7C3AED', '#DB2777', '#059669', '#2563EB', '#4F46E5'
            ];
            return colors;
        }

        function getIncomeColors() {
            const colors = [
                '#22C55E', '#16A34A', '#15803D', '#166534', '#14532D', '#4ADE80', '#86EFAC', '#BBF7D0', '#10B981', '#059669',
                '#047857', '#065F46', '#064E3B', '#6EE7B7', '#A7F3D0', '#D1FAE5', '#34D399', '#6BCF7F', '#52C41A', '#73D13D'
            ];
            return colors;
        }

        // CHART FUNCTIONS (keeping them the same as they work)
        function createOverallPieChart(data) {
            const ctx = document.getElementById('overallPieChart');
            if (!ctx) return;

            const hasData = data && data.labels && data.labels.length > 0;

            try {
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: hasData ? data.labels : ['No Transactions'],
                        datasets: [{
                            data: hasData ? data.data : [1],
                            backgroundColor: hasData ? data.colors : ['#E5E7EB'],
                            borderWidth: 3,
                            borderColor: '#ffffff',
                            hoverBorderWidth: 4,
                            hoverOffset: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 20,
                                    usePointStyle: true,
                                    pointStyle: 'circle',
                                    font: { size: 13 }
                                }
                            },
                            tooltip: {
                                enabled: hasData,
                                callbacks: {
                                    label: function (context) {
                                        if (!hasData) return 'No data available';
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = total > 0 ? ((context.raw / total) * 100).toFixed(1) : '0';
                                        return `${context.label}: ৳${context.raw.toLocaleString()} (${percentage}%)`;
                                    }
                                }
                            }
                        },
                        animation: {
                            animateRotate: true,
                            duration: 1200,
                            easing: 'easeInOutQuart'
                        }
                    }
                });
            } catch (error) {
                console.error('❌ Error creating overall pie chart:', error);
            }
        }

        function createExpensePieChart(data) {
            const ctx = document.getElementById('expensePieChart');
            if (!ctx || !data || !data.labels || data.labels.length === 0) return;

            try {
                const colors = getExpenseColors().slice(0, data.labels.length);
                
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            data: data.data || [],
                            backgroundColor: colors,
                            borderWidth: 3,
                            borderColor: '#ffffff',
                            hoverBorderWidth: 4,
                            hoverOffset: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 15,
                                    usePointStyle: true,
                                    pointStyle: 'circle',
                                    font: { size: 11 }
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(239, 68, 68, 0.9)',
                                titleColor: '#ffffff',
                                bodyColor: '#ffffff',
                                callbacks: {
                                    label: function (context) {
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = total > 0 ? ((context.raw / total) * 100).toFixed(1) : '0';
                                        return `${context.label}: ৳${context.raw.toLocaleString()} (${percentage}%)`;
                                    }
                                }
                            }
                        },
                        animation: {
                            animateRotate: true,
                            duration: 1200,
                            easing: 'easeInOutQuart'
                        }
                    }
                });
            } catch (error) {
                console.error('❌ Error creating expense pie chart:', error);
            }
        }

        function createIncomePieChart(data) {
            const ctx = document.getElementById('incomePieChart');
            if (!ctx || !data || !data.labels || data.labels.length === 0) return;

            try {
                const colors = getIncomeColors().slice(0, data.labels.length);
                
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            data: data.data || [],
                            backgroundColor: colors,
                            borderWidth: 3,
                            borderColor: '#ffffff',
                            hoverBorderWidth: 4,
                            hoverOffset: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 15,
                                    usePointStyle: true,
                                    pointStyle: 'circle',
                                    font: { size: 11 }
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(34, 197, 94, 0.9)',
                                titleColor: '#ffffff',
                                bodyColor: '#ffffff',
                                callbacks: {
                                    label: function (context) {
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = total > 0 ? ((context.raw / total) * 100).toFixed(1) : '0';
                                        return `${context.label}: ৳${context.raw.toLocaleString()} (${percentage}%)`;
                                    }
                                }
                            }
                        },
                        animation: {
                            animateRotate: true,
                            duration: 1200,
                            easing: 'easeInOutQuart'
                        }
                    }
                });
            } catch (error) {
                console.error('❌ Error creating income pie chart:', error);
            }
        }

        function createTrendChart(data) {
            const ctx = document.getElementById('trendChart');
            if (!ctx || !data || !data.labels || data.labels.length === 0) return;

            const allValues = [...(data.income || []), ...(data.expense || [])];
            const maxValue = Math.max(...allValues, 0);
            const bufferedMax = maxValue + 1000;

            let yAxisMax, stepSize;
            if (bufferedMax <= 1000) {
                yAxisMax = Math.ceil(bufferedMax / 100) * 100;
                stepSize = 100;
            } else if (bufferedMax <= 5000) {
                yAxisMax = Math.ceil(bufferedMax / 500) * 500;
                stepSize = 500;
            } else if (bufferedMax <= 10000) {
                yAxisMax = Math.ceil(bufferedMax / 1000) * 1000;
                stepSize = 1000;
            } else if (bufferedMax <= 50000) {
                yAxisMax = Math.ceil(bufferedMax / 5000) * 5000;
                stepSize = 5000;
            } else {
                yAxisMax = Math.ceil(bufferedMax / 10000) * 10000;
                stepSize = 10000;
            }

            const isMonthly = data.aggregationType === 'monthly';
            const chartTitle = isMonthly ? 'Monthly Income vs Expenses Trend' : 'Daily Income vs Expenses Trend';
            const xAxisTitle = isMonthly ? 'Month' : 'Date';

            try {
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Income',
                            data: data.income || [],
                            borderColor: '#22C55E',
                            backgroundColor: 'rgba(34, 197, 94, 0.1)',
                            fill: false,
                            tension: 0.4,
                            pointRadius: isMonthly ? 6 : 4,
                            pointHoverRadius: isMonthly ? 8 : 6,
                            pointBackgroundColor: '#22C55E',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            borderWidth: 3
                        }, {
                            label: 'Expenses',
                            data: data.expense || [],
                            borderColor: '#EF4444',
                            backgroundColor: 'rgba(239, 68, 68, 0.1)',
                            fill: false,
                            tension: 0.4,
                            pointRadius: isMonthly ? 6 : 4,
                            pointHoverRadius: isMonthly ? 8 : 6,
                            pointBackgroundColor: '#EF4444',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            borderWidth: 3
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                min: 0,
                                max: yAxisMax,
                                ticks: {
                                    stepSize: stepSize,
                                    callback: function (value) {
                                        return '৳' + value.toLocaleString();
                                    }
                                },
                                grid: {
                                    display: true,
                                    color: 'rgba(0, 0, 0, 0.1)'
                                },
                                title: {
                                    display: true,
                                    text: 'Amount (BDT)',
                                    font: { size: 12, weight: 'bold' }
                                }
                            },
                            x: {
                                grid: { display: false },
                                title: {
                                    display: true,
                                    text: xAxisTitle,
                                    font: { size: 12, weight: 'bold' }
                                },
                                ticks: {
                                    maxRotation: data.labels.length > 15 ? 45 : 0,
                                    minRotation: 0,
                                    font: {
                                        size: data.labels.length > 20 ? 9 : (data.labels.length > 10 ? 10 : 11)
                                    },
                                    autoSkip: data.labels.length > 31,
                                    autoSkipPadding: 5
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    usePointStyle: true,
                                    padding: 20
                                }
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false,
                                callbacks: {
                                    label: function (context) {
                                        return `${context.dataset.label}: ৳${context.raw.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
                                    },
                                    footer: function (tooltipItems) {
                                        let income = 0, expense = 0;
                                        tooltipItems.forEach(item => {
                                            if (item.dataset.label === 'Income') income = item.raw;
                                            if (item.dataset.label === 'Expenses') expense = item.raw;
                                        });
                                        const net = income - expense;
                                        return 'Net: ৳' + net.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                                    }
                                }
                            },
                            title: {
                                display: true,
                                text: chartTitle,
                                font: { size: 14, weight: 'bold' }
                            }
                        },
                        interaction: {
                            mode: 'nearest',
                            axis: 'x',
                            intersect: false
                        },
                        animation: {
                            duration: 1000,
                            easing: 'easeInOutQuart'
                        }
                    }
                });
            } catch (error) {
                console.error('❌ Error creating trend chart:', error);
            }
        }

        function createCategoryBarChart(data) {
            const ctx = document.getElementById('categoryBarChart');
            if (!ctx || !data || !data.labels) return;

            try {
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Income',
                            data: data.income || [],
                            backgroundColor: 'rgba(34, 197, 94, 0.8)',
                            borderColor: '#22C55E',
                            borderWidth: 1
                        }, {
                            label: 'Expenses',
                            data: data.expense || [],
                            backgroundColor: 'rgba(239, 68, 68, 0.8)',
                            borderColor: '#EF4444',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function (value) {
                                        return '৳' + value.toLocaleString();
                                    }
                                }
                            }
                        },
                        plugins: {
                            legend: { position: 'top' },
                            tooltip: {
                                callbacks: {
                                    label: function (context) {
                                        return `${context.dataset.label}: ৳${context.raw.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
                                    }
                                }
                            }
                        },
                        animation: {
                            duration: 1000,
                            easing: 'easeInOutQuart'
                        }
                    }
                });
            } catch (error) {
                console.error('❌ Error creating category bar chart:', error);
            }
        }

        function initializeProgressBars() {
            const progressBars = document.querySelectorAll('.progress-bar');
            progressBars.forEach(bar => {
                const percentage = bar.getAttribute('data-percentage');
                if (percentage) {
                    bar.style.width = '0%';
                    bar.style.transition = 'width 1s ease-in-out';
                    setTimeout(() => {
                        bar.style.width = percentage + '%';
                    }, 100);
                }
            });
        }

        function initializeAllCharts(chartData, hasExpenseData, hasIncomeData) {
            createOverallPieChart(chartData.overallPie);
            
            if (hasExpenseData && chartData.expensePie && chartData.expensePie.labels && chartData.expensePie.labels.length > 0) {
                createExpensePieChart(chartData.expensePie);
            }
            
            if (hasIncomeData && chartData.incomePie && chartData.incomePie.labels && chartData.incomePie.labels.length > 0) {
                createIncomePieChart(chartData.incomePie);
            }
            
            createTrendChart(chartData.trend);
            createCategoryBarChart(chartData.categoryBar);
            initializeProgressBars();
        }

        // MAIN INITIALIZATION
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🌟 DOM LOADED - Starting Analytics Initialization...');
            
            // IMPORTANT: Detect current filter FIRST before setting up event listeners
            detectCurrentFilter();
            
            // Monitor date changes - clear active filter when manually changing dates
            document.getElementById('start_date').addEventListener('change', function() {
                console.log('📅 Start date changed manually');
                document.querySelectorAll('.quick-filter-btn').forEach(btn => {
                    btn.classList.remove('bg-blue-500', 'text-white', 'active');
                    btn.classList.add('bg-gray-200', 'text-gray-700');
                });
            });
            
            document.getElementById('end_date').addEventListener('change', function() {
                console.log('📅 End date changed manually');
                document.querySelectorAll('.quick-filter-btn').forEach(btn => {
                    btn.classList.remove('bg-blue-500', 'text-white', 'active');
                    btn.classList.add('bg-gray-200', 'text-gray-700');
                });
            });

            // Initialize charts after delay
            setTimeout(function() {
                if (typeof Chart === 'undefined') {
                    console.error('❌ Chart.js not loaded!');
                    return;
                }

                let chartData;
                try {
                    const chartDataElement = document.getElementById('chart-data');
                    if (!chartDataElement) {
                        console.error('❌ Chart data element not found!');
                        return;
                    }
                    
                    chartData = JSON.parse(chartDataElement.textContent);
                } catch (error) {
                    console.error('❌ Error parsing chart data:', error);
                    chartData = {
                        overallPie: { labels: [], data: [], colors: [] },
                        expensePie: { labels: [], data: [], colors: [] },
                        incomePie: { labels: [], data: [], colors: [] },
                        trend: { labels: [], income: [], expense: [] },
                        categoryBar: { labels: [], income: [], expense: [] }
                    };
                }

                const hasExpenseData = {{ $expensesByCategory->count() > 0 ? 'true' : 'false' }};
                const hasIncomeData = {{ $incomeByCategory->count() > 0 ? 'true' : 'false' }};

                initializeAllCharts(chartData, hasExpenseData, hasIncomeData);

            }, 500);
        });
    </script>
@endpush