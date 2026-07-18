<?php

namespace App\Http\Controllers;

use App\Http\Requests\PublicDonationRequest;
use App\Models\Donation;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PublicDonationController extends Controller
{
    protected PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function index()
    {
        $data = [
            'title' => 'Donate',
            'page_title' => 'Make a Donation',
            'purposes' => Donation::PURPOSES,
        ];

        return view('public.donate', $data);
    }

    public function store(PublicDonationRequest $request)
    {
        try {
            $donation = Donation::create([
                'donor_name' => $request->donor_name,
                'donor_name_bn' => $request->donor_name_bn,
                'email' => $request->email,
                'phone' => $request->phone,
                'member_id' => auth()->check() ? auth()->user()->member_id : null,
                'amount' => $request->amount,
                'currency' => 'SAR',
                'purpose' => $request->purpose,
                'purpose_other' => $request->purpose === 'other' ? $request->purpose_other : null,
                'payment_method' => 'online',
                'status' => 'pending',
                'is_anonymous' => $request->boolean('is_anonymous'),
                'message' => $request->message,
                'created_by' => 1,
            ]);

            Log::info('Public donation created', [
                'id' => $donation->id,
                'donor' => $donation->donor_name,
                'amount' => $request->amount,
            ]);

            // If payment gateway selected, initiate payment
            if ($request->gateway) {
                return $this->initiatePayment($donation, $request->gateway);
            }

            return redirect()->back()->with('success', 'Thank you for your donation! We will contact you shortly.');
        } catch (\Exception $e) {
            Log::error('Public donation failed', [
                'error' => $e->getMessage(),
                'request' => $request->all(),
            ]);

            return redirect()->back()
                ->with('error', 'Failed to process donation. Please try again.')
                ->withInput();
        }
    }

    protected function initiatePayment(Donation $donation, string $gateway)
    {
        try {
            $payment = $this->paymentService->createPayment([
                'member_id' => $donation->member_id,
                'type' => 'donation',
                'reference_id' => $donation->id,
                'reference_type' => get_class($donation),
                'amount' => $donation->amount,
                'currency' => 'SAR',
                'payment_method' => 'online',
                'gateway' => $gateway,
            ]);

            $donation->update([
                'payment_id' => $payment->id,
                'gateway' => $gateway,
            ]);

            $returnUrl = route('donation.payment.success', ['donation_id' => $donation->id]);
            $cancelUrl = route('donation.payment.cancel', ['donation_id' => $donation->id]);

            if ($gateway === 'stripe') {
                $stripeService = app(\App\Services\StripeService::class);
                $result = $stripeService->createCheckoutSession($payment, $returnUrl, $cancelUrl);
                return redirect($result['url']);
            } else {
                $paypalService = app(\App\Services\PayPalService::class);
                $result = $paypalService->createOrder($payment, $returnUrl, $cancelUrl);
                return redirect($result['url']);
            }
        } catch (\Exception $e) {
            Log::error('Donation payment initiation failed', [
                'donation_id' => $donation->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->with('error', 'Failed to initiate payment. Please try again.');
        }
    }

    public function paymentSuccess(Request $request, Donation $donation)
    {
        try {
            $donation->update([
                'status' => 'completed',
                'received_at' => now(),
                'received_by' => 1,
            ]);

            return view('public.donation-success', [
                'donation' => $donation,
                'title' => 'Donation Successful',
            ]);
        } catch (\Exception $e) {
            Log::error('Donation success handling failed', [
                'donation_id' => $donation->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('donate')->with('error', 'An error occurred.');
        }
    }

    public function paymentCancel(Request $request, Donation $donation)
    {
        $donation->update(['status' => 'cancelled']);

        return view('public.donation-cancel', [
            'title' => 'Donation Cancelled',
        ]);
    }
}
