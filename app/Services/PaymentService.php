<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\MonthlyContribution;
use App\Models\EmergencyCollectionPayment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class PaymentService
{
    protected StripeService $stripeService;
    protected PayPalService $paypalService;

    public function __construct(StripeService $stripeService, PayPalService $paypalService)
    {
        $this->stripeService = $stripeService;
        $this->paypalService = $paypalService;
    }

    public function createPayment(array $data): Payment
    {
        return Payment::create([
            'payment_id' => Payment::generatePaymentId(),
            'member_id' => $data['member_id'] ?? null,
            'type' => $data['type'],
            'reference_id' => $data['reference_id'] ?? null,
            'reference_type' => $data['reference_type'] ?? null,
            'amount' => $data['amount'],
            'currency' => $data['currency'] ?? 'SAR',
            'payment_method' => $data['payment_method'] ?? 'online',
            'gateway' => $data['gateway'] ?? null,
            'status' => 'pending',
            'notes' => $data['notes'] ?? null,
            'created_by' => auth()->id(),
        ]);
    }

    public function initiateStripePayment(Payment $payment, string $returnUrl, string $cancelUrl): array
    {
        try {
            $result = $this->stripeService->createCheckoutSession(
                $payment,
                $returnUrl,
                $cancelUrl
            );

            $payment->update([
                'gateway' => 'stripe',
                'gateway_transaction_id' => $result['session_id'],
                'status' => 'processing',
            ]);

            return $result;
        } catch (Exception $e) {
            Log::error('Stripe payment initiation failed', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    public function initiatePayPalPayment(Payment $payment, string $returnUrl, string $cancelUrl): array
    {
        try {
            $result = $this->paypalService->createOrder(
                $payment,
                $returnUrl,
                $cancelUrl
            );

            $payment->update([
                'gateway' => 'paypal',
                'gateway_transaction_id' => $result['order_id'],
                'status' => 'processing',
            ]);

            return $result;
        } catch (Exception $e) {
            Log::error('PayPal payment initiation failed', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    public function handleStripeWebhook(array $payload): void
    {
        $eventType = $payload['type'];
        $sessionId = $payload['data']['object']['id'] ?? null;

        $payment = Payment::where('gateway_transaction_id', $sessionId)->first();

        if (!$payment) {
            Log::warning('Stripe webhook: Payment not found', ['session_id' => $sessionId]);
            return;
        }

        switch ($eventType) {
            case 'checkout.session.completed':
                $this->completePayment($payment, $payload);
                break;

            case 'checkout.session.expired':
                $this->failPayment($payment, 'Session expired');
                break;

            case 'payment_intent.payment_failed':
                $this->failPayment($payment, $payload['data']['object']['last_payment_error']['message'] ?? 'Payment failed');
                break;
        }
    }

    public function handlePayPalWebhook(array $payload): void
    {
        $eventType = $payload['event_type'];
        $orderId = $payload['resource']['id'] ?? null;

        $payment = Payment::where('gateway_transaction_id', $orderId)->first();

        if (!$payment) {
            Log::warning('PayPal webhook: Payment not found', ['order_id' => $orderId]);
            return;
        }

        switch ($eventType) {
            case 'CHECKOUT.ORDER.APPROVED':
                $this->capturePayPalPayment($payment, $payload);
                break;

            case 'PAYMENT.CAPTURE.COMPLETED':
                $this->completePayment($payment, $payload);
                break;

            case 'PAYMENT.CAPTURE.DENIED':
                $this->failPayment($payment, 'Payment denied');
                break;
        }
    }

    public function completePayment(Payment $payment, array $gatewayResponse = []): void
    {
        DB::transaction(function () use ($payment, $gatewayResponse) {
            $payment->update([
                'status' => 'completed',
                'paid_at' => now(),
                'gateway_response' => $gatewayResponse,
            ]);

            $this->updateReferencePayment($payment);

            Log::info('Payment completed', [
                'payment_id' => $payment->id,
                'payment_id_code' => $payment->payment_id,
                'amount' => $payment->amount,
            ]);
        });
    }

    public function failPayment(Payment $payment, string $reason): void
    {
        $payment->update([
            'status' => 'failed',
            'failure_reason' => $reason,
        ]);

        Log::warning('Payment failed', [
            'payment_id' => $payment->id,
            'reason' => $reason,
        ]);
    }

    public function processRefund(Payment $payment, float $amount = null, string $reason = null): bool
    {
        if (!$payment->isRefundable) {
            throw new Exception('Payment is not refundable');
        }

        $refundAmount = $amount ?? $payment->amount;

        try {
            DB::transaction(function () use ($payment, $refundAmount, $reason) {
                if ($payment->gateway === 'stripe') {
                    $this->stripeService->refund($payment->gateway_transaction_id, $refundAmount);
                } elseif ($payment->gateway === 'paypal') {
                    $this->paypalService->refundOrder($payment->gateway_transaction_id, $refundAmount);
                }

                $payment->update([
                    'refund_id' => 'REF-' . uniqid(),
                    'refund_amount' => $refundAmount,
                    'refund_reason' => $reason,
                    'refunded_at' => now(),
                    'status' => 'refunded',
                ]);

                $this->reverseReferencePayment($payment, $refundAmount);

                Log::info('Payment refunded', [
                    'payment_id' => $payment->id,
                    'refund_amount' => $refundAmount,
                ]);
            });

            return true;
        } catch (Exception $e) {
            Log::error('Payment refund failed', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    protected function updateReferencePayment(Payment $payment): void
    {
        if ($payment->type === 'monthly_contribution' && $payment->reference_id) {
            $contribution = MonthlyContribution::find($payment->reference_id);
            if ($contribution) {
                $newPaidAmount = $contribution->paid_amount + $payment->amount;
                $contribution->update([
                    'paid_amount' => $newPaidAmount,
                    'due_amount' => max(0, $contribution->amount - $newPaidAmount),
                    'status' => $newPaidAmount >= $contribution->amount ? 'paid' : 'partial',
                    'paid_date' => now(),
                    'payment_method' => $payment->gateway,
                    'transaction_id' => $payment->gateway_transaction_id,
                    'receipt_no' => $contribution->receipt_no ?? MonthlyContribution::generateReceiptNo(),
                ]);
            }
        } elseif ($payment->type === 'emergency_collection' && $payment->reference_id) {
            $ecPayment = EmergencyCollectionPayment::find($payment->reference_id);
            if ($ecPayment) {
                $newPaidAmount = $ecPayment->paid_amount + $payment->amount;
                $ecPayment->update([
                    'paid_amount' => $newPaidAmount,
                    'due_amount' => max(0, $ecPayment->amount - $newPaidAmount),
                    'status' => $newPaidAmount >= $ecPayment->amount ? 'paid' : 'partial',
                    'paid_date' => now(),
                    'payment_method' => $payment->gateway,
                    'receipt_no' => $ecPayment->receipt_no ?? EmergencyCollectionPayment::generateReceiptNo(),
                ]);

                $ecPayment->emergencyCollection->update([
                    'collected_amount' => $ecPayment->emergencyCollection->calculateCollectedAmount()
                ]);
            }
        }
    }

    protected function reverseReferencePayment(Payment $payment, float $amount): void
    {
        if ($payment->type === 'monthly_contribution' && $payment->reference_id) {
            $contribution = MonthlyContribution::find($payment->reference_id);
            if ($contribution) {
                $contribution->update([
                    'paid_amount' => max(0, $contribution->paid_amount - $amount),
                    'due_amount' => min($contribution->amount, $contribution->due_amount + $amount),
                    'status' => 'pending',
                ]);
            }
        } elseif ($payment->type === 'emergency_collection' && $payment->reference_id) {
            $ecPayment = EmergencyCollectionPayment::find($payment->reference_id);
            if ($ecPayment) {
                $ecPayment->update([
                    'paid_amount' => max(0, $ecPayment->paid_amount - $amount),
                    'due_amount' => min($ecPayment->amount, $ecPayment->due_amount + $amount),
                    'status' => 'pending',
                ]);

                $ecPayment->emergencyCollection->update([
                    'collected_amount' => $ecPayment->emergencyCollection->calculateCollectedAmount()
                ]);
            }
        }
    }

    protected function capturePayPalPayment(Payment $payment, array $payload): void
    {
        $captureId = $payload['resource']['purchase_units'][0]['payments']['captures'][0]['id'] ?? null;

        if ($captureId) {
            $payment->update(['gateway_transaction_id' => $captureId]);
        }

        $this->completePayment($payment, $payload);
    }
}
