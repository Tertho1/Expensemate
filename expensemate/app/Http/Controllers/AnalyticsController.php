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

        // Set default date range (current month)
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

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

            // Group by date for easier processing
            $dailyTransactions = $dailyTransactionsRaw->groupBy('date');

            // Calculate totals for the selected period
            $totalIncome = Transaction::where('user_id', $userId)
                ->where('type', 'income')
                ->whereBetween('date', [$startDate, $endDate])
                ->sum('amount');

            $totalExpense = Transaction::where('user_id', $userId)
                ->where('type', 'expense')
                ->whereBetween('date', [$startDate, $endDate])
                ->sum('amount');

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
            '#EF4444',
            '#DC2626',
            '#B91C1C',
            '#991B1B',
            '#7F1D1D',
            '#F87171',
            '#FCA5A5',
            '#FEB2B2',
            '#FECACA',
            '#FEE2E2'
        ];

        $incomeColors = [
            '#22C55E',
            '#16A34A',
            '#15803D',
            '#166534',
            '#14532D',
            '#4ADE80',
            '#86EFAC',
            '#BBF7D0',
            '#D1FAE5',
            '#ECFDF5'
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

        // FIXED: Prepare daily trend data with complete date range
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
            $dayData = $dailyTransactions->get($date, collect());

            // Get income and expense for this specific day
            $dailyIncome = $dayData->where('type', 'income')->sum('daily_total') ?: 0;
            $dailyExpense = $dayData->where('type', 'expense')->sum('daily_total') ?: 0;

            $dailyIncomeData[] = (float) $dailyIncome;
            $dailyExpenseData[] = (float) $dailyExpense;
            $trendLabels[] = Carbon::parse($date)->format('M j'); // Short format for labels
        }

        // Log for debugging
        Log::info('Daily Trend Data:', [
            'dateRange' => $dateRange,
            'dailyIncomeData' => $dailyIncomeData,
            'dailyExpenseData' => $dailyExpenseData,
            'trendLabels' => $trendLabels
        ]);

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
                'labels' => $trendLabels,
                'income' => $dailyIncomeData,
                'expense' => $dailyExpenseData
            ],
            'categoryBar' => [
                'labels' => $allCategories,
                'income' => $categoryIncomeData,
                'expense' => $categoryExpenseData
            ]
        ];
    }
}
