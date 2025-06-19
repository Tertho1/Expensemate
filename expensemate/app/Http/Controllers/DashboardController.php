<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Initialize with default values in case no transactions exist
        $monthlyIncome = 0;
        $monthlyExpense = 0;
        $totalIncome = 0;
        $totalExpense = 0;
        $balance = 0;
        $incomeChange = 0;
        $expenseChange = 0;
        $recentTransactions = collect();

        // Get current month data
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // Get monthly income
        $monthlyIncome = Transaction::where('user_id', $userId)
            ->where('type', 'income')
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        // Get monthly expense
        $monthlyExpense = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        // Get current balance (all time)
        $totalIncome = Transaction::where('user_id', $userId)
            ->where('type', 'income')
            ->sum('amount');

        $totalExpense = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->sum('amount');

        $balance = $totalIncome - $totalExpense;

        // Get recent transactions
        $recentTransactions = Transaction::with('category')
            ->where('user_id', $userId)
            ->orderBy('date', 'desc')
            ->limit(5)
            ->get();

        // Calculate month-over-month changes (optional)
        $lastMonth = Carbon::now()->subMonth();
        $startOfLastMonth = $lastMonth->copy()->startOfMonth();
        $endOfLastMonth = $lastMonth->copy()->endOfMonth();

        $lastMonthIncome = Transaction::where('user_id', $userId)
            ->where('type', 'income')
            ->whereBetween('date', [$startOfLastMonth, $endOfLastMonth])
            ->sum('amount');

        $lastMonthExpense = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->whereBetween('date', [$startOfLastMonth, $endOfLastMonth])
            ->sum('amount');

        $incomeChange = $lastMonthIncome > 0
            ? (($monthlyIncome - $lastMonthIncome) / $lastMonthIncome) * 100
            : 0;

        $expenseChange = $lastMonthExpense > 0
            ? (($monthlyExpense - $lastMonthExpense) / $lastMonthExpense) * 100
            : 0;

        return view('dashboard', compact(
            'monthlyIncome',
            'monthlyExpense',
            'balance',
            'recentTransactions',
            'incomeChange',
            'expenseChange',
            'totalIncome',
            'totalExpense'
        ));
    }
}
