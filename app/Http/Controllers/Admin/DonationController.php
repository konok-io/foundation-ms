<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DonationStoreRequest;
use App\Http\Requests\DonationUpdateRequest;
use App\Models\Donation;
use App\Services\ReceiptService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DonationController extends Controller
{
    protected ReceiptService $receiptService;

    public function __construct(ReceiptService $receiptService)
    {
        $this->receiptService = $receiptService;
    }

    public function index(Request $request)
    {
        $this->authorize('donations.view');

        $query = Donation::with(['member', 'creator', 'receiver']);

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('purpose') && $request->purpose) {
            $query->where('purpose', $request->purpose);
        }

        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('donor_name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->has('date_from')) {
            $query->whereDate('received_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('received_at', '<=', $request->date_to);
        }

        $donations = $query->orderBy('created_at', 'desc')->paginate(20);

        $data = [
            'title' => 'Donation Management',
            'page_title' => 'Donation History',
            'donations' => $donations,
            'purposes' => Donation::PURPOSES,
            'statuses' => Donation::STATUSES,
            'stats' => $this->getStats(),
        ];

        return view('admin.donations.index', $data);
    }

    protected function getStats(): array
    {
        return [
            'total_donations' => Donation::completed()->count(),
            'total_amount' => Donation::completed()->sum('amount'),
            'average_donation' => Donation::completed()->avg('amount') ?? 0,
            'pending_count' => Donation::pending()->count(),
        ];
    }

    public function create()
    {
        $this->authorize('donations.create');

        $data = [
            'title' => 'Add Donation',
            'page_title' => 'Create Donation',
            'donation' => null,
            'purposes' => Donation::PURPOSES,
            'paymentMethods' => Donation::PAYMENT_METHODS,
            'statuses' => Donation::STATUSES,
        ];

        return view('admin.donations.create', $data);
    }

    public function store(DonationStoreRequest $request)
    {
        $this->authorize('donations.create');

        try {
            DB::beginTransaction();

            $donation = Donation::create([
                'donor_name' => $request->donor_name,
                'donor_name_bn' => $request->donor_name_bn,
                'email' => $request->email,
                'phone' => $request->phone,
                'member_id' => $request->member_id,
                'amount' => $request->amount,
                'currency' => $request->currency ?? 'SAR',
                'purpose' => $request->purpose,
                'purpose_other' => $request->purpose === 'other' ? $request->purpose_other : null,
                'payment_method' => $request->payment_method,
                'status' => $request->status ?? 'pending',
                'is_anonymous' => $request->boolean('is_anonymous'),
                'message' => $request->message,
                'received_at' => $request->status === 'completed' ? now() : null,
                'received_by' => $request->status === 'completed' ? auth()->id() : null,
                'notes' => $request->notes,
                'created_by' => auth()->id(),
            ]);

            DB::commit();

            Log::info('Donation created', [
                'id' => $donation->id,
                'donor' => $donation->donor_name,
                'amount' => $request->amount,
            ]);

            return redirect()->route('admin.donations.index')
                ->with('success', 'Donation added successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to add donation: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Donation $donation)
    {
        $this->authorize('donations.view');

        $donation->load(['member', 'creator', 'receiver', 'payment']);

        $data = [
            'title' => 'Donation Details',
            'page_title' => 'Donation: ' . $donation->donor_name,
            'donation' => $donation,
            'purposes' => Donation::PURPOSES,
            'statuses' => Donation::STATUSES,
            'paymentMethods' => Donation::PAYMENT_METHODS,
        ];

        return view('admin.donations.show', $data);
    }

    public function edit(Donation $donation)
    {
        $this->authorize('donations.edit');

        $data = [
            'title' => 'Edit Donation',
            'page_title' => 'Edit Donation',
            'donation' => $donation,
            'purposes' => Donation::PURPOSES,
            'paymentMethods' => Donation::PAYMENT_METHODS,
            'statuses' => Donation::STATUSES,
        ];

        return view('admin.donations.edit', $data);
    }

    public function update(DonationUpdateRequest $request, Donation $donation)
    {
        $this->authorize('donations.edit');

        try {
            DB::beginTransaction();

            $data = $request->only([
                'donor_name', 'donor_name_bn', 'email', 'phone', 'member_id',
                'amount', 'currency', 'purpose', 'purpose_other', 'payment_method',
                'status', 'is_anonymous', 'message', 'notes'
            ]);

            // Handle received_at and received_by based on status change
            if ($request->status === 'completed' && $donation->status !== 'completed') {
                $data['received_at'] = now();
                $data['received_by'] = auth()->id();
            }

            $donation->update($data);

            DB::commit();

            Log::info('Donation updated', [
                'id' => $donation->id,
                'updated_by' => auth()->id(),
            ]);

            return redirect()->route('admin.donations.show', $donation)
                ->with('success', 'Donation updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to update donation: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Donation $donation)
    {
        $this->authorize('donations.delete');

        try {
            Log::info('Donation deleted', [
                'id' => $donation->id,
                'deleted_by' => auth()->id(),
            ]);

            $donation->delete();

            return redirect()->route('admin.donations.index')
                ->with('success', 'Donation deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete donation.');
        }
    }

    public function export(Request $request)
    {
        $this->authorize('donations.export');

        $query = Donation::with(['member']);

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('purpose') && $request->purpose) {
            $query->where('purpose', $request->purpose);
        }

        if ($request->has('date_from')) {
            $query->whereDate('received_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('received_at', '<=', $request->date_to);
        }

        $donations = $query->orderBy('created_at', 'desc')->get();

        return response()->streamDownload(function () use ($donations) {
            $handle = fopen('php://output', 'w');
            
            fputcsv($handle, [
                'Donor Name', 'Email', 'Phone', 'Amount', 'Currency',
                'Purpose', 'Payment Method', 'Status', 'Received At', 'Created At'
            ]);

            foreach ($donations as $donation) {
                fputcsv($handle, [
                    $donation->donor_name,
                    $donation->email,
                    $donation->phone,
                    $donation->amount,
                    $donation->currency,
                    $donation->purpose_label,
                    $donation->payment_method,
                    $donation->status,
                    $donation->received_at?->format('Y-m-d H:i'),
                    $donation->created_at->format('Y-m-d H:i'),
                ]);
            }

            fclose($handle);
        }, 'donations-' . date('Y-m-d') . '.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }
}
