<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContributionStoreRequest;
use App\Http\Requests\ContributionUpdateRequest;
use App\Models\Member;
use App\Models\MonthlyContribution;
use App\Models\GeneralSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ContributionController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('contributions.view');

        $query = MonthlyContribution::with('member');

        if ($request->has('year') && $request->year) {
            $query->where('year', $request->year);
        } else {
            $query->where('year', date('Y'));
        }

        if ($request->has('month') && $request->month) {
            $query->where('month', $request->month);
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('search') && $request->search) {
            $query->whereHas('member', function ($q) use ($request) {
                $q->search($request->search);
            });
        }

        $contributions = $query->orderBy('created_at', 'desc')->paginate(20);

        $data = [
            'title' => 'Monthly Contributions',
            'page_title' => 'Contribution Management',
            'contributions' => $contributions,
            'statuses' => MonthlyContribution::STATUSES,
            'years' => range(date('Y') - 5, date('Y') + 1),
            'months' => range(1, 12),
            'stats' => $this->getStats($request->year ?? date('Y')),
        ];

        return view('admin.contributions.index', $data);
    }

    protected function getStats($year)
    {
        $totalMembers = Member::active()->count();
        
        $yearContributions = MonthlyContribution::where('year', $year)->get();
        
        $totalExpected = $yearContributions->sum('amount');
        $totalCollected = $yearContributions->where('status', 'paid')->sum('paid_amount');
        $totalDue = $yearContributions->whereIn('status', ['pending', 'partial', 'overdue'])->sum('due_amount');
        
        $paidCount = $yearContributions->where('status', 'paid')->count();
        $pendingCount = $yearContributions->where('status', 'pending')->count();
        $overdueQuery = MonthlyContribution::where('year', $year)->overdue();
        $overdueCount = $overdueQuery->count();

        return [
            'total_members' => $totalMembers,
            'total_expected' => $totalExpected,
            'total_collected' => $totalCollected,
            'total_due' => $totalDue,
            'collection_rate' => $totalExpected > 0 ? round(($totalCollected / $totalExpected) * 100, 1) : 0,
            'paid_count' => $paidCount,
            'pending_count' => $pendingCount,
            'overdue_count' => $overdueCount,
        ];
    }

    public function create(Request $request)
    {
        $this->authorize('contributions.create');

        $data = [
            'title' => 'Add Contribution',
            'page_title' => 'Create Contribution',
            'members' => Member::active()->orderBy('name')->get(),
            'statuses' => MonthlyContribution::STATUSES,
            'paymentMethods' => MonthlyContribution::PAYMENT_METHODS,
            'defaultAmount' => GeneralSetting::getSetting('default_contribution_amount', 100),
            'years' => range(date('Y') - 5, date('Y') + 1),
            'months' => range(1, 12),
        ];

        return view('admin.contributions.create', $data);
    }

    public function store(ContributionStoreRequest $request)
    {
        $this->authorize('contributions.create');

        try {
            DB::beginTransaction();

            $member = Member::findOrFail($request->member_id);
            
            // Check if contribution already exists
            $existing = MonthlyContribution::where('member_id', $request->member_id)
                ->where('year', $request->year)
                ->where('month', $request->month)
                ->first();

            if ($existing) {
                return redirect()->back()->with('error', 'Contribution already exists for this member for the selected period.')->withInput();
            }

            $dueDate = $request->due_date ?? date('Y-m-d', strtotime($request->year . '-' . str_pad($request->month, 2, '0', STR_PAD_LEFT) . '-28'));
            
            $isPaid = $request->boolean('is_paid');
            $paidAmount = $isPaid ? $request->amount : 0;
            
            $contribution = MonthlyContribution::create([
                'member_id' => $request->member_id,
                'year' => $request->year,
                'month' => $request->month,
                'amount' => $request->amount,
                'paid_amount' => $paidAmount,
                'due_amount' => $request->amount - $paidAmount,
                'due_date' => $dueDate,
                'paid_date' => $isPaid ? now() : null,
                'status' => $isPaid ? 'paid' : 'pending',
                'payment_method' => $isPaid ? $request->payment_method : null,
                'transaction_id' => $isPaid ? $request->transaction_id : null,
                'receipt_no' => $isPaid ? MonthlyContribution::generateReceiptNo() : null,
                'notes' => $request->notes,
                'created_by' => auth()->id(),
                'paid_by' => $isPaid ? auth()->id() : null,
            ]);

            DB::commit();

            Log::info('Contribution created', [
                'id' => $contribution->id,
                'member_id' => $member->member_id,
                'amount' => $request->amount,
                'created_by' => auth()->id()
            ]);

            return redirect()->route('admin.contributions.index')->with('success', 'Contribution added successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Contribution creation failed', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to add contribution: ' . $e->getMessage())->withInput();
        }
    }

    public function show(MonthlyContribution $contribution)
    {
        $this->authorize('contributions.view');

        $data = [
            'title' => 'Contribution Details',
            'page_title' => 'Contribution Details',
            'contribution' => $contribution->load(['member', 'creator', 'payer']),
            'statuses' => MonthlyContribution::STATUSES,
            'paymentMethods' => MonthlyContribution::PAYMENT_METHODS,
        ];

        return view('admin.contributions.show', $data);
    }

    public function edit(MonthlyContribution $contribution)
    {
        $this->authorize('contributions.edit');

        $data = [
            'title' => 'Edit Contribution',
            'page_title' => 'Edit Contribution',
            'contribution' => $contribution,
            'members' => Member::active()->orderBy('name')->get(),
            'statuses' => MonthlyContribution::STATUSES,
            'paymentMethods' => MonthlyContribution::PAYMENT_METHODS,
            'years' => range(date('Y') - 5, date('Y') + 1),
            'months' => range(1, 12),
        ];

        return view('admin.contributions.edit', $data);
    }

    public function update(ContributionUpdateRequest $request, MonthlyContribution $contribution)
    {
        $this->authorize('contributions.edit');

        try {
            DB::beginTransaction();

            $data = $request->only([
                'member_id', 'year', 'month', 'amount', 'due_date', 'status',
                'payment_method', 'transaction_id', 'notes'
            ]);

            // Check if changing period to existing record
            if ($contribution->member_id != $request->member_id || 
                $contribution->year != $request->year || 
                $contribution->month != $request->month) {
                $existing = MonthlyContribution::where('member_id', $request->member_id)
                    ->where('year', $request->year)
                    ->where('month', $request->month)
                    ->where('id', '!=', $contribution->id)
                    ->first();

                if ($existing) {
                    return redirect()->back()->with('error', 'Contribution already exists for this member for the selected period.')->withInput();
                }
            }

            // Update payment status if marked as paid
            if ($request->status === 'paid' && $contribution->status !== 'paid') {
                $data['paid_amount'] = $request->amount;
                $data['due_amount'] = 0;
                $data['paid_date'] = now();
                $data['receipt_no'] = $contribution->receipt_no ?? MonthlyContribution::generateReceiptNo();
                $data['paid_by'] = auth()->id();
            } elseif ($request->status === 'pending' || $request->status === 'partial') {
                $data['paid_date'] = null;
            }

            $contribution->update($data);

            DB::commit();

            Log::info('Contribution updated', [
                'id' => $contribution->id,
                'updated_by' => auth()->id()
            ]);

            return redirect()->route('admin.contributions.show', $contribution)->with('success', 'Contribution updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Contribution update failed', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to update contribution: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(MonthlyContribution $contribution)
    {
        $this->authorize('contributions.delete');

        try {
            if ($contribution->status === 'paid') {
                return redirect()->back()->with('error', 'Cannot delete a paid contribution. Please reverse the payment first.');
            }

            Log::info('Contribution deleted', [
                'id' => $contribution->id,
                'member_id' => $contribution->member_id,
                'deleted_by' => auth()->id()
            ]);

            $contribution->delete();

            return redirect()->route('admin.contributions.index')->with('success', 'Contribution deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Contribution deletion failed', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to delete contribution.');
        }
    }

    public function recordPayment(Request $request, MonthlyContribution $contribution)
    {
        $this->authorize('contributions.edit');

        try {
            DB::beginTransaction();

            $amount = $request->paid_amount ?? $contribution->remaining_amount;
            $newPaidAmount = $contribution->paid_amount + $amount;

            $data = [
                'paid_amount' => $newPaidAmount,
                'due_amount' => max(0, $contribution->amount - $newPaidAmount),
                'payment_method' => $request->payment_method,
                'transaction_id' => $request->transaction_id,
                'notes' => $request->notes,
            ];

            if ($newPaidAmount >= $contribution->amount) {
                $data['status'] = 'paid';
                $data['paid_date'] = now();
                $data['paid_by'] = auth()->id();
                $data['receipt_no'] = $contribution->receipt_no ?? MonthlyContribution::generateReceiptNo();
            } else {
                $data['status'] = 'partial';
            }

            $contribution->update($data);

            DB::commit();

            Log::info('Payment recorded', [
                'contribution_id' => $contribution->id,
                'amount' => $amount,
                'recorded_by' => auth()->id()
            ]);

            return redirect()->back()->with('success', 'Payment recorded successfully. Receipt No: ' . $contribution->receipt_no);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment recording failed', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to record payment: ' . $e->getMessage());
        }
    }

    public function generateMonthly(Request $request)
    {
        $this->authorize('contributions.create');

        $year = $request->year ?? date('Y');
        $month = $request->month ?? date('n');
        $amount = $request->amount ?? GeneralSetting::getSetting('default_contribution_amount', 100);
        $dueDate = $request->due_date ?? date('Y-m-d', strtotime($year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-28'));

        try {
            DB::beginTransaction();

            $members = Member::active()->get();
            $created = 0;
            $skipped = 0;

            foreach ($members as $member) {
                // Check if contribution already exists
                $existing = MonthlyContribution::where('member_id', $member->id)
                    ->where('year', $year)
                    ->where('month', $month)
                    ->first();

                if ($existing) {
                    $skipped++;
                    continue;
                }

                MonthlyContribution::create([
                    'member_id' => $member->id,
                    'year' => $year,
                    'month' => $month,
                    'amount' => $amount,
                    'paid_amount' => 0,
                    'due_amount' => $amount,
                    'due_date' => $dueDate,
                    'status' => 'pending',
                    'created_by' => auth()->id(),
                ]);

                $created++;
            }

            DB::commit();

            Log::info('Monthly contributions generated', [
                'year' => $year,
                'month' => $month,
                'created' => $created,
                'skipped' => $skipped,
                'generated_by' => auth()->id()
            ]);

            return redirect()->route('admin.contributions.index', ['year' => $year, 'month' => $month])
                ->with('success', "Generated {$created} contributions. Skipped {$skipped} existing records.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Contribution generation failed', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to generate contributions: ' . $e->getMessage());
        }
    }

    public function markOverdue()
    {
        $this->authorize('contributions.edit');

        try {
            $count = MonthlyContribution::where('status', 'pending')
                ->where('due_date', '<', now())
                ->update(['status' => 'overdue']);

            Log::info('Overdue contributions marked', ['count' => $count, 'marked_by' => auth()->id()]);

            return redirect()->back()->with('success', "Marked {$count} contributions as overdue.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to mark overdue contributions.');
        }
    }

    public function bulkPayment(Request $request)
    {
        $this->authorize('contributions.edit');

        try {
            DB::beginTransaction();

            $contributionIds = $request->contribution_ids ?? [];
            $amount = $request->bulk_amount;
            $paymentMethod = $request->payment_method;
            $transactionId = $request->transaction_id;

            $updated = MonthlyContribution::whereIn('id', $contributionIds)
                ->where('status', '!=', 'paid')
                ->update([
                    'paid_amount' => DB::raw("amount"),
                    'due_amount' => 0,
                    'status' => 'paid',
                    'paid_date' => now(),
                    'payment_method' => $paymentMethod,
                    'transaction_id' => $transactionId,
                    'receipt_no' => DB::raw("IFNULL(receipt_no, '" . MonthlyContribution::generateReceiptNo() . "')"),
                    'paid_by' => auth()->id(),
                ]);

            DB::commit();

            Log::info('Bulk payment processed', [
                'count' => $updated,
                'amount' => $amount,
                'processed_by' => auth()->id()
            ]);

            return redirect()->back()->with('success', "Processed bulk payment for {$updated} contributions.");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to process bulk payment: ' . $e->getMessage());
        }
    }
}
