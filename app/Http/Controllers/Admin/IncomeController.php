<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Income;
use App\Models\IncomeCategory;
use App\Models\Ledger;
use Illuminate\Http\Request;

class IncomeController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('incomes.view');

        $query = Income::with(['category', 'creator']);

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

        $incomes = $query->orderBy('date', 'desc')->paginate(20);

        $data = [
            'title' => 'Income Management',
            'page_title' => 'Income List',
            'incomes' => $incomes,
            'categories' => IncomeCategory::active()->orderBy('name')->get(),
            'stats' => $this->getStats($request),
        ];

        return view('admin.accounting.incomes.index', $data);
    }

    protected function getStats($request)
    {
        $query = Income::query();

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
        $this->authorize('incomes.create');

        $data = [
            'title' => 'Add Income',
            'page_title' => 'Create Income Voucher',
            'categories' => IncomeCategory::active()->orderBy('name')->get(),
            'income' => null,
        ];

        return view('admin.accounting.incomes.create', $data);
    }

    public function store(Request $request)
    {
        $this->authorize('incomes.create');

        $request->validate([
            'category_id' => 'required|exists:income_categories,id',
            'member_id' => 'nullable|exists:members,id',
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|string',
            'received_from' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'reference_no' => 'nullable|string|max:255',
        ]);

        $income = Income::create([
            'voucher_no' => Income::generateVoucherNo(),
            'category_id' => $request->category_id,
            'member_id' => $request->member_id,
            'amount' => $request->amount,
            'currency' => 'SAR',
            'payment_method' => $request->payment_method,
            'received_from' => $request->received_from,
            'description' => $request->description,
            'date' => $request->date,
            'reference_no' => $request->reference_no,
            'created_by' => auth()->id(),
        ]);

        // Create ledger entry
        Ledger::create([
            'date' => $request->date,
            'voucher_no' => $income->voucher_no,
            'voucher_type' => 'receipt',
            'account_type' => 'income',
            'account_id' => $request->category_id,
            'description' => $income->description,
            'credit' => $request->amount,
            'balance' => 0,
            'reference_type' => Income::class,
            'reference_id' => $income->id,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('admin.incomes.index')
            ->with('success', 'Income voucher created successfully.');
    }

    public function show(Income $income)
    {
        $this->authorize('incomes.view');

        $income->load(['category', 'creator']);

        return view('admin.accounting.incomes.show', [
            'title' => 'Income Details',
            'page_title' => 'Voucher: ' . $income->voucher_no,
            'income' => $income,
        ]);
    }

    public function edit(Income $income)
    {
        $this->authorize('incomes.edit');

        return view('admin.accounting.incomes.edit', [
            'title' => 'Edit Income',
            'page_title' => 'Edit Voucher: ' . $income->voucher_no,
            'income' => $income,
            'categories' => IncomeCategory::active()->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Income $income)
    {
        $this->authorize('incomes.edit');

        $request->validate([
            'category_id' => 'required|exists:income_categories,id',
            'member_id' => 'nullable|exists:members,id',
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|string',
            'received_from' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'reference_no' => 'nullable|string|max:255',
        ]);

        $income->update($request->only([
            'category_id', 'member_id', 'amount', 'payment_method',
            'received_from', 'description', 'date', 'reference_no'
        ]));

        // Update ledger entry
        Ledger::where('reference_type', Income::class)
            ->where('reference_id', $income->id)
            ->update([
                'date' => $request->date,
                'account_id' => $request->category_id,
                'description' => $request->description,
                'credit' => $request->amount,
            ]);

        return redirect()->route('admin.incomes.show', $income)
            ->with('success', 'Income voucher updated successfully.');
    }

    public function destroy(Income $income)
    {
        $this->authorize('incomes.delete');

        // Delete ledger entry
        Ledger::where('reference_type', Income::class)
            ->where('reference_id', $income->id)
            ->delete();

        $income->delete();

        return redirect()->route('admin.incomes.index')
            ->with('success', 'Income voucher deleted successfully.');
    }
}
