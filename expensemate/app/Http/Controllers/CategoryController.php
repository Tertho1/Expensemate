<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Get both global categories (user_id = null) and user's custom categories
        $categories = Category::where(function ($query) use ($userId) {
            $query->whereNull('user_id')
                ->orWhere('user_id', $userId);
        })
            ->orderBy('name')
            ->get();

        return view('categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        try {
            $userId = Auth::id();

            $validated = $request->validate([
                'name' => 'required|string|max:255',
            ]);

            // Check if category already exists (case-insensitive)
            $existingCategory = Category::where(function ($query) use ($userId) {
                $query->whereNull('user_id')
                    ->orWhere('user_id', $userId);
            })
                ->whereRaw('LOWER(name) = LOWER(?)', [$validated['name']])
                ->first();

            if ($existingCategory) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Category "' . $validated['name'] . '" already exists!'
                    ], 422);
                }

                return back()->withErrors(['name' => 'Category already exists!']);
            }

            $category = Category::create([
                'name' => $validated['name'],
                'user_id' => $userId,
            ]);

            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'category' => $category]);
            }

            return redirect()->route('categories.index')->with('success', 'Category added successfully!');

        } catch (\Exception $e) {
            Log::error('Category creation failed: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Failed to create category: ' . $e->getMessage()], 500);
            }

            return back()->with('error', 'Failed to create category');
        }
    }

    public function destroy(Category $category)
    {
        $userId = Auth::id();

        // Only allow deletion of user's own categories (not global ones)
        if ($category->user_id !== $userId) {
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'You can only delete your own categories'], 403);
            }

            return back()->withErrors(['error' => 'You can only delete your own categories']);
        }

        // Check if category is being used in any transactions
        $transactionCount = $category->transactions()->count();

        if ($transactionCount > 0) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => "Cannot delete category. It's being used in {$transactionCount} transaction(s)."
                ], 422);
            }

            return back()->withErrors(['error' => "Cannot delete category. It's being used in {$transactionCount} transaction(s)."]);
        }

        $category->delete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Category deleted successfully']);
        }

        return redirect()->route('categories.index')->with('success', 'Category deleted successfully!');
    }
}
