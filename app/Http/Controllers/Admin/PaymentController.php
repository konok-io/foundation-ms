<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function index(Request $request)
    {
        $this->authorize('payments.view');

        $query = Payment::with(['member', 'creator']);

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('gateway') && $request->gateway) {
            $query->where('gateway', $request->gateway);
        }

        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('payment_id', 'like', '%' . $request->search . '%')
                  ->orWhereHas('member', function ($q) use ($request) {
                      $q->search($request->search);
                  });
            });
        }

        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $payments = $query->orderBy('created_at', 'desc')->paginate(20);

        $data = [
            'title' => 'Payment Management',
            'page_title' => 'Payment History',
            'payments' => $payments,
            'statuses' => Payment::STATUSES,
            'gateways' => Payment::GATEWAYS,
            'types' => Payment::TYPES,
            'stats' => $this->getStats(),
        ];

        return view('admin.payments.index', $data);
    }

    protected function getStats()
    {
        return [
            'total' => Payment::sum('amount'),
            'completed' => Payment::completed()->sum('amount'),
            'pending' => Payment::pending()->sum('amount'),
            'refunded' => Payment::where('status', 'refunded')->sum('refund_amount'),
            'count' => Payment::count(),
        ];
    }

    public function show(Payment $payment)
    {
        $this->authorize('payments.view');

        $data = [
            'title' => 'Payment Details',
            'page_title' => 'Payment: ' . $payment->payment_id,
            'payment' => $payment->load(['member', 'creator']),
            'statuses' => Payment::STATUSES,
        ];

        return view('admin.payments.show', $data);
    }

    public function refund(Request $request, Payment $payment)
    {
        $this->authorize('payments.refund');

        try {
            $request->validate([
                'amount' => 'nullable|numeric|min:0|max:' . $payment->amount,
                'reason' => 'required|string|max:500',
            ]);

            $amount = $request->amount ?? $payment->amount;

            $this->paymentService->processRefund($payment, $amount, $request->reason);

            return redirect()->back()->with('success', 'Payment refunded successfully.');
        } catch (\Exception $e) {
            Log::error('Payment refund failed', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()->with('error', 'Refund failed: ' . $e->getMessage());
        }
    }

    public function webhookStripe(Request $request)
    {
        $payload = $request->all();
        $signature = $request->header('Stripe-Signature');

        $stripeService = app(\App\Services\StripeService::class);
        $result = $stripeService->verifyWebhook($request->getContent(), $signature);

        if (!$result['success']) {
            return response()->json(['error' => 'Webhook verification failed'], 400);
        }

        $this->paymentService->handleStripeWebhook($result['event']->toArray());

        return response()->json(['received' => true]);
    }

    public function webhookPayPal(Request $request)
    {
        $payload = $request->all();
        
        $paypalService = app(\App\Services\PayPalService::class);
        
        if (!$paypalService->verifyWebhook($payload, $request->headers->all())) {
            Log::warning('PayPal webhook verification failed', ['payload' => $payload]);
            return response()->json(['error' => 'Webhook verification failed'], 400);
        }

        $this->paymentService->handlePayPalWebhook($payload);

        return response()->json(['received' => true]);
    }

    public function export(Request $request)
    {
        $this->authorize('payments.export');

        $query = Payment::with(['member', 'creator']);

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $payments = $query->orderBy('created_at', 'desc')->get();

        return response()->streamDownload(function () use ($payments) {
            $handle = fopen('php://output', 'w');
            
            fputcsv($handle, [
                'Payment ID', 'Member', 'Type', 'Gateway', 'Amount', 
                'Status', 'Paid At', 'Created At'
            ]);

            foreach ($payments as $payment) {
                fputcsv($handle, [
                    $payment->payment_id,
                    $payment->member?->name ?? 'N/A',
                    $payment->type,
                    $payment->gateway ?? 'N/A',
                    $payment->amount,
                    $payment->status,
                    $payment->paid_at?->format('Y-m-d H:i'),
                    $payment->created_at->format('Y-m-d H:i'),
                ]);
            }

            fclose($handle);
        }, 'payments-' . date('Y-m-d') . '.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }
}
