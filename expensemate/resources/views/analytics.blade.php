@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8" data-chart-data="{{ json_encode($chartData) }}"
        data-has-expense-data="{{ $expensesByCategory->count() > 0 ? 'true' : 'false' }}"
        data-has-income-data="{{ $incomeByCategory->count() > 0 ? 'true' : 'false' }}">

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

        <!-- Summary Cards (Updated to BDT) -->
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

        <!-- Daily Trend Chart (Enhanced Title) -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Daily Financial Trend</h3>
            <p class="text-sm text-gray-600 mb-4">Track your daily income and expenses to identify spending patterns
            </p>
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

        <!-- Category Breakdown (Progress Bars) -->
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
    <!-- Chart.js CDN - Use UMD version (no ES6 modules) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

    <!-- FIXED: Safe Chart Data Transfer -->
    <script id="chart-data" type="application/json">{!! json_encode($chartData ?? []) !!}</script>

    <script>
        // Wait for DOM and Chart.js to load
        document.addEventListener('DOMContentLoaded', function () {
            // Add additional delay to ensure Chart.js is fully loaded
            setTimeout(function () {
                // Check if Chart.js is available
                if (typeof Chart === 'undefined') {
                    console.error('Chart.js not loaded! Charts will not work.');
                    return;
                }

                console.log('Chart.js loaded successfully, version:', Chart.version);

                // Safe data retrieval from script tag
                let chartData;
                try {
                    const chartDataElement = document.getElementById('chart-data');
                    chartData = JSON.parse(chartDataElement.textContent);
                    console.log('Chart data loaded successfully:', chartData);
                } catch (error) {
                    console.error('Error parsing chart data:', error);
                    chartData = {
                        overallPie: { labels: [], data: [], colors: [] },
                        expensePie: { labels: [], data: [], colors: [] },
                        incomePie: { labels: [], data: [], colors: [] },
                        trend: { labels: [], income: [], expense: [] },
                        categoryBar: { labels: [], income: [], expense: [] }
                    };
                }

                // Fallback if data is empty
                if (!chartData || typeof chartData !== 'object') {
                    chartData = {
                        overallPie: { labels: [], data: [], colors: [] },
                        expensePie: { labels: [], data: [], colors: [] },
                        incomePie: { labels: [], data: [], colors: [] },
                        trend: { labels: [], income: [], expense: [] },
                        categoryBar: { labels: [], income: [], expense: [] }
                    };
                }

                // Set global chart data
                window.chartData = chartData;

                // Initialize charts directly here instead of relying on analytics.js
                const hasExpenseData = {{ $expensesByCategory->count() > 0 ? 'true' : 'false' }};
                const hasIncomeData = {{ $incomeByCategory->count() > 0 ? 'true' : 'false' }};

                initializeAllCharts(chartData, hasExpenseData, hasIncomeData);

            }, 300); // 300ms delay to ensure Chart.js is ready
        });

        // Initialize all charts function
        function initializeAllCharts(chartData, hasExpenseData, hasIncomeData) {
            console.log('Initializing all charts...');

            // Initialize Overall Pie Chart
            createOverallPieChart(chartData.overallPie);

            // Initialize Expense Pie Chart
            if (hasExpenseData && chartData.expensePie.labels.length > 0) {
                createExpensePieChart(chartData.expensePie);
            }

            // Initialize Income Pie Chart
            if (hasIncomeData && chartData.incomePie.labels.length > 0) {
                createIncomePieChart(chartData.incomePie);
            }

            // Initialize Trend Chart
            createTrendChart(chartData.trend);

            // Initialize Category Bar Chart
            createCategoryBarChart(chartData.categoryBar);

            // Initialize progress bars
            initializeProgressBars();
        }

        // Chart creation functions
        function createOverallPieChart(data) {
            const ctx = document.getElementById('overallPieChart');
            if (!ctx) {
                console.error('Overall pie chart canvas not found');
                return;
            }

            const hasData = data && data.labels && data.labels.length > 0;

            try {
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: hasData ? data.labels : ['No Transactions'],
                        datasets: [{
                            data: hasData ? data.data : [1],
                            backgroundColor: hasData ? data.colors : ['#E5E7EB'],
                            borderWidth: 2,
                            borderColor: '#ffffff'
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
                                    font: {
                                        size: 12
                                    }
                                }
                            },
                            tooltip: {
                                enabled: hasData,
                                callbacks: {
                                    label: function (context) {
                                        if (!hasData) return 'No data available';
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = total > 0 ? ((context.raw / total) * 100).toFixed(1) : '0';
                                        return `${context.label}: ৳${context.raw.toFixed(2)} (${percentage}%)`;
                                    }
                                }
                            }
                        },
                        animation: {
                            animateRotate: true,
                            duration: 1000
                        }
                    }
                });
                console.log('Overall pie chart created successfully');
            } catch (error) {
                console.error('Error creating overall pie chart:', error);
            }
        }

        function createExpensePieChart(data) {
            const ctx = document.getElementById('expensePieChart');
            if (!ctx || !data || !data.labels || data.labels.length === 0) {
                console.log('No expense data available for pie chart');
                return;
            }

            try {
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            data: data.data || [],
                            backgroundColor: data.colors || ['#EF4444', '#F97316', '#F59E0B'],
                            borderWidth: 2,
                            borderColor: '#ffffff'
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
                                    font: {
                                        size: 11
                                    }
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function (context) {
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = total > 0 ? ((context.raw / total) * 100).toFixed(1) : '0';
                                        return `${context.label}: ৳${context.raw.toFixed(2)} (${percentage}%)`;
                                    }
                                }
                            }
                        },
                        animation: {
                            animateRotate: true,
                            duration: 1000
                        }
                    }
                });
                console.log('Expense pie chart created successfully');
            } catch (error) {
                console.error('Error creating expense pie chart:', error);
            }
        }

        function createIncomePieChart(data) {
            const ctx = document.getElementById('incomePieChart');
            if (!ctx || !data || !data.labels || data.labels.length === 0) {
                console.log('No income data available for pie chart');
                return;
            }

            try {
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            data: data.data || [],
                            backgroundColor: data.colors || ['#22C55E', '#16A34A', '#15803D'],
                            borderWidth: 2,
                            borderColor: '#ffffff'
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
                                    font: {
                                        size: 11
                                    }
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function (context) {
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = total > 0 ? ((context.raw / total) * 100).toFixed(1) : '0';
                                        return `${context.label}: ৳${context.raw.toFixed(2)} (${percentage}%)`;
                                    }
                                }
                            }
                        },
                        animation: {
                            animateRotate: true,
                            duration: 1000
                        }
                    }
                });
                console.log('Income pie chart created successfully');
            } catch (error) {
                console.error('Error creating income pie chart:', error);
            }
        }

        function createTrendChart(data) {
            const ctx = document.getElementById('trendChart');
            if (!ctx) {
                console.error('Trend chart canvas not found');
                return null;
            }

            console.log('Creating trend chart with data:', data);

            // Handle empty data
            if (!data.labels || data.labels.length === 0) {
                this.showNoDataMessage('trendChart', 'No trend data available for the selected period');
                return null;
            }

            // ENHANCED: Calculate Y-axis scale with +1000 buffer
            const allValues = [...(data.income || []), ...(data.expense || [])];
            const maxValue = Math.max(...allValues, 0);

            // Add 1000 to the maximum value as requested
            const bufferedMax = maxValue + 1000;

            // Determine appropriate step size and final max
            let yAxisMax, stepSize;

            if (bufferedMax <= 1000) {
                yAxisMax = Math.ceil(bufferedMax / 100) * 100; // Round up to nearest 100
                stepSize = 100;
            } else if (bufferedMax <= 5000) {
                yAxisMax = Math.ceil(bufferedMax / 500) * 500; // Round up to nearest 500
                stepSize = 500;
            } else if (bufferedMax <= 10000) {
                yAxisMax = Math.ceil(bufferedMax / 1000) * 1000; // Round up to nearest 1000
                stepSize = 1000;
            } else if (bufferedMax <= 50000) {
                yAxisMax = Math.ceil(bufferedMax / 5000) * 5000; // Round up to nearest 5000
                stepSize = 5000;
            } else {
                yAxisMax = Math.ceil(bufferedMax / 10000) * 10000; // Round up to nearest 10000
                stepSize = 10000;
            }

            console.log('Y-axis scaling:', {
                maxValue: maxValue,
                bufferedMax: bufferedMax,
                yAxisMax: yAxisMax,
                stepSize: stepSize
            });

            // Determine chart title based on aggregation type
            const isMonthly = data.aggregationType === 'monthly';
            const chartTitle = isMonthly ? 'Monthly Income vs Expenses Trend' : 'Daily Income vs Expenses Trend';
            const xAxisTitle = isMonthly ? 'Month' : 'Date';

            return new Chart(ctx, {
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
                        pointRadius: isMonthly ? 6 : 4, // Larger points for monthly view
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
                        pointRadius: isMonthly ? 6 : 4, // Larger points for monthly view
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
                            max: yAxisMax, // ENHANCED: Now includes +1000 buffer
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
                                font: {
                                    size: 12,
                                    weight: 'bold'
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            title: {
                                display: true,
                                text: xAxisTitle, // ENHANCED: Changes based on aggregation
                                font: {
                                    size: 12,
                                    weight: 'bold'
                                }
                            },
                            ticks: {
                                // FIXED: Remove maxTicksLimit to show all labels
                                // maxTicksLimit: isMonthly ? 12 : 20, // REMOVED THIS LINE

                                // Instead, use smart rotation and font sizing based on data length
                                maxRotation: data.labels.length > 15 ? 45 : 0, // Rotate if many labels
                                minRotation: 0,
                                font: {
                                    size: data.labels.length > 20 ? 9 : (data.labels.length > 10 ? 10 : 11)
                                },
                                // Auto-skip only if there are too many labels (more than 31)
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
                            text: chartTitle, // ENHANCED: Dynamic title
                            font: {
                                size: 14,
                                weight: 'bold'
                            }
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
        }

        function createCategoryBarChart(data) {
            const ctx = document.getElementById('categoryBarChart');
            if (!ctx || !data || !data.labels) {
                console.log('No category comparison data available');
                return;
            }

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
                            legend: {
                                position: 'top',
                            },
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
                console.log('Category bar chart created successfully');
            } catch (error) {
                console.error('Error creating category bar chart:', error);
            }
        }

        function initializeProgressBars() {
            // Set progress bar widths from data attributes
            const progressBars = document.querySelectorAll('.progress-bar');

            progressBars.forEach(bar => {
                const percentage = bar.getAttribute('data-percentage');
                if (percentage) {
                    // Start at 0 width for animation
                    bar.style.width = '0%';
                    bar.style.transition = 'width 1s ease-in-out';

                    // Set the actual width after a short delay for animation
                    setTimeout(() => {
                        bar.style.width = percentage + '%';
                    }, 100);
                }
            });
        }
    </script>
@endpush