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
        $userId = Auth::id();

        $now = Carbon::now();
        $defaultStartDate = $now->copy()->startOfMonth()->format('Y-m-d');
        $defaultEndDate = $now->copy()->endOfMonth()->format('Y-m-d');

        $startDate = $request->input('start_date', $defaultStartDate);
        $endDate = $request->input('end_date', $defaultEndDate);

        $expensesByCategory = collect();
        $incomeByCategory = collect();
        $dailyTransactions = collect();
        $totalIncome = 0;
        $totalExpense = 0;
        $totalTransactionCount = 0;
        $averageTransaction = 0;

        if ($userId) {
            $expensesByCategory = Transaction::where('transactions.user_id', $userId)
                ->where('type', 'expense')
                ->whereBetween('date', [$startDate, $endDate])
                ->join('categories', 'transactions.category_id', '=', 'categories.id')
                ->select('categories.name', DB::raw('SUM(amount) as total'), DB::raw('COUNT(*) as count'))
                ->groupBy('categories.name', 'categories.id')
                ->get();

            $incomeByCategory = Transaction::where('transactions.user_id', $userId)
                ->where('type', 'income')
                ->whereBetween('date', [$startDate, $endDate])
                ->join('categories', 'transactions.category_id', '=', 'categories.id')
                ->select('categories.name', DB::raw('SUM(amount) as total'), DB::raw('COUNT(*) as count'))
                ->groupBy('categories.name', 'categories.id')
                ->get();

            $dailyTransactionsRaw = Transaction::where('user_id', $userId)
                ->whereBetween('date', [$startDate, $endDate])
                ->select('date', 'type', DB::raw('SUM(amount) as daily_total'))
                ->groupBy('date', 'type')
                ->orderBy('date')
                ->get();

            $dailyTransactions = $dailyTransactionsRaw->groupBy('date');

            $totalIncome = Transaction::where('user_id', $userId)
                ->where('type', 'income')
                ->whereBetween('date', [$startDate, $endDate])
                ->sum('amount');

            $totalExpense = Transaction::where('user_id', $userId)
                ->where('type', 'expense')
                ->whereBetween('date', [$startDate, $endDate])
                ->sum('amount');

            $totalTransactionCount = Transaction::where('user_id', $userId)
                ->whereBetween('date', [$startDate, $endDate])
                ->count();

            $totalAmount = $totalIncome + $totalExpense;
            $averageTransaction = $totalTransactionCount > 0 ? $totalAmount / $totalTransactionCount : 0;
        }

        $balance = $totalIncome - $totalExpense;

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
        $expenseColors = [
            '#EF4444', '#F97316', '#F59E0B', '#8B5CF6', '#EC4899', '#10B981', '#3B82F6', '#6366F1', '#84CC16', '#F472B6',
            '#06B6D4', '#8B5A2B', '#DC2626', '#EA580C', '#D97706', '#7C3AED', '#DB2777', '#059669', '#2563EB', '#4F46E5'
        ];

        $incomeColors = [
            '#22C55E', '#16A34A', '#15803D', '#166534', '#14532D', '#4ADE80', '#86EFAC', '#BBF7D0', '#10B981', '#059669',
            '#047857', '#065F46', '#064E3B', '#6EE7B7', '#A7F3D0', '#D1FAE5', '#34D399', '#6BCF7F', '#52C41A', '#73D13D'
        ];

        $expenseLabels = $expensesByCategory->pluck('name')->toArray();
        $expenseData = $expensesByCategory->pluck('total')->map(function ($item) {
            return (float) $item;
        })->toArray();

        $incomeLabels = $incomeByCategory->pluck('name')->toArray();
        $incomeData = $incomeByCategory->pluck('total')->map(function ($item) {
            return (float) $item;
        })->toArray();

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

        $startCarbon = Carbon::parse($startDate);
        $endCarbon = Carbon::parse($endDate);
        $monthsDiff = $startCarbon->diffInMonths($endCarbon);
        $useMonthlyAggregation = $monthsDiff > 6;

        $dailyTransactionsByDate = collect();
        foreach ($dailyTransactions as $dateTimeKey => $transactions) {
            $dateOnly = Carbon::parse($dateTimeKey)->format('Y-m-d');
            $dailyTransactionsByDate[$dateOnly] = $transactions;
        }

        if ($useMonthlyAggregation) {
            $trendData = $this->prepareMonthlyTrendData($dailyTransactionsByDate, $startDate, $endDate);
        } else {
            $trendData = $this->prepareDailyTrendData($dailyTransactionsByDate, $startDate, $endDate);
        }

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
            $trendLabels[] = Carbon::parse($date)->format('M j');
        }

        return [
            'labels' => $trendLabels,
            'income' => $dailyIncomeData,
            'expense' => $dailyExpenseData
        ];
    }

    private function prepareMonthlyTrendData($dailyTransactionsByDate, $startDate, $endDate)
    {
        $startCarbon = Carbon::parse($startDate);
        $endCarbon = Carbon::parse($endDate);

        $monthRange = [];
        $currentMonth = $startCarbon->copy()->startOfMonth();

        while ($currentMonth <= $endCarbon) {
            $monthKey = $currentMonth->format('Y-m');
            $monthRange[$monthKey] = [
                'income' => 0,
                'expense' => 0,
                'label' => $currentMonth->format('M Y'),
                'start' => $currentMonth->copy()->startOfMonth()->format('Y-m-d'),
                'end' => $currentMonth->copy()->endOfMonth()->format('Y-m-d')
            ];
            $currentMonth->addMonth();
        }

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

        return [
            'labels' => $trendLabels,
            'income' => $monthlyIncomeData,
            'expense' => $monthlyExpenseData
        ];
    }
}
