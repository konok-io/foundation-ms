<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmergencyCollectionStoreRequest;
use App\Http\Requests\EmergencyCollectionUpdateRequest;
use App\Models\EmergencyCollection;
use App\Models\EmergencyCollectionPayment;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EmergencyCollectionController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('emergency_collections.view');

        $query = EmergencyCollection::with('creator');

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('title_bn', 'like', '%' . $request->search . '%');
            });
        }

        $collections = $query->orderBy('created_at', 'desc')->paginate(15);

        $data = [
            'title' => 'Emergency Collections',
            'page_title' => 'Emergency Collection Management',
            'collections' => $collections,
            'types' => EmergencyCollection::TYPES,
            'statuses' => EmergencyCollection::STATUSES,
            'stats' => $this->getStats(),
        ];

        return view('admin.emergency-collections.index', $data);
    }

    protected function getStats()
    {
        return [
            'total' => EmergencyCollection::count(),
            'active' => EmergencyCollection::active()->count(),
            'total_collected' => EmergencyCollection::sum('collected_amount'),
            'total_target' => EmergencyCollection::sum('target_amount'),
        ];
    }

    public function create()
    {
        $this->authorize('emergency_collections.create');

        $data = [
            'title' => 'Create Emergency Collection',
            'page_title' => 'Create Emergency Collection',
            'types' => EmergencyCollection::TYPES,
            'statuses' => EmergencyCollection::STATUSES,
        ];

        return view('admin.emergency-collections.create', $data);
    }

    public function store(EmergencyCollectionStoreRequest $request)
    {
        $this->authorize('emergency_collections.create');

        try {
            DB::beginTransaction();

            $collection = EmergencyCollection::create([
                'title' => $request->title,
                'title_bn' => $request->title_bn,
                'description' => $request->description,
                'description_bn' => $request->description_bn,
                'type' => $request->type,
                'target_amount' => $request->target_amount,
                'amount_per_member' => $request->amount_per_member,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'status' => $request->status ?? 'draft',
                'notes' => $request->notes,
                'created_by' => auth()->id(),
            ]);

            // If set to active, assign to all active members
            if ($collection->status === 'active' && $request->boolean('assign_to_members')) {
                $this->assignToMembers($collection);
            }

            DB::commit();

            Log::info('Emergency collection created', [
                'id' => $collection->id,
                'title' => $collection->title,
                'created_by' => auth()->id()
            ]);

            return redirect()->route('admin.emergency-collections.index')
                ->with('success', 'Emergency collection created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Emergency collection creation failed', ['error' => $e->getMessage()]);
            return redirect()->back()
                ->with('error', 'Failed to create collection: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(EmergencyCollection $emergencyCollection)
    {
        $this->authorize('emergency_collections.view');

        $data = [
            'title' => 'Emergency Collection Details',
            'page_title' => $emergencyCollection->title,
            'collection' => $emergencyCollection->load(['payments.member', 'creator']),
            'types' => EmergencyCollection::TYPES,
            'statuses' => EmergencyCollection::STATUSES,
            'paymentStatuses' => EmergencyCollectionPayment::STATUSES,
            'paymentMethods' => EmergencyCollectionPayment::PAYMENT_METHODS,
        ];

        return view('admin.emergency-collections.show', $data);
    }

    public function edit(EmergencyCollection $emergencyCollection)
    {
        $this->authorize('emergency_collections.edit');

        $data = [
            'title' => 'Edit Emergency Collection',
            'page_title' => 'Edit: ' . $emergencyCollection->title,
            'collection' => $emergencyCollection,
            'types' => EmergencyCollection::TYPES,
            'statuses' => EmergencyCollection::STATUSES,
        ];

        return view('admin.emergency-collections.edit', $data);
    }

    public function update(EmergencyCollectionUpdateRequest $request, EmergencyCollection $emergencyCollection)
    {
        $this->authorize('emergency_collections.edit');

        try {
            DB::beginTransaction();

            $data = $request->only([
                'title', 'title_bn', 'description', 'description_bn', 'type',
                'target_amount', 'amount_per_member', 'start_date', 'end_date', 'status', 'notes'
            ]);

            $wasActive = $emergencyCollection->status === 'active';
            $willBeActive = $request->status === 'active';

            $emergencyCollection->update($data);

            // If becoming active and wasn't before, assign to members
            if ($willBeActive && !$wasActive && $request->boolean('assign_to_members')) {
                $this->assignToMembers($emergencyCollection);
            }

            DB::commit();

            Log::info('Emergency collection updated', [
                'id' => $emergencyCollection->id,
                'updated_by' => auth()->id()
            ]);

            return redirect()->route('admin.emergency-collections.show', $emergencyCollection)
                ->with('success', 'Emergency collection updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Emergency collection update failed', ['error' => $e->getMessage()]);
            return redirect()->back()
                ->with('error', 'Failed to update collection: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(EmergencyCollection $emergencyCollection)
    {
        $this->authorize('emergency_collections.delete');

        try {
            if ($emergencyCollection->collected_amount > 0) {
                return redirect()->back()
                    ->with('error', 'Cannot delete a collection with collected amounts.');
            }

            Log::info('Emergency collection deleted', [
                'id' => $emergencyCollection->id,
                'deleted_by' => auth()->id()
            ]);

            $emergencyCollection->delete();

            return redirect()->route('admin.emergency-collections.index')
                ->with('success', 'Emergency collection deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete collection.');
        }
    }

    public function assignMembers(EmergencyCollection $emergencyCollection)
    {
        $this->authorize('emergency_collections.edit');

        try {
            $this->assignToMembers($emergencyCollection);

            return redirect()->back()
                ->with('success', 'Assigned to all active members successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to assign members: ' . $e->getMessage());
        }
    }

    protected function assignToMembers(EmergencyCollection $collection)
    {
        $members = Member::active()->get();
        $amount = $collection->amount_per_member;

        foreach ($members as $member) {
            EmergencyCollectionPayment::firstOrCreate(
                [
                    'emergency_collection_id' => $collection->id,
                    'member_id' => $member->id,
                ],
                [
                    'amount' => $amount,
                    'paid_amount' => 0,
                    'due_amount' => $amount,
                    'status' => 'pending',
                    'created_by' => auth()->id(),
                ]
            );
        }
    }

    public function recordPayment(Request $request, EmergencyCollectionPayment $payment)
    {
        $this->authorize('emergency_collections.edit');

        try {
            DB::beginTransaction();

            $amount = $request->paid_amount ?? $payment->remaining_amount;
            $newPaidAmount = $payment->paid_amount + $amount;

            $data = [
                'paid_amount' => $newPaidAmount,
                'due_amount' => max(0, $payment->amount - $newPaidAmount),
                'payment_method' => $request->payment_method,
                'transaction_id' => $request->transaction_id,
                'notes' => $request->notes,
            ];

            if ($newPaidAmount >= $payment->amount) {
                $data['status'] = 'paid';
                $data['paid_date'] = now();
                $data['paid_by'] = auth()->id();
                $data['receipt_no'] = $payment->receipt_no ?? EmergencyCollectionPayment::generateReceiptNo();
            } else {
                $data['status'] = 'partial';
            }

            $payment->update($data);

            // Update collection's collected amount
            $payment->emergencyCollection->update([
                'collected_amount' => $payment->emergencyCollection->calculateCollectedAmount()
            ]);

            DB::commit();

            return redirect()->back()
                ->with('success', 'Payment recorded successfully. Receipt No: ' . $payment->receipt_no);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to record payment: ' . $e->getMessage());
        }
    }

    public function bulkPayment(Request $request)
    {
        $this->authorize('emergency_collections.edit');

        try {
            DB::beginTransaction();

            $paymentIds = $request->payment_ids ?? [];
            $collection = EmergencyCollection::findOrFail($request->collection_id);

            $updated = 0;
            foreach ($paymentIds as $paymentId) {
                $payment = EmergencyCollectionPayment::where('id', $paymentId)
                    ->where('status', '!=', 'paid')
                    ->first();

                if ($payment) {
                    $payment->update([
                        'paid_amount' => $payment->amount,
                        'due_amount' => 0,
                        'status' => 'paid',
                        'paid_date' => now(),
                        'payment_method' => $request->payment_method,
                        'receipt_no' => $payment->receipt_no ?? EmergencyCollectionPayment::generateReceiptNo(),
                        'paid_by' => auth()->id(),
                    ]);
                    $updated++;
                }
            }

            // Update collection
            $collection->update([
                'collected_amount' => $collection->calculateCollectedAmount()
            ]);

            DB::commit();

            return redirect()->back()
                ->with('success', "Processed bulk payment for {$updated} members.");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to process bulk payment: ' . $e->getMessage());
        }
    }

    public function closeCollection(EmergencyCollection $emergencyCollection)
    {
        $this->authorize('emergency_collections.edit');

        try {
            $emergencyCollection->update(['status' => 'closed']);

            return redirect()->back()
                ->with('success', 'Emergency collection closed successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to close collection.');
        }
    }
}
