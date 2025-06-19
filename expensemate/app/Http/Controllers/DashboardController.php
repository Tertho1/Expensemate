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

        if (!$userId) {
            return redirect()->route('login');
        }

        // Initialize with default values
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
            ->sum('amount') ?? 0;

        // Get monthly expense
        $monthlyExpense = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->sum('amount') ?? 0;

        // Get current balance (all time)
        $totalIncome = Transaction::where('user_id', $userId)
            ->where('type', 'income')
            ->sum('amount') ?? 0;

        $totalExpense = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->sum('amount') ?? 0;

        $balance = $totalIncome - $totalExpense;

        // Get recent transactions
        $recentTransactions = Transaction::with('category')
            ->where('user_id', $userId)
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Calculate month-over-month changes
        $lastMonth = Carbon::now()->subMonth();
        $startOfLastMonth = $lastMonth->copy()->startOfMonth();
        $endOfLastMonth = $lastMonth->copy()->endOfMonth();

        $lastMonthIncome = Transaction::where('user_id', $userId)
            ->where('type', 'income')
            ->whereBetween('date', [$startOfLastMonth, $endOfLastMonth])
            ->sum('amount') ?? 0;

        $lastMonthExpense = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->whereBetween('date', [$startOfLastMonth, $endOfLastMonth])
            ->sum('amount') ?? 0;

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
