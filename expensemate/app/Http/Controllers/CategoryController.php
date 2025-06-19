<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    //
    public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'type' => 'required|in:income,expense',
    ]);

    $category = Category::create([
        'name' => $validated['name'],
        'type' => $validated['type'],
        'user_id' => Auth::id(),

    ]);

    if ($request->expectsJson()) {
        return response()->json(['success' => true, 'category' => $category]);
    }

    return redirect()->route('categories.index')->with('success', 'Category added!');
}

}
