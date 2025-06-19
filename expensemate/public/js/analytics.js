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