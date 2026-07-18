<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IncomeCategory;
use Illuminate\Http\Request;

class IncomeCategoryController extends Controller
{
    public function index()
    {
        $this->authorize('income_categories.view');

        $categories = IncomeCategory::orderBy('name')->get();

        return view('admin.accounting.categories.income', [
            'title' => 'Income Categories',
            'page_title' => 'Income Categories',
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('income_categories.create');

        $request->validate([
            'name' => 'required|string|max:255',
            'name_bn' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        IncomeCategory::create([
            'name' => $request->name,
            'name_bn' => $request->name_bn,
            'code' => IncomeCategory::generateCode(),
            'description' => $request->description,
            'created_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Income category added successfully.');
    }

    public function update(Request $request, IncomeCategory $category)
    {
        $this->authorize('income_categories.edit');

        $request->validate([
            'name' => 'required|string|max:255',
            'name_bn' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category->update($request->only(['name', 'name_bn', 'description']));

        return redirect()->back()->with('success', 'Income category updated successfully.');
    }

    public function toggleStatus(IncomeCategory $category)
    {
        $this->authorize('income_categories.edit');

        $category->update(['is_active' => !$category->is_active]);

        return redirect()->back()->with('success', 'Status updated successfully.');
    }

    public function destroy(IncomeCategory $category)
    {
        $this->authorize('income_categories.delete');

        if ($category->incomes()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete category with associated records.');
        }

        $category->delete();

        return redirect()->back()->with('success', 'Category deleted successfully.');
    }
}
