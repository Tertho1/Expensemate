<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        
        // Get current month data
        $currentMonth = Carbon::now();
        $startOfMonth = $currentMonth->copy()->startOfMonth();
        $endOfMonth = $currentMonth->copy()->endOfMonth();
        
        // Get this month's transactions
        $monthlyIncome = Transaction::where('user_id', $userId)
            ->where('type', 'income')
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->sum('amount') ?? 0;
            
        $monthlyExpense = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->sum('amount') ?? 0;
            
        // Calculate monthly balance
        $monthlyBalance = $monthlyIncome - $monthlyExpense;
            
        // Get recent transactions (last 5 transactions overall, not just this month)
        $recentTransactions = Transaction::where('user_id', $userId)
            ->with('category')
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        // Get categories for quick actions
        $categories = Category::orderBy('name')->get();
        
        // Get total transaction count for this month
        $totalTransactionCount = Transaction::where('user_id', $userId)
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->count();
        
        return view('dashboard', compact(
            'monthlyIncome',
            'monthlyExpense', 
            'monthlyBalance',
            'recentTransactions',
            'categories',
            'totalTransactionCount'
        ));
    }
}
