<?php

namespace App\Services;

use App\Models\GeneralSetting;
use App\Models\Payment;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PayPalService
{
    protected ?string $clientId = null;
    protected ?string $clientSecret = null;
    protected string $mode;
    protected string $baseUrl;
    protected ?string $accessToken = null;

    public function __construct()
    {
        $this->clientId = GeneralSetting::getSetting('paypal_client_id', config('services.paypal.client_id')) ?: null;
        $this->clientSecret = GeneralSetting::getSetting('paypal_client_secret', config('services.paypal.client_secret')) ?: null;
        $this->mode = GeneralSetting::getSetting('paypal_mode', config('services.paypal.mode', 'sandbox')) ?: 'sandbox';
        
        $this->baseUrl = $this->mode === 'live' 
            ? 'https://api-m.paypal.com' 
            : 'https://api-m.sandbox.paypal.com';
    }

    protected function getAccessToken(): string
    {
        if ($this->accessToken) {
            return $this->accessToken;
        }

        $response = Http::withBasicAuth($this->clientId, $this->clientSecret)
            ->asForm()
            ->post($this->baseUrl . '/v1/oauth2/token', [
                'grant_type' => 'client_credentials',
            ]);

        if ($response->successful()) {
            $this->accessToken = $response->json()['access_token'];
            return $this->accessToken;
        }

        throw new Exception('Failed to get PayPal access token: ' . $response->body());
    }

    protected function headers(): array
    {
        return [
            'Authorization' => 'Bearer ' . $this->getAccessToken(),
            'Content-Type' => 'application/json',
        ];
    }

    public function createOrder(Payment $payment, string $returnUrl, string $cancelUrl): array
    {
        try {
            $orderPayload = [
                'intent' => 'CAPTURE',
                'application_context' => [
                    'return_url' => $returnUrl,
                    'cancel_url' => $cancelUrl,
                    'brand_name' => GeneralSetting::getSetting('site_name', 'Foundation'),
                    'landing_page' => 'BILLING',
                    'user_action' => 'PAY_NOW',
                ],
                'purchase_units' => [
                    [
                        'reference_id' => $payment->payment_id,
                        'description' => $this->getPaymentDescription($payment),
                        'custom_id' => (string) $payment->id,
                        'amount' => [
                            'currency_code' => 'USD',
                            'value' => number_format($this->convertCurrency($payment->amount, 'SAR', 'USD'), 2, '.', ''),
                        ],
                    ],
                ],
            ];

            $response = Http::withHeaders($this->headers())
                ->post($this->baseUrl . '/v2/checkout/orders', $orderPayload);

            if ($response->successful()) {
                $data = $response->json();

                Log::info('PayPal order created', [
                    'payment_id' => $payment->id,
                    'order_id' => $data['id'],
                ]);

                $approvalUrl = collect($data['links'])->firstWhere('rel', 'approve')['href'];

                return [
                    'order_id' => $data['id'],
                    'url' => $approvalUrl,
                ];
            }

            throw new Exception('PayPal order creation failed: ' . $response->body());
        } catch (Exception $e) {
            Log::error('PayPal order creation error', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function captureOrder(string $orderId): array
    {
        try {
            $response = Http::withHeaders($this->headers())
                ->post($this->baseUrl . '/v2/checkout/orders/' . $orderId . '/capture');

            if ($response->successful()) {
                $data = $response->json();

                Log::info('PayPal order captured', [
                    'order_id' => $orderId,
                    'status' => $data['status'],
                ]);

                return $data;
            }

            throw new Exception('PayPal capture failed: ' . $response->body());
        } catch (Exception $e) {
            Log::error('PayPal capture error', [
                'order_id' => $orderId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function getOrder(string $orderId): ?array
    {
        try {
            $response = Http::withHeaders($this->headers())
                ->get($this->baseUrl . '/v2/checkout/orders/' . $orderId);

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (Exception $e) {
            Log::error('PayPal get order error', [
                'order_id' => $orderId,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    public function refundOrder(string $captureId, float $amount): void
    {
        try {
            $refundPayload = [
                'amount' => [
                    'currency_code' => 'USD',
                    'value' => number_format($this->convertCurrency($amount, 'SAR', 'USD'), 2, '.', ''),
                ],
                'invoice_id' => 'REF-' . uniqid(),
            ];

            $response = Http::withHeaders($this->headers())
                ->post($this->baseUrl . '/v2/payments/captures/' . $captureId . '/refund', $refundPayload);

            if (!$response->successful()) {
                throw new Exception('PayPal refund failed: ' . $response->body());
            }

            Log::info('PayPal refund processed', [
                'capture_id' => $captureId,
                'amount' => $amount,
            ]);
        } catch (Exception $e) {
            Log::error('PayPal refund error', [
                'capture_id' => $captureId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function verifyWebhook(array $payload, array $headers): bool
    {
        try {
            $response = Http::withHeaders($this->headers())
                ->post($this->baseUrl . '/v1/notifications/verify-webhook-signature', [
                    'auth_algo' => $headers['PAYPAL-AUTH-ALGO'] ?? '',
                    'cert_url' => $headers['PAYPAL-CERT-URL'] ?? '',
                    'transmission_id' => $headers['PAYPAL-TRANSMISSION-ID'] ?? '',
                    'transmission_sig' => $headers['PAYPAL-TRANSMISSION-SIG'] ?? '',
                    'transmission_time' => $headers['PAYPAL-TRANSMISSION-TIME'] ?? '',
                    'webhook_id' => GeneralSetting::getSetting('paypal_webhook_id'),
                    'webhook_event' => $payload,
                ]);

            return $response->successful() && $response->json()['verification_status'] === 'SUCCESS';
        } catch (Exception $e) {
            Log::error('PayPal webhook verification error', [
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    protected function getPaymentDescription(Payment $payment): string
    {
        $descriptions = [
            'monthly_contribution' => 'Monthly Contribution Payment',
            'emergency_collection' => 'Emergency Collection Payment',
            'donation' => 'Donation',
            'event_fee' => 'Event Fee',
            'other' => 'Foundation Payment',
        ];

        return $descriptions[$payment->type] ?? 'Foundation Payment';
    }

    protected function convertCurrency(float $amount, string $from, string $to): float
    {
        if ($from === $to) {
            return $amount;
        }

        $rates = [
            'SAR_USD' => 0.27,
            'USD_SAR' => 3.75,
        ];

        $key = $from . '_' . $to;
        
        return $amount * ($rates[$key] ?? 1);
    }
}
