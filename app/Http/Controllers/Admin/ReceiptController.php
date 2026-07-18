<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Receipt;
use App\Services\ReceiptService;
use Illuminate\Http\Request;

class ReceiptController extends Controller
{
    protected ReceiptService $receiptService;

    public function __construct(ReceiptService $receiptService)
    {
        $this->receiptService = $receiptService;
    }

    public function index(Request $request)
    {
        $this->authorize('receipts.view');

        $query = Receipt::with(['member', 'creator']);

        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        if ($request->has('member_id') && $request->member_id) {
            $query->where('member_id', $request->member_id);
        }

        if ($request->has('search') && $request->search) {
            $query->where('receipt_no', 'like', '%' . $request->search . '%');
        }

        if ($request->has('date_from')) {
            $query->whereDate('paid_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('paid_at', '<=', $request->date_to);
        }

        $receipts = $query->orderBy('created_at', 'desc')->paginate(20);

        $data = [
            'title' => 'Receipt Management',
            'page_title' => 'Receipt History',
            'receipts' => $receipts,
            'types' => Receipt::TYPES,
            'stats' => $this->getStats(),
        ];

        return view('admin.receipts.index', $data);
    }

    protected function getStats(): array
    {
        return [
            'total' => Receipt::count(),
            'total_amount' => Receipt::sum('amount'),
            'printed' => Receipt::where('is_printed', true)->count(),
            'emailed' => Receipt::where('is_emailed', true)->count(),
        ];
    }

    public function show(Receipt $receipt)
    {
        $this->authorize('receipts.view');

        $receipt->load(['member', 'creator']);

        $data = [
            'title' => 'Receipt Details',
            'page_title' => 'Receipt: ' . $receipt->receipt_no,
            'receipt' => $receipt,
            'types' => Receipt::TYPES,
        ];

        return view('admin.receipts.show', $data);
    }

    public function download(Receipt $receipt)
    {
        $this->authorize('receipts.download');

        return $this->receiptService->downloadPdf($receipt);
    }

    public function print(Receipt $receipt)
    {
        $this->authorize('receipts.download');

        return $this->receiptService->streamPdf($receipt);
    }

    public function email(Request $request, Receipt $receipt)
    {
        $this->authorize('receipts.email');

        if ($this->receiptService->sendEmail($receipt)) {
            return redirect()->back()->with('success', 'Receipt sent to member email successfully.');
        }

        return redirect()->back()->with('error', 'Failed to send receipt email. Please check if member has an email address.');
    }

    public function bulkEmail(Request $request)
    {
        $this->authorize('receipts.email');

        $request->validate([
            'receipt_ids' => 'required|array',
            'receipt_ids.*' => 'exists:receipts,id',
        ]);

        $result = $this->receiptService->bulkEmail($request->receipt_ids);

        return redirect()->back()
            ->with('success', "Sent {$result['sent']} receipts. {$result['failed']} failed.");
    }

    public function verify(string $receiptNo)
    {
        $result = Receipt::verify($receiptNo);

        return view('receipts.verify', [
            'verification' => $result,
        ]);
    }

    public function export(Request $request)
    {
        $this->authorize('receipts.export');

        $query = Receipt::with(['member', 'creator']);

        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        if ($request->has('date_from')) {
            $query->whereDate('paid_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('paid_at', '<=', $request->date_to);
        }

        $receipts = $query->orderBy('created_at', 'desc')->get();

        return response()->streamDownload(function () use ($receipts) {
            $handle = fopen('php://output', 'w');
            
            fputcsv($handle, [
                'Receipt No', 'Type', 'Member', 'Amount', 'Currency',
                'Payment Method', 'Paid At', 'Created At'
            ]);

            foreach ($receipts as $receipt) {
                fputcsv($handle, [
                    $receipt->receipt_no,
                    $receipt->type,
                    $receipt->member?->name ?? 'N/A',
                    $receipt->amount,
                    $receipt->currency,
                    $receipt->payment_method,
                    $receipt->paid_at?->format('Y-m-d H:i'),
                    $receipt->created_at->format('Y-m-d H:i'),
                ]);
            }

            fclose($handle);
        }, 'receipts-' . date('Y-m-d') . '.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }
}
