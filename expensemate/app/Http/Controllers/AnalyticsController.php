<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        // Get authenticated user ID
        $userId = Auth::id();

        // ENHANCED: Set default to "This Month" instead of current month range
        $now = Carbon::now();
        $defaultStartDate = $now->copy()->startOfMonth()->format('Y-m-d'); // First day of current month
        $defaultEndDate = $now->copy()->endOfMonth()->format('Y-m-d');     // Last day of current month (handles 28/29/30/31 correctly)

        $startDate = $request->input('start_date', $defaultStartDate);
        $endDate = $request->input('end_date', $defaultEndDate);

        // DEBUG: Check the date range and user
        Log::info('Analytics Debug Start:', [
            'userId' => $userId,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'defaultStartDate' => $defaultStartDate,
            'defaultEndDate' => $defaultEndDate,
            'requestParams' => $request->all()
        ]);

        // DEBUG: Check if we have any transactions at all for this user
        $allUserTransactions = Transaction::where('user_id', $userId)->get();
        Log::info('All User Transactions:', [
            'total_count' => $allUserTransactions->count(),
            'transactions' => $allUserTransactions->toArray()
        ]);

        // Initialize empty collections
        $expensesByCategory = collect();
        $incomeByCategory = collect();
        $dailyTransactions = collect();
        $totalIncome = 0;
        $totalExpense = 0;
        $totalTransactionCount = 0;
        $averageTransaction = 0;

        if ($userId) {
            // Get expense data by category with count
            $expensesByCategory = Transaction::where('transactions.user_id', $userId)
                ->where('type', 'expense')
                ->whereBetween('date', [$startDate, $endDate])
                ->join('categories', 'transactions.category_id', '=', 'categories.id')
                ->select('categories.name', DB::raw('SUM(amount) as total'), DB::raw('COUNT(*) as count'))
                ->groupBy('categories.name', 'categories.id')
                ->get();

            // Get income data by category with count
            $incomeByCategory = Transaction::where('transactions.user_id', $userId)
                ->where('type', 'income')
                ->whereBetween('date', [$startDate, $endDate])
                ->join('categories', 'transactions.category_id', '=', 'categories.id')
                ->select('categories.name', DB::raw('SUM(amount) as total'), DB::raw('COUNT(*) as count'))
                ->groupBy('categories.name', 'categories.id')
                ->get();

            // FIXED: Get daily transactions with proper aggregation for trend chart
            $dailyTransactionsRaw = Transaction::where('user_id', $userId)
                ->whereBetween('date', [$startDate, $endDate])
                ->select('date', 'type', DB::raw('SUM(amount) as daily_total'))
                ->groupBy('date', 'type')
                ->orderBy('date')
                ->get();

            // DEBUG: Check what we got from the query
            Log::info('Daily Transactions Raw Query Result:', [
                'count' => $dailyTransactionsRaw->count(),
                'data' => $dailyTransactionsRaw->toArray()
            ]);

            // Group by date for easier processing
            $dailyTransactions = $dailyTransactionsRaw->groupBy('date');

            // DEBUG: Check grouped result
            Log::info('Daily Transactions After Grouping:', [
                'grouped_keys' => $dailyTransactions->keys()->toArray(),
                'grouped_count' => $dailyTransactions->count()
            ]);

            // Calculate totals for the selected period
            $totalIncome = Transaction::where('user_id', $userId)
                ->where('type', 'income')
                ->whereBetween('date', [$startDate, $endDate])
                ->sum('amount');

            $totalExpense = Transaction::where('user_id', $userId)
                ->where('type', 'expense')
                ->whereBetween('date', [$startDate, $endDate])
                ->sum('amount');

            // DEBUG: Check totals
            Log::info('Calculated Totals:', [
                'totalIncome' => $totalIncome,
                'totalExpense' => $totalExpense
            ]);

            // Calculate actual transaction count for the selected period
            $totalTransactionCount = Transaction::where('user_id', $userId)
                ->whereBetween('date', [$startDate, $endDate])
                ->count();

            // Calculate average transaction for the selected period
            $totalAmount = $totalIncome + $totalExpense;
            $averageTransaction = $totalTransactionCount > 0 ? $totalAmount / $totalTransactionCount : 0;
        }

        $balance = $totalIncome - $totalExpense;

        // Prepare chart data
        $chartData = $this->prepareChartData($expensesByCategory, $incomeByCategory, $dailyTransactions, $startDate, $endDate, $totalIncome, $totalExpense);

        return view('analytics', compact(
            'expensesByCategory',
            'incomeByCategory',
            'dailyTransactions',
            'totalIncome',
            'totalExpense',
            'balance',
            'totalTransactionCount',
            'averageTransaction',
            'startDate',
            'endDate',
            'chartData'
        ));
    }

    private function prepareChartData($expensesByCategory, $incomeByCategory, $dailyTransactions, $startDate, $endDate, $totalIncome, $totalExpense)
    {
        // Define color palettes
        $expenseColors = [
            '#EF4444', // Red - Food
                '#F97316', // Orange - Transport  
                '#F59E0B', // Amber - Shopping
                '#8B5CF6', // Purple - Entertainment
                '#EC4899', // Pink - Healthcare
                '#10B981', // Emerald - Bills
                '#3B82F6', // Blue - Education
                '#6366F1', // Indigo - Utilities
                '#84CC16', // Lime - Rent
                '#F472B6', // Rose - Gift
                '#06B6D4', // Cyan - Business
                '#8B5A2B', // Brown - Travel
                '#DC2626', // Dark Red
                '#EA580C', // Dark Orange
                '#D97706', // Dark Amber
                '#7C3AED', // Dark Purple
                '#DB2777', // Dark Pink
                '#059669', // Dark Emerald
                '#2563EB', // Dark Blue
                '#4F46E5'  // Dark Indigo
        ];

        $incomeColors = [
            '#22C55E', // Green - Salary
                '#16A34A', // Dark Green - Freelancing
                '#15803D', // Darker Green - Investment
                '#166534', // Forest Green - Business
                '#14532D', // Very Dark Green
                '#4ADE80', // Light Green
                '#86EFAC', // Lighter Green
                '#BBF7D0', // Very Light Green
                '#10B981', // Emerald
                '#059669', // Dark Emerald
                '#047857', // Darker Emerald
                '#065F46', // Forest Emerald
                '#064E3B', // Very Dark Emerald
                '#6EE7B7', // Light Emerald
                '#A7F3D0', // Lighter Emerald
                '#D1FAE5', // Very Light Emerald
                '#34D399', // Medium Emerald
                '#6BCF7F', // Custom Green 1
                '#52C41A', // Custom Green 2
                '#73D13D'  // Custom Green 3
        ];

        // Prepare expense pie data
        $expenseLabels = $expensesByCategory->pluck('name')->toArray();
        $expenseData = $expensesByCategory->pluck('total')->map(function ($item) {
            return (float) $item;
        })->toArray();

        // Prepare income pie data
        $incomeLabels = $incomeByCategory->pluck('name')->toArray();
        $incomeData = $incomeByCategory->pluck('total')->map(function ($item) {
            return (float) $item;
        })->toArray();

        // Prepare overall transaction pie data
        $overallData = [];
        $overallLabels = [];
        $overallColors = [];

        if ($totalIncome > 0) {
            $overallLabels[] = 'Total Income';
            $overallData[] = (float) $totalIncome;
            $overallColors[] = '#22C55E';
        }

        if ($totalExpense > 0) {
            $overallLabels[] = 'Total Expenses';
            $overallData[] = (float) $totalExpense;
            $overallColors[] = '#EF4444';
        }

        // ENHANCED: Determine if we should use monthly or daily aggregation
        $startCarbon = Carbon::parse($startDate);
        $endCarbon = Carbon::parse($endDate);
        $monthsDiff = $startCarbon->diffInMonths($endCarbon);
        $useMonthlyAggregation = $monthsDiff > 6;

        Log::info('Chart Aggregation Decision:', [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'monthsDiff' => $monthsDiff,
            'useMonthlyAggregation' => $useMonthlyAggregation
        ]);

        // Convert grouped daily transactions to use date-only keys
        $dailyTransactionsByDate = collect();
        foreach ($dailyTransactions as $dateTimeKey => $transactions) {
            $dateOnly = Carbon::parse($dateTimeKey)->format('Y-m-d');
            $dailyTransactionsByDate[$dateOnly] = $transactions;
        }

        // ENHANCED: Choose aggregation method based on time period
        if ($useMonthlyAggregation) {
            $trendData = $this->prepareMonthlyTrendData($dailyTransactionsByDate, $startDate, $endDate);
        } else {
            $trendData = $this->prepareDailyTrendData($dailyTransactionsByDate, $startDate, $endDate);
        }

        // Prepare category comparison bar chart data
        $allCategories = $expensesByCategory->pluck('name')
            ->merge($incomeByCategory->pluck('name'))
            ->unique()
            ->values()
            ->toArray();

        $categoryIncomeData = [];
        $categoryExpenseData = [];

        foreach ($allCategories as $category) {
            $incomeAmount = $incomeByCategory->where('name', $category)->first();
            $expenseAmount = $expensesByCategory->where('name', $category)->first();

            $categoryIncomeData[] = (float) ($incomeAmount ? $incomeAmount->total : 0);
            $categoryExpenseData[] = (float) ($expenseAmount ? $expenseAmount->total : 0);
        }

        return [
            'overallPie' => [
                'labels' => $overallLabels,
                'data' => $overallData,
                'colors' => $overallColors
            ],
            'expensePie' => [
                'labels' => $expenseLabels,
                'data' => $expenseData,
                'colors' => array_slice($expenseColors, 0, count($expenseLabels))
            ],
            'incomePie' => [
                'labels' => $incomeLabels,
                'data' => $incomeData,
                'colors' => array_slice($incomeColors, 0, count($incomeLabels))
            ],
            'trend' => [
                'labels' => $trendData['labels'],
                'income' => $trendData['income'],
                'expense' => $trendData['expense'],
                'aggregationType' => $useMonthlyAggregation ? 'monthly' : 'daily'
            ],
            'categoryBar' => [
                'labels' => $allCategories,
                'income' => $categoryIncomeData,
                'expense' => $categoryExpenseData
            ]
        ];
    }

    // NEW: Prepare daily trend data (for periods ≤ 6 months)
    private function prepareDailyTrendData($dailyTransactionsByDate, $startDate, $endDate)
    {
        $dateRange = [];
        $currentDate = Carbon::parse($startDate);
        $endDateCarbon = Carbon::parse($endDate);

        while ($currentDate <= $endDateCarbon) {
            $dateRange[] = $currentDate->format('Y-m-d');
            $currentDate->addDay();
        }

        $dailyIncomeData = [];
        $dailyExpenseData = [];
        $trendLabels = [];

        foreach ($dateRange as $date) {
            $dayData = $dailyTransactionsByDate->get($date, collect());

            $dailyIncome = 0;
            $dailyExpense = 0;

            foreach ($dayData as $transaction) {
                if ($transaction->type === 'income') {
                    $dailyIncome += (float) $transaction->daily_total;
                } elseif ($transaction->type === 'expense') {
                    $dailyExpense += (float) $transaction->daily_total;
                }
            }

            $dailyIncomeData[] = (float) $dailyIncome;
            $dailyExpenseData[] = (float) $dailyExpense;
            $trendLabels[] = Carbon::parse($date)->format('M j'); // "Jun 5"
        }

        Log::info('Daily Trend Data Prepared:', [
            'totalDays' => count($dateRange),
            'nonZeroIncome' => count(array_filter($dailyIncomeData)),
            'nonZeroExpense' => count(array_filter($dailyExpenseData)),
            'incomeSum' => array_sum($dailyIncomeData),
            'expenseSum' => array_sum($dailyExpenseData)
        ]);

        return [
            'labels' => $trendLabels,
            'income' => $dailyIncomeData,
            'expense' => $dailyExpenseData
        ];
    }

    // NEW: Prepare monthly trend data (for periods > 6 months)
    private function prepareMonthlyTrendData($dailyTransactionsByDate, $startDate, $endDate)
    {
        $startCarbon = Carbon::parse($startDate);
        $endCarbon = Carbon::parse($endDate);

        // Create month range
        $monthRange = [];
        $currentMonth = $startCarbon->copy()->startOfMonth();

        while ($currentMonth <= $endCarbon) {
            $monthKey = $currentMonth->format('Y-m');
            $monthRange[$monthKey] = [
                'income' => 0,
                'expense' => 0,
                'label' => $currentMonth->format('M Y'), // "Jun 2025"
                'start' => $currentMonth->copy()->startOfMonth()->format('Y-m-d'),
                'end' => $currentMonth->copy()->endOfMonth()->format('Y-m-d')
            ];
            $currentMonth->addMonth();
        }

        // Aggregate daily data into monthly
        foreach ($dailyTransactionsByDate as $date => $dayTransactions) {
            $monthKey = Carbon::parse($date)->format('Y-m');

            if (isset($monthRange[$monthKey])) {
                foreach ($dayTransactions as $transaction) {
                    if ($transaction->type === 'income') {
                        $monthRange[$monthKey]['income'] += (float) $transaction->daily_total;
                    } elseif ($transaction->type === 'expense') {
                        $monthRange[$monthKey]['expense'] += (float) $transaction->daily_total;
                    }
                }
            }
        }

        $trendLabels = [];
        $monthlyIncomeData = [];
        $monthlyExpenseData = [];

        foreach ($monthRange as $month) {
            $trendLabels[] = $month['label'];
            $monthlyIncomeData[] = (float) $month['income'];
            $monthlyExpenseData[] = (float) $month['expense'];
        }

        Log::info('Monthly Trend Data Prepared:', [
            'totalMonths' => count($monthRange),
            'nonZeroIncome' => count(array_filter($monthlyIncomeData)),
            'nonZeroExpense' => count(array_filter($monthlyExpenseData)),
            'incomeSum' => array_sum($monthlyIncomeData),
            'expenseSum' => array_sum($monthlyExpenseData),
            'monthLabels' => $trendLabels
        ]);

        return [
            'labels' => $trendLabels,
            'income' => $monthlyIncomeData,
            'expense' => $monthlyExpenseData
        ];
    }
}
