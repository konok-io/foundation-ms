<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Income;
use App\Models\Expense;
use App\Models\IncomeCategory;
use App\Models\ExpenseCategory;
use App\Models\Ledger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LedgerController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('ledger.view');

        $dateFrom = $request->date_from ?? date('Y-01-01');
        $dateTo = $request->date_to ?? date('Y-m-d');

        // Get all ledger entries
        $entries = Ledger::whereBetween('date', [$dateFrom, $dateTo])
            ->orderBy('date')
            ->orderBy('id')
            ->get();

        // Calculate running balance
        $openingBalance = $this->getOpeningBalance($dateFrom);
        $balance = $openingBalance;
        
        $entries->transform(function ($entry) use (&$balance) {
            if ($entry->credit > 0) {
                $balance += $entry->credit;
            }
            if ($entry->debit > 0) {
                $balance -= $entry->debit;
            }
            $entry->running_balance = $balance;
            return $entry;
        });

        $closingBalance = $balance;

        $data = [
            'title' => 'Cash Book',
            'page_title' => 'Cash Book',
            'entries' => $entries,
            'openingBalance' => $openingBalance,
            'closingBalance' => $closingBalance,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'incomeTotal' => Ledger::whereBetween('date', [$dateFrom, $dateTo])->sum('credit'),
            'expenseTotal' => Ledger::whereBetween('date', [$dateFrom, $dateTo])->sum('debit'),
        ];

        return view('admin.accounting.ledger.index', $data);
    }

    protected function getOpeningBalance($dateFrom)
    {
        $previousEntries = Ledger::where('date', '<', $dateFrom)->get();
        
        $income = $previousEntries->sum('credit');
        $expense = $previousEntries->sum('debit');
        
        return $income - $expense;
    }

    public function accountLedger(Request $request, string $type, int $id)
    {
        $this->authorize('ledger.view');

        $dateFrom = $request->date_from ?? date('Y-01-01');
        $dateTo = $request->date_to ?? date('Y-m-d');

        if ($type === 'income') {
            $category = IncomeCategory::findOrFail($id);
            $entries = Ledger::where('account_type', 'income')
                ->where('account_id', $id)
                ->whereBetween('date', [$dateFrom, $dateTo])
                ->orderBy('date')
                ->orderBy('id')
                ->get();
            $title = 'Income Ledger: ' . $category->name;
        } else {
            $category = ExpenseCategory::findOrFail($id);
            $entries = Ledger::where('account_type', 'expense')
                ->where('account_id', $id)
                ->whereBetween('date', [$dateFrom, $dateTo])
                ->orderBy('date')
                ->orderBy('id')
                ->get();
            $title = 'Expense Ledger: ' . $category->name;
        }

        $data = [
            'title' => $title,
            'page_title' => $title,
            'category' => $category,
            'type' => $type,
            'entries' => $entries,
            'totalDebit' => $entries->sum('debit'),
            'totalCredit' => $entries->sum('credit'),
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
        ];

        return view('admin.accounting.ledger.account', $data);
    }

    public function incomeStatement(Request $request)
    {
        $this->authorize('reports.view');

        $dateFrom = $request->date_from ?? date('Y-01-01');
        $dateTo = $request->date_to ?? date('Y-m-d');

        // Get income by category
        $incomes = DB::table('incomes')
            ->join('income_categories', 'incomes.category_id', '=', 'income_categories.id')
            ->whereBetween('incomes.date', [$dateFrom, $dateTo])
            ->groupBy('income_categories.id', 'income_categories.name')
            ->select('income_categories.name', DB::raw('SUM(incomes.amount) as total'))
            ->get();

        // Get expenses by category
        $expenses = DB::table('expenses')
            ->join('expense_categories', 'expenses.category_id', '=', 'expense_categories.id')
            ->whereBetween('expenses.date', [$dateFrom, $dateTo])
            ->groupBy('expense_categories.id', 'expense_categories.name')
            ->select('expense_categories.name', DB::raw('SUM(expenses.amount) as total'))
            ->get();

        $totalIncome = $incomes->sum('total');
        $totalExpense = $expenses->sum('total');
        $netProfit = $totalIncome - $totalExpense;

        $data = [
            'title' => 'Income Statement',
            'page_title' => 'Income Statement',
            'incomes' => $incomes,
            'expenses' => $expenses,
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'netProfit' => $netProfit,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
        ];

        return view('admin.accounting.reports.income-statement', $data);
    }

    public function export(Request $request)
    {
        $this->authorize('ledger.export');

        $dateFrom = $request->date_from ?? date('Y-01-01');
        $dateTo = $request->date_to ?? date('Y-m-d');

        $entries = Ledger::whereBetween('date', [$dateFrom, $dateTo])
            ->orderBy('date')
            ->orderBy('id')
            ->get();

        return response()->streamDownload(function () use ($entries) {
            $handle = fopen('php://output', 'w');
            
            fputcsv($handle, ['Date', 'Voucher No', 'Type', 'Description', 'Debit', 'Credit', 'Balance']);

            $balance = 0;
            foreach ($entries as $entry) {
                if ($entry->credit > 0) {
                    $balance += $entry->credit;
                }
                if ($entry->debit > 0) {
                    $balance -= $entry->debit;
                }
                
                fputcsv($handle, [
                    $entry->date->format('Y-m-d'),
                    $entry->voucher_no,
                    $entry->voucher_type,
                    $entry->description,
                    $entry->debit,
                    $entry->credit,
                    $balance,
                ]);
            }

            fclose($handle);
        }, 'ledger-' . date('Y-m-d') . '.csv', ['Content-Type' => 'text/csv']);
    }
}
