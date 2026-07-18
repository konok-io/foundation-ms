<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;

class ExpenseCategoryController extends Controller
{
    public function index()
    {
        $this->authorize('expense_categories.view');

        $categories = ExpenseCategory::orderBy('name')->get();

        return view('admin.accounting.categories.expense', [
            'title' => 'Expense Categories',
            'page_title' => 'Expense Categories',
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('expense_categories.create');

        $request->validate([
            'name' => 'required|string|max:255',
            'name_bn' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        ExpenseCategory::create([
            'name' => $request->name,
            'name_bn' => $request->name_bn,
            'code' => ExpenseCategory::generateCode(),
            'description' => $request->description,
            'created_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Expense category added successfully.');
    }

    public function update(Request $request, ExpenseCategory $category)
    {
        $this->authorize('expense_categories.edit');

        $request->validate([
            'name' => 'required|string|max:255',
            'name_bn' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category->update($request->only(['name', 'name_bn', 'description']));

        return redirect()->back()->with('success', 'Expense category updated successfully.');
    }

    public function toggleStatus(ExpenseCategory $category)
    {
        $this->authorize('expense_categories.edit');

        $category->update(['is_active' => !$category->is_active]);

        return redirect()->back()->with('success', 'Status updated successfully.');
    }

    public function destroy(ExpenseCategory $category)
    {
        $this->authorize('expense_categories.delete');

        if ($category->expenses()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete category with associated records.');
        }

        $category->delete();

        return redirect()->back()->with('success', 'Category deleted successfully.');
    }
}
