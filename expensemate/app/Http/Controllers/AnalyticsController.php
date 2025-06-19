<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

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

            // Get daily transactions for trend chart
            $dailyTransactions = Transaction::where('user_id', $userId)
                ->whereBetween('date', [$startDate, $endDate])
                ->select('date', 'type', DB::raw('SUM(amount) as total'))
                ->groupBy('date', 'type')
                ->orderBy('date')
                ->get()
                ->groupBy('date');

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

        // Prepare chart data - ALWAYS return valid data structure
        $chartData = $this->prepareChartData($expensesByCategory, $incomeByCategory, $dailyTransactions, $startDate, $endDate);

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

    private function prepareChartData($expensesByCategory, $incomeByCategory, $dailyTransactions, $startDate, $endDate)
    {
        // Ensure we always have arrays, never null
        $expenseLabels = $expensesByCategory->pluck('name')->toArray();
        $expenseData = $expensesByCategory->pluck('total')->map(function ($item) {
            return (float) $item;
        })->toArray();

        $incomeLabels = $incomeByCategory->pluck('name')->toArray();
        $incomeData = $incomeByCategory->pluck('total')->map(function ($item) {
            return (float) $item;
        })->toArray();

        // Prepare daily trend data
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
            $dailyIncomeData[] = (float) ($dayData->where('type', 'income')->sum('total') ?: 0);
            $dailyExpenseData[] = (float) ($dayData->where('type', 'expense')->sum('total') ?: 0);
            $trendLabels[] = Carbon::parse($date)->format('M j');
        }

        // Prepare bar chart data (category comparison)
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

        // Return structured data that JavaScript can safely parse
        return [
            'expensePie' => [
                'labels' => $expenseLabels,
                'data' => $expenseData,
                'colors' => [
                    '#EF4444',
                    '#F97316',
                    '#F59E0B',
                    '#EAB308',
                    '#84CC16',
                    '#22C55E',
                    '#10B981',
                    '#14B8A6',
                    '#06B6D4',
                    '#0EA5E9',
                    '#3B82F6',
                    '#6366F1',
                    '#8B5CF6',
                    '#A855F7',
                    '#D946EF'
                ]
            ],
            'incomePie' => [
                'labels' => $incomeLabels,
                'data' => $incomeData,
                'colors' => [
                    '#22C55E',
                    '#16A34A',
                    '#15803D',
                    '#166534',
                    '#14532D',
                    '#10B981',
                    '#059669',
                    '#047857',
                    '#065F46',
                    '#064E3B',
                    '#84CC16',
                    '#65A30D',
                    '#4D7C0F',
                    '#365314',
                    '#1A2E05'
                ]
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
