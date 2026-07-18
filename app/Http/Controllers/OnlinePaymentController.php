<?php

namespace App\Http\Controllers;

use App\Models\MonthlyContribution;
use App\Models\EmergencyCollectionPayment;
use App\Services\PaymentService;
use App\Services\StripeService;
use App\Services\PayPalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OnlinePaymentController extends Controller
{
    protected PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'type' => 'required|in:monthly_contribution,emergency_collection',
            'reference_id' => 'required|integer',
            'gateway' => 'required|in:stripe,paypal',
            'amount' => 'required|numeric|min:1',
        ]);

        try {
            $member = auth()->user()->member;

            if ($request->type === 'monthly_contribution') {
                $reference = MonthlyContribution::findOrFail($request->reference_id);
            } else {
                $reference = EmergencyCollectionPayment::findOrFail($request->reference_id);
            }

            $payment = $this->paymentService->createPayment([
                'member_id' => $member->id,
                'type' => $request->type,
                'reference_id' => $request->reference_id,
                'reference_type' => get_class($reference),
                'amount' => $request->amount,
                'currency' => 'SAR',
                'payment_method' => 'online',
                'gateway' => $request->gateway,
            ]);

            $returnUrl = route('payment.success', ['payment_id' => $payment->id]);
            $cancelUrl = route('payment.cancel', ['payment_id' => $payment->id]);

            if ($request->gateway === 'stripe') {
                $stripeService = app(StripeService::class);
                $result = $stripeService->createCheckoutSession($payment, $returnUrl, $cancelUrl);
                
                return redirect($result['url']);
            } else {
                $paypalService = app(PayPalService::class);
                $result = $paypalService->createOrder($payment, $returnUrl, $cancelUrl);
                
                return redirect($result['url']);
            }
        } catch (\Exception $e) {
            Log::error('Payment initiation failed', [
                'error' => $e->getMessage(),
                'request' => $request->all(),
            ]);

            return redirect()->back()->with('error', 'Failed to initiate payment: ' . $e->getMessage());
        }
    }

    public function success(Request $request)
    {
        try {
            $paymentId = $request->payment_id;

            if ($request->has('session_id')) {
                $stripeService = app(StripeService::class);
                $session = $stripeService->retrieveCheckoutSession($request->session_id);

                if ($session && $session->payment_status === 'paid') {
                    $payment = \App\Models\Payment::where('gateway_transaction_id', $request->session_id)->first();
                    
                    if ($payment && $payment->status !== 'completed') {
                        $this->paymentService->completePayment($payment, ['stripe_session' => $session->toArray()]);
                    }

                    return view('payment.success', [
                        'payment' => $payment,
                        'message' => 'Payment completed successfully!',
                    ]);
                }
            }

            if ($request->has('token')) {
                $paypalService = app(PayPalService::class);
                $order = $paypalService->captureOrder($request->token);

                if ($order && $order['status'] === 'COMPLETED') {
                    $payment = \App\Models\Payment::find($paymentId);
                    
                    if ($payment && $payment->status !== 'completed') {
                        $this->paymentService->completePayment($payment, ['paypal_order' => $order]);
                    }

                    return view('payment.success', [
                        'payment' => $payment,
                        'message' => 'Payment completed successfully!',
                    ]);
                }
            }

            $payment = \App\Models\Payment::find($paymentId);

            return view('payment.success', [
                'payment' => $payment,
                'message' => 'Payment is being processed.',
            ]);
        } catch (\Exception $e) {
            Log::error('Payment success handler error', [
                'error' => $e->getMessage(),
                'request' => $request->all(),
            ]);

            return redirect()->route('home')->with('error', 'An error occurred while processing your payment.');
        }
    }

    public function cancel(Request $request)
    {
        $paymentId = $request->payment_id;

        if ($paymentId) {
            $payment = \App\Models\Payment::find($paymentId);
            
            if ($payment && $payment->status === 'processing') {
                $payment->update(['status' => 'cancelled']);
            }
        }

        return view('payment.cancel', [
            'message' => 'Payment was cancelled. You can try again from your dashboard.',
        ]);
    }
}
