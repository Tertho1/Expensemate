/**
 * Initialize charts for the analytics page
 * @param {Object} chartData - Data needed for the charts
 */
function initializeCharts(chartData) {
    // Extract data from the chartData object
    const {
        hasExpenseData,
        hasIncomeData,
        hasDailyData,
        expenseCategoryNames,
        expenseCategoryValues,
        incomeCategoryNames,
        incomeCategoryValues,
        dates,
        incomeValues,
        expenseValues
    } = chartData;

    // Colors for charts
    const colorPalette = [
        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40',
        '#8AC249', '#EA526F', '#25CED1', '#FCEADE', '#FF8A5B', '#EA8C55'
    ];

    // Initialize Expense Pie Chart
    if (hasExpenseData) {
        const expenseCtx = document.getElementById('expensesPieChart').getContext('2d');
        new Chart(expenseCtx, {
            type: 'pie',
            data: {
                labels: expenseCategoryNames,
                datasets: [{
                    data: expenseCategoryValues,
                    backgroundColor: colorPalette,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }

    // Initialize Income Pie Chart
    if (hasIncomeData) {
        const incomeCtx = document.getElementById('incomePieChart').getContext('2d');
        new Chart(incomeCtx, {
            type: 'pie',
            data: {
                labels: incomeCategoryNames,
                datasets: [{
                    data: incomeCategoryValues,
                    backgroundColor: colorPalette.slice().reverse(),
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }

    // Initialize Daily Transactions Bar Chart
    if (hasDailyData) {
        const dailyCtx = document.getElementById('dailyTransactionsChart').getContext('2d');
        new Chart(dailyCtx, {
            type: 'bar',
            data: {
                labels: dates,
                datasets: [
                    {
                        label: 'Income',
                        data: incomeValues,
                        backgroundColor: 'rgba(75, 192, 192, 0.5)',
                        borderColor: 'rgb(75, 192, 192)',
                        borderWidth: 1
                    },
                    {
                        label: 'Expenses',
                        data: expenseValues,
                        backgroundColor: 'rgba(255, 99, 132, 0.5)',
                        borderColor: 'rgb(255, 99, 132)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
}

document.addEventListener('DOMContentLoaded', function () {
    // Get data from the container element
    const container = document.querySelector('.container.mx-auto[data-chart-data]');

    if (!container) {
        console.warn('Analytics container not found');
        return;
    }

    // Safely parse chart data from data attribute
    let chartData;
    try {
        const rawData = container.getAttribute('data-chart-data');
        chartData = JSON.parse(rawData);
        console.log('Chart data loaded from data attribute:', chartData);
    } catch (error) {
        console.error('Error parsing chart data from data attribute:', error);
        chartData = {
            overallPie: { labels: [], data: [], colors: [] },
            expensePie: { labels: [], data: [], colors: [] },
            incomePie: { labels: [], data: [], colors: [] },
            trend: { labels: [], income: [], expense: [] },
            categoryBar: { labels: [], income: [], expense: [] }
        };
    }

    // Get boolean flags
    const hasExpenseData = container.getAttribute('data-has-expense-data') === 'true';
    const hasIncomeData = container.getAttribute('data-has-income-data') === 'true';

    // Initialize the analytics dashboard
    initializeAnalytics(chartData, hasExpenseData, hasIncomeData);
});

function initializeAnalytics(chartData, hasExpenseData, hasIncomeData) {
    // Set default date range if not already set
    setDefaultDateRange();

    // Add event listeners
    setupEventListeners();

    // Initialize progress bars with proper widths
    initializeProgressBars();

    // Initialize charts with provided data
    initializeCharts(chartData, hasExpenseData, hasIncomeData);

    // Enhance data display
    enhanceDataDisplay();
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

function initializeCharts(chartData, hasExpenseData, hasIncomeData) {
    // Add tooltips to progress bars
    addProgressBarTooltips();

    // Initialize Chart.js charts with provided data
    if (chartData && typeof chartData === 'object') {
        try {
            // Always create overall pie chart if we have any data
            createOverallPieChart(chartData.overallPie);

            if (hasExpenseData) {
                createExpensePieChart(chartData.expensePie);
            }
            if (hasIncomeData) {
                createIncomePieChart(chartData.incomePie);
            }
            createTrendChart(chartData.trend);
            createCategoryBarChart(chartData.categoryBar);
        } catch (error) {
            console.error('Error initializing charts:', error);
        }
    } else {
        console.warn('Chart data is not available or invalid');
    }
}

function createOverallPieChart(data) {
    const ctx = document.getElementById('overallPieChart');
    if (!ctx) return;

    // Show chart even if no data to indicate no transactions
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
    } catch (error) {
        console.error('Error creating overall pie chart:', error);
    }
}

function createExpensePieChart(data) {
    const ctx = document.getElementById('expensePieChart');
    if (!ctx || !data || !data.labels || !Array.isArray(data.labels) || data.labels.length === 0) {
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
    } catch (error) {
        console.error('Error creating expense pie chart:', error);
    }
}

function createIncomePieChart(data) {
    const ctx = document.getElementById('incomePieChart');
    if (!ctx || !data || !data.labels || !Array.isArray(data.labels) || data.labels.length === 0) {
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
    } catch (error) {
        console.error('Error creating income pie chart:', error);
    }
}

function createTrendChart(data) {
    const ctx = document.getElementById('trendChart');
    if (!ctx || !data || !data.labels || !Array.isArray(data.labels)) {
        console.log('No trend data available');
        return;
    }

    console.log('Creating trend chart with data:', data); // Debug log

    // FIXED: Calculate proper min/max values for BDT scaling
    const allValues = [...(data.income || []), ...(data.expense || [])];
    const maxValue = Math.max(...allValues, 0);
    const minValue = 0; // Always start from 0 for financial data

    // Set appropriate Y-axis limits with BDT ranges
    let yAxisMax, stepSize;

    if (maxValue <= 100) {
        yAxisMax = 100;
        stepSize = 10;
    } else if (maxValue <= 1000) {
        yAxisMax = Math.ceil(maxValue / 100) * 100;
        stepSize = 100;
    } else if (maxValue <= 10000) {
        yAxisMax = Math.ceil(maxValue / 1000) * 1000;
        stepSize = 1000;
    } else {
        yAxisMax = Math.ceil(maxValue / 10000) * 10000;
        stepSize = 5000;
    }

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
                    pointRadius: 5,
                    pointHoverRadius: 8,
                    pointBackgroundColor: '#22C55E',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2
                }, {
                    label: 'Expenses',
                    data: data.expense || [],
                    borderColor: '#EF4444',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    fill: false,
                    tension: 0.4,
                    pointRadius: 5,
                    pointHoverRadius: 8,
                    pointBackgroundColor: '#EF4444',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        min: minValue,
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
                            text: 'Date',
                            font: {
                                size: 12,
                                weight: 'bold'
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function (context) {
                                return `${context.dataset.label}: ৳${context.raw.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
                            }
                        }
                    },
                    title: {
                        display: true,
                        text: 'Daily Income vs Expenses Trend',
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
    } catch (error) {
        console.error('Error creating trend chart:', error);
    }
}

function createCategoryBarChart(data) {
    const ctx = document.getElementById('categoryBarChart');
    if (!ctx || !data || !data.labels || !Array.isArray(data.labels)) {
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
    } catch (error) {
        console.error('Error creating category bar chart:', error);
    }
}

// Add the rest of the helper functions (keeping them the same)...
function setDefaultDateRange() {
    const startDateInput = document.querySelector('input[name="start_date"]');
    const endDateInput = document.querySelector('input[name="end_date"]');

    if (startDateInput && endDateInput) {
        // Only set defaults if fields are empty
        if (!startDateInput.value) {
            const now = new Date();
            const startOfMonth = new Date(now.getFullYear(), now.getMonth(), 1);
            startDateInput.value = startOfMonth.toISOString().split('T')[0];
        }

        if (!endDateInput.value) {
            const now = new Date();
            endDateInput.value = now.toISOString().split('T')[0];
        }
    }
}

function setupEventListeners() {
    // Date range form submission
    const dateForm = document.querySelector('form[action*="analytics"]');
    if (dateForm) {
        dateForm.addEventListener('submit', function (e) {
            const startDate = document.querySelector('input[name="start_date"]').value;
            const endDate = document.querySelector('input[name="end_date"]').value;

            // Validate date range
            if (startDate && endDate && startDate > endDate) {
                e.preventDefault();
                showAlert('Start date cannot be later than end date.', 'error');
                return false;
            }

            // Show loading state
            showLoadingState();
        });
    }

    // Quick date range buttons
    addQuickDateRangeButtons();
}

function addQuickDateRangeButtons() {
    const dateForm = document.querySelector('form[action*="analytics"]');
    if (!dateForm) return;

    // Create quick range buttons container
    const quickRangeDiv = document.createElement('div');
    quickRangeDiv.className = 'mt-4 flex flex-wrap gap-2';
    quickRangeDiv.innerHTML = `
        <span class="text-sm font-medium text-gray-700 mr-2">Quick ranges:</span>
        <button type="button" onclick="setDateRange('thisMonth')" class="px-3 py-1 text-xs bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition">
            This Month
        </button>
        <button type="button" onclick="setDateRange('lastMonth')" class="px-3 py-1 text-xs bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition">
            Last Month
        </button>
        <button type="button" onclick="setDateRange('thisYear')" class="px-3 py-1 text-xs bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition">
            This Year
        </button>
        <button type="button" onclick="setDateRange('lastYear')" class="px-3 py-1 text-xs bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition">
            Last Year
        </button>
        <button type="button" onclick="setDateRange('all')" class="px-3 py-1 text-xs bg-gray-100 text-gray-700 rounded hover:bg-gray-200 transition">
            All Time
        </button>
    `;

    dateForm.appendChild(quickRangeDiv);
}

function setDateRange(range) {
    const startDateInput = document.querySelector('input[name="start_date"]');
    const endDateInput = document.querySelector('input[name="end_date"]');
    const now = new Date();

    let startDate, endDate;

    switch (range) {
        case 'thisMonth':
            startDate = new Date(now.getFullYear(), now.getMonth(), 1);
            endDate = new Date(now.getFullYear(), now.getMonth() + 1, 0);
            break;

        case 'lastMonth':
            startDate = new Date(now.getFullYear(), now.getMonth() - 1, 1);
            endDate = new Date(now.getFullYear(), now.getMonth(), 0);
            break;

        case 'thisYear':
            startDate = new Date(now.getFullYear(), 0, 1);
            endDate = new Date(now.getFullYear(), 11, 31);
            break;

        case 'lastYear':
            startDate = new Date(now.getFullYear() - 1, 0, 1);
            endDate = new Date(now.getFullYear() - 1, 11, 31);
            break;

        case 'all':
            startDateInput.value = '';
            endDateInput.value = '';
            document.querySelector('form[action*="analytics"]').submit();
            return;

        default:
            return;
    }

    startDateInput.value = startDate.toISOString().split('T')[0];
    endDateInput.value = endDate.toISOString().split('T')[0];

    // Auto-submit the form
    document.querySelector('form[action*="analytics"]').submit();
}

function addProgressBarTooltips() {
    const categoryItems = document.querySelectorAll('.category-item');

    categoryItems.forEach(item => {
        const categoryName = item.querySelector('.text-gray-700');
        const amount = item.querySelector('.font-semibold');
        const progressBar = item.querySelector('.progress-bar');

        if (categoryName && amount && progressBar) {
            const percentage = progressBar.getAttribute('data-percentage');
            const tooltip = `${categoryName.textContent}: ${amount.textContent} (${percentage}%)`;

            item.title = tooltip;
            item.style.cursor = 'help';
        }
    });
}

function enhanceDataDisplay() {
    // Add percentage labels to summary cards
    addPercentageLabels();

    // Enhance empty state messages
    enhanceEmptyStates();

    // Add export quick action
    addExportQuickAction();
}

function addPercentageLabels() {
    const incomeCard = document.querySelector('.text-green-600');
    const expenseCard = document.querySelector('.text-red-600');

    if (incomeCard && expenseCard) {
        const incomeAmount = parseFloat(incomeCard.textContent.replace(/[৳$,]/g, ''));
        const expenseAmount = parseFloat(expenseCard.textContent.replace(/[৳$,]/g, ''));
        const total = incomeAmount + expenseAmount;

        if (total > 0) {
            const incomePercentage = ((incomeAmount / total) * 100).toFixed(1);
            const expensePercentage = ((expenseAmount / total) * 100).toFixed(1);

            // Add small percentage indicators
            addPercentageIndicator(incomeCard, incomePercentage + '%');
            addPercentageIndicator(expenseCard, expensePercentage + '%');
        }
    }
}

function addPercentageIndicator(element, percentage) {
    const card = element.closest('.bg-white');
    if (card && !card.querySelector('.percentage-indicator')) {
        const indicator = document.createElement('span');
        indicator.className = 'percentage-indicator text-xs text-gray-500 ml-2';
        indicator.textContent = `(${percentage})`;
        element.appendChild(indicator);
    }
}

function enhanceEmptyStates() {
    const emptyStates = document.querySelectorAll('.text-center.py-8');

    emptyStates.forEach(emptyState => {
        if (emptyState.textContent.includes('No expense data') ||
            emptyState.textContent.includes('No income data')) {

            // Add helpful suggestions
            const suggestion = document.createElement('div');
            suggestion.className = 'mt-4 text-sm text-blue-600';
            suggestion.innerHTML = `
                <a href="/transactions/create" class="hover:underline">
                    + Add your first transaction to see analytics
                </a>
            `;
            emptyState.appendChild(suggestion);
        }
    });
}

function addExportQuickAction() {
    const analyticsHeader = document.querySelector('.mb-8 h1');
    if (analyticsHeader && !document.querySelector('.export-quick-action')) {
        const headerContainer = analyticsHeader.parentElement;
        const exportButton = document.createElement('a');
        exportButton.href = '/export';
        exportButton.className = 'export-quick-action inline-flex items-center px-4 py-2 bg-purple-500 hover:bg-purple-600 text-white text-sm rounded-lg transition';
        exportButton.innerHTML = `
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Export Data
        `;

        // Add to header container
        const headerWrapper = document.createElement('div');
        headerWrapper.className = 'flex justify-between items-start';
        headerContainer.insertBefore(headerWrapper, analyticsHeader);
        headerWrapper.appendChild(analyticsHeader);
        headerWrapper.appendChild(exportButton);
    }
}

function showLoadingState() {
    const submitButton = document.querySelector('button[type="submit"]');
    if (submitButton) {
        submitButton.disabled = true;
        submitButton.textContent = 'Loading...';
        submitButton.className = submitButton.className.replace('bg-blue-500', 'bg-blue-300');
    }
}

function showAlert(message, type = 'info') {
    // Remove any existing alerts
    const existingAlert = document.querySelector('.analytics-alert');
    if (existingAlert) {
        existingAlert.remove();
    }

    const alertColors = {
        'success': 'bg-green-100 border-green-400 text-green-700',
        'error': 'bg-red-100 border-red-400 text-red-700',
        'warning': 'bg-yellow-100 border-yellow-400 text-yellow-700',
        'info': 'bg-blue-100 border-blue-400 text-blue-700'
    };

    const alert = document.createElement('div');
    alert.className = `analytics-alert ${alertColors[type]} px-4 py-3 rounded mb-4 border`;
    alert.textContent = message;

    // Insert after the header
    const container = document.querySelector('.container.mx-auto');
    const header = container.querySelector('.mb-8');
    header.parentNode.insertBefore(alert, header.nextSibling);

    // Auto-remove after 5 seconds
    setTimeout(() => {
        alert.remove();
    }, 5000);
}

// Utility functions for formatting
function formatCurrency(amount) {
    return new Intl.NumberFormat('en-BD', {
        style: 'currency',
        currency: 'BDT',
        currencyDisplay: 'symbol'
    }).format(amount);
}

function formatPercentage(value) {
    return new Intl.NumberFormat('en-US', {
        style: 'percent',
        minimumFractionDigits: 1,
        maximumFractionDigits: 1
    }).format(value / 100);
}

// Export functions for global access
window.setDateRange = setDateRange;
window.showAlert = showAlert;
window.formatCurrency = formatCurrency;
window.formatPercentage = formatPercentage;