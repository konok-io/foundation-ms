<?php

namespace App\Services;

use App\Models\GeneralSetting;
use App\Models\Payment;
use Exception;
use Illuminate\Support\Facades\Log;
use Stripe\Checkout\Session;
use Stripe\Exception\SignatureVerificationException;
use Stripe\PaymentIntent;
use Stripe\Refund;
use Stripe\Stripe;
use Stripe\Webhook;

class StripeService
{
    protected ?string $secretKey;
    protected ?string $webhookSecret;
    protected string $currency;

    public function __construct()
    {
        $this->secretKey = GeneralSetting::getSetting('stripe_secret_key') ?? config('services.stripe.secret');
        $this->webhookSecret = GeneralSetting::getSetting('stripe_webhook_secret') ?? config('services.stripe.webhook_secret');
        $this->currency = GeneralSetting::getSetting('stripe_currency', 'SAR');

        if ($this->secretKey) {
            Stripe::setApiKey($this->secretKey);
        }
    }

    public function isConfigured(): bool
    {
        return !empty($this->secretKey);
    }

    public function createCheckoutSession(Payment $payment, string $returnUrl, string $cancelUrl): array
    {
        try {
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => strtolower($this->currency),
                            'product_data' => [
                                'name' => $this->getPaymentDescription($payment),
                                'description' => 'Payment ID: ' . $payment->payment_id,
                            ],
                            'unit_amount' => $this->toCents($payment->amount),
                        ],
                        'quantity' => 1,
                    ],
                ],
                'mode' => 'payment',
                'success_url' => $returnUrl . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => $cancelUrl,
                'metadata' => [
                    'payment_id' => $payment->id,
                    'payment_id_code' => $payment->payment_id,
                ],
                'billing_address_collection' => 'auto',
            ]);

            Log::info('Stripe checkout session created', [
                'payment_id' => $payment->id,
                'session_id' => $session->id,
                'amount' => $payment->amount,
            ]);

            return [
                'session_id' => $session->id,
                'url' => $session->url,
            ];
        } catch (Exception $e) {
            Log::error('Stripe checkout session creation failed', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function retrieveCheckoutSession(string $sessionId): ?object
    {
        try {
            return Session::retrieve($sessionId);
        } catch (Exception $e) {
            Log::error('Stripe session retrieval failed', [
                'session_id' => $sessionId,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    public function createPaymentIntent(Payment $payment): array
    {
        try {
            $intent = PaymentIntent::create([
                'amount' => $this->toCents($payment->amount),
                'currency' => strtolower($this->currency),
                'metadata' => [
                    'payment_id' => $payment->id,
                    'payment_id_code' => $payment->payment_id,
                ],
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
            ]);

            return [
                'client_secret' => $intent->client_secret,
            ];
        } catch (Exception $e) {
            Log::error('Stripe payment intent creation failed', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function refund(string $transactionId, float $amount = null): void
    {
        try {
            $params = [
                'payment_intent' => $transactionId,
            ];

            if ($amount) {
                $params['amount'] = $this->toCents($amount);
            }

            Refund::create($params);

            Log::info('Stripe refund processed', [
                'transaction_id' => $transactionId,
                'amount' => $amount,
            ]);
        } catch (Exception $e) {
            Log::error('Stripe refund failed', [
                'transaction_id' => $transactionId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function verifyWebhook(string $payload, string $signature): array
    {
        try {
            $event = Webhook::constructEvent(
                $payload,
                $signature,
                $this->webhookSecret
            );

            return [
                'success' => true,
                'event' => $event,
            ];
        } catch (SignatureVerificationException $e) {
            Log::error('Stripe webhook verification failed', [
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => 'Webhook signature verification failed',
            ];
        }
    }

    public function getSessionStatus(string $sessionId): array
    {
        try {
            $session = Session::retrieve($sessionId);

            return [
                'status' => $session->payment_status,
                'amount' => $this->fromCents($session->amount_total),
                'customer_email' => $session->customer_details->email ?? null,
                'metadata' => $session->metadata->toArray(),
            ];
        } catch (Exception $e) {
            return [
                'status' => 'unknown',
                'error' => $e->getMessage(),
            ];
        }
    }

    protected function getPaymentDescription(Payment $payment): string
    {
        $descriptions = [
            'monthly_contribution' => 'Monthly Contribution Payment',
            'emergency_collection' => 'Emergency Collection Payment',
            'donation' => 'Donation',
            'event_fee' => 'Event Fee',
            'other' => 'General Payment',
        ];

        return $descriptions[$payment->type] ?? 'Foundation Payment';
    }

    protected function toCents(float $amount): int
    {
        return (int) round($amount * 100);
    }

    protected function fromCents(int $cents): float
    {
        return $cents / 100;
    }
}
