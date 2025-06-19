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

        if ($userId) {
            // Get expense data by category
            $expensesByCategory = Transaction::where('user_id', $userId)
                ->where('type', 'expense')
                ->whereBetween('date', [$startDate, $endDate])
                ->join('categories', 'transactions.category_id', '=', 'categories.id')
                ->select('categories.name', DB::raw('SUM(amount) as total'))
                ->groupBy('categories.name')
                ->get();

            // Get income data by category
            $incomeByCategory = Transaction::where('user_id', $userId)
                ->where('type', 'income')
                ->whereBetween('date', [$startDate, $endDate])
                ->join('categories', 'transactions.category_id', '=', 'categories.id')
                ->select('categories.name', DB::raw('SUM(amount) as total'))
                ->groupBy('categories.name')
                ->get();

            // Get daily transactions for the selected period
            $dailyTransactions = Transaction::where('user_id', $userId)
                ->whereBetween('date', [$startDate, $endDate])
                ->select('date', 'type', DB::raw('SUM(amount) as total'))
                ->groupBy('date', 'type')
                ->orderBy('date')
                ->get()
                ->groupBy('date');

            // Total income, expense and balance
            $totalIncome = Transaction::where('user_id', $userId)
                ->where('type', 'income')
                ->whereBetween('date', [$startDate, $endDate])
                ->sum('amount');

            $totalExpense = Transaction::where('user_id', $userId)
                ->where('type', 'expense')
                ->whereBetween('date', [$startDate, $endDate])
                ->sum('amount');
        }

        $balance = $totalIncome - $totalExpense;

        return view('analytics', compact(
            'expensesByCategory',
            'incomeByCategory',
            'dailyTransactions',
            'totalIncome',
            'totalExpense',
            'balance',
            'startDate',
            'endDate'
        ));
    }
}
