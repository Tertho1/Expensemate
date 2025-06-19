<?php

namespace App\Http\Controllers;
use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        $transactions = Transaction::where('user_id', $user->id)
            ->with('category')
            ->latest()
            ->get();
            
        return view('transactions.index', compact('transactions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $userId = Auth::id();
        
        if (!$userId) {
            return redirect()->route('login');
        }
        
        $categories = Category::whereNull('user_id')
            ->orWhere('user_id', $userId)
            ->orderBy('type')
            ->orderBy('name')
            ->get()
            ->groupBy('type');

        return view('transactions.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $userId = Auth::id();
        
        if (!$userId) {
            return redirect()->route('login');
        }
        
        $validated = $request->validate([
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'category_id' => 'required|exists:categories,id',
            'note' => 'nullable|string',
        ]);
        
        // Add user_id to validated data
        $validated['user_id'] = $userId;
        
        // Create transaction directly
        Transaction::create($validated);
        
        return redirect()->route('transactions.index')->with('success', 'Transaction added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        $userId = Auth::id();
        
        if (!$userId) {
            return redirect()->route('login');
        }
        
        // Simple authorization - user must own the transaction
        if ($userId !== $transaction->user_id) {
            abort(403, 'Unauthorized action');
        }
        
        return view('transactions.show', compact('transaction'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        $userId = Auth::id();
        
        if (!$userId) {
            return redirect()->route('login');
        }
        
        // Simple authorization - user must own the transaction
        if ($userId !== $transaction->user_id) {
            abort(403, 'Unauthorized action');
        }

        // Get relevant categories (system default + user's custom)
        $categories = Category::whereNull('user_id')
            ->orWhere('user_id', $userId)
            ->orderBy('type')
            ->orderBy('name')
            ->get()
            ->groupBy('type');

        return view('transactions.edit', compact('transaction', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        $userId = Auth::id();
        
        if (!$userId) {
            return redirect()->route('login');
        }
        
        // Authorization check
        if ($userId !== $transaction->user_id) {
            abort(403, 'Unauthorized action');
        }

        $validated = $request->validate([
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'category_id' => 'required|exists:categories,id',
            'note' => 'nullable|string',
        ]);

        $transaction->update($validated);
        return redirect()->route('transactions.index')->with('success', 'Transaction updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        $userId = Auth::id();
        
        if (!$userId) {
            return redirect()->route('login');
        }
        
        // Authorization check
        if ($userId !== $transaction->user_id) {
            abort(403, 'Unauthorized action');
        }

        $transaction->delete();
        return redirect()->route('transactions.index')->with('success', 'Transaction deleted successfully.');
    }
}
