<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Ledger;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('expenses.view');

        $query = Expense::with(['category', 'creator']);

        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        if ($request->has('search') && $request->search) {
            $query->where('voucher_no', 'like', '%' . $request->search . '%');
        }

        $expenses = $query->orderBy('date', 'desc')->paginate(20);

        $data = [
            'title' => 'Expense Management',
            'page_title' => 'Expense List',
            'expenses' => $expenses,
            'categories' => ExpenseCategory::active()->orderBy('name')->get(),
            'stats' => $this->getStats($request),
        ];

        return view('admin.accounting.expenses.index', $data);
    }

    protected function getStats($request)
    {
        $query = Expense::query();

        if ($request->has('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        return [
            'total' => $query->sum('amount'),
            'count' => $query->count(),
        ];
    }

    public function create()
    {
        $this->authorize('expenses.create');

        $data = [
            'title' => 'Add Expense',
            'page_title' => 'Create Expense Voucher',
            'categories' => ExpenseCategory::active()->orderBy('name')->get(),
            'expense' => null,
        ];

        return view('admin.accounting.expenses.create', $data);
    }

    public function store(Request $request)
    {
        $this->authorize('expenses.create');

        $request->validate([
            'category_id' => 'required|exists:expense_categories,id',
            'payee_name' => 'nullable|string|max:255',
            'member_id' => 'nullable|exists:members,id',
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|string',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'reference_no' => 'nullable|string|max:255',
        ]);

        $expense = Expense::create([
            'voucher_no' => Expense::generateVoucherNo(),
            'category_id' => $request->category_id,
            'payee_name' => $request->payee_name,
            'member_id' => $request->member_id,
            'amount' => $request->amount,
            'currency' => 'SAR',
            'payment_method' => $request->payment_method,
            'description' => $request->description,
            'date' => $request->date,
            'reference_no' => $request->reference_no,
            'approved_by' => auth()->id(),
            'created_by' => auth()->id(),
        ]);

        // Create ledger entry
        Ledger::create([
            'date' => $request->date,
            'voucher_no' => $expense->voucher_no,
            'voucher_type' => 'payment',
            'account_type' => 'expense',
            'account_id' => $request->category_id,
            'description' => $expense->description,
            'debit' => $request->amount,
            'balance' => 0,
            'reference_type' => Expense::class,
            'reference_id' => $expense->id,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('admin.expenses.index')
            ->with('success', 'Expense voucher created successfully.');
    }

    public function show(Expense $expense)
    {
        $this->authorize('expenses.view');

        $expense->load(['category', 'creator', 'approver']);

        return view('admin.accounting.expenses.show', [
            'title' => 'Expense Details',
            'page_title' => 'Voucher: ' . $expense->voucher_no,
            'expense' => $expense,
        ]);
    }

    public function edit(Expense $expense)
    {
        $this->authorize('expenses.edit');

        return view('admin.accounting.expenses.edit', [
            'title' => 'Edit Expense',
            'page_title' => 'Edit Voucher: ' . $expense->voucher_no,
            'expense' => $expense,
            'categories' => ExpenseCategory::active()->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Expense $expense)
    {
        $this->authorize('expenses.edit');

        $request->validate([
            'category_id' => 'required|exists:expense_categories,id',
            'payee_name' => 'nullable|string|max:255',
            'member_id' => 'nullable|exists:members,id',
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|string',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'reference_no' => 'nullable|string|max:255',
        ]);

        $expense->update($request->only([
            'category_id', 'payee_name', 'member_id', 'amount', 
            'payment_method', 'description', 'date', 'reference_no'
        ]));

        // Update ledger entry
        Ledger::where('reference_type', Expense::class)
            ->where('reference_id', $expense->id)
            ->update([
                'date' => $request->date,
                'account_id' => $request->category_id,
                'description' => $request->description,
                'debit' => $request->amount,
            ]);

        return redirect()->route('admin.expenses.show', $expense)
            ->with('success', 'Expense voucher updated successfully.');
    }

    public function destroy(Expense $expense)
    {
        $this->authorize('expenses.delete');

        // Delete ledger entry
        Ledger::where('reference_type', Expense::class)
            ->where('reference_id', $expense->id)
            ->delete();

        $expense->delete();

        return redirect()->route('admin.expenses.index')
            ->with('success', 'Expense voucher deleted successfully.');
    }
}
