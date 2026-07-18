<?php

namespace App\Services;

use App\Models\Receipt;
use App\Models\MonthlyContribution;
use App\Models\EmergencyCollectionPayment;
use App\Models\Donation;
use App\Models\GeneralSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ReceiptService
{
    public function createFromContribution(MonthlyContribution $contribution): Receipt
    {
        return Receipt::create([
            'receipt_no' => Receipt::generateReceiptNo('RCP'),
            'type' => 'monthly_contribution',
            'member_id' => $contribution->member_id,
            'contribution_id' => $contribution->id,
            'payment_id' => null,
            'amount' => $contribution->paid_amount,
            'currency' => 'SAR',
            'payment_method' => $contribution->payment_method,
            'paid_at' => $contribution->paid_date,
            'purpose' => 'Monthly Contribution - ' . $contribution->month_name . ' ' . $contribution->year,
            'description' => 'Monthly contribution payment for ' . $contribution->month_name . ', ' . $contribution->year,
            'created_by' => $contribution->paid_by,
        ]);
    }

    public function createFromEmergencyPayment(EmergencyCollectionPayment $payment): Receipt
    {
        $collection = $payment->emergencyCollection;

        return Receipt::create([
            'receipt_no' => Receipt::generateReceiptNo('ECR'),
            'type' => 'emergency_collection',
            'member_id' => $payment->member_id,
            'emergency_payment_id' => $payment->id,
            'payment_id' => null,
            'amount' => $payment->paid_amount,
            'currency' => 'SAR',
            'payment_method' => $payment->payment_method,
            'paid_at' => $payment->paid_date,
            'purpose' => 'Emergency Collection - ' . $collection->title,
            'description' => 'Payment for emergency collection: ' . $collection->title,
            'created_by' => auth()->id(),
        ]);
    }

    public function createFromDonation(Donation $donation): Receipt
    {
        return Receipt::create([
            'receipt_no' => Receipt::generateReceiptNo('DNR'),
            'type' => 'donation',
            'member_id' => $donation->member_id,
            'donation_id' => $donation->id,
            'payment_id' => $donation->payment_id,
            'amount' => $donation->amount,
            'currency' => 'SAR',
            'payment_method' => $donation->payment_method,
            'paid_at' => $donation->created_at,
            'purpose' => 'Donation - ' . ($donation->purpose ?? 'General'),
            'description' => $donation->notes ?? 'General donation',
            'created_by' => auth()->id(),
        ]);
    }

    public function generateQrCode(Receipt $receipt): string
    {
        $verificationUrl = route('receipt.verify', ['receipt_no' => $receipt->receipt_no]);
        
        $qrCode = QrCode::size(150)
            ->format('png')
            ->generate($verificationUrl);

        $filename = 'receipts/' . $receipt->receipt_no . '.png';
        Storage::disk('public')->put($filename, $qrCode);

        $receipt->update(['qr_code' => $filename]);

        return $filename;
    }

    public function generatePdf(Receipt $receipt): \Barryvdh\DomPDF\PDF
    {
        $receipt->load(['member', 'creator']);
        
        $settings = [
            'site_name' => GeneralSetting::getSetting('site_name', 'Foundation'),
            'site_address' => GeneralSetting::getSetting('address', ''),
            'site_phone' => GeneralSetting::getSetting('phone', ''),
            'site_email' => GeneralSetting::getSetting('email', ''),
        ];

        // Generate QR code if not exists
        if (!$receipt->qr_code) {
            $this->generateQrCode($receipt);
        }

        $data = [
            'receipt' => $receipt,
            'settings' => $settings,
            'qrCodePath' => $receipt->qr_code ? Storage::disk('public')->path($receipt->qr_code) : null,
        ];

        $pdf = Pdf::loadView('receipts.pdf', $data);
        $pdf->setPaper('A5', 'portrait');

        return $pdf;
    }

    public function downloadPdf(Receipt $receipt): \Illuminate\Http\Response
    {
        $pdf = $this->generatePdf($receipt);
        
        $receipt->update([
            'is_printed' => true,
            'printed_at' => now(),
        ]);

        return $pdf->download('Receipt-' . $receipt->receipt_no . '.pdf');
    }

    public function streamPdf(Receipt $receipt): \Illuminate\Http\Response
    {
        $pdf = $this->generatePdf($receipt);
        
        $receipt->update([
            'is_printed' => true,
            'printed_at' => now(),
        ]);

        return $pdf->stream('Receipt-' . $receipt->receipt_no . '.pdf');
    }

    public function sendEmail(Receipt $receipt): bool
    {
        try {
            $receipt->load(['member']);
            
            if (!$receipt->member || !$receipt->member->email) {
                Log::warning('Cannot send receipt email: No email address', [
                    'receipt_id' => $receipt->id,
                ]);
                return false;
            }

            $pdf = $this->generatePdf($receipt);
            $pdfContent = $pdf->output();

            \Mail::to($receipt->member->email)
                ->send(new \App\Mail\ReceiptMail($receipt, $pdfContent));

            $receipt->update([
                'is_emailed' => true,
                'emailed_at' => now(),
            ]);

            Log::info('Receipt email sent', [
                'receipt_id' => $receipt->id,
                'email' => $receipt->member->email,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send receipt email', [
                'receipt_id' => $receipt->id,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    public function bulkEmail(array $receiptIds): array
    {
        $sent = 0;
        $failed = 0;

        foreach ($receiptIds as $id) {
            $receipt = Receipt::find($id);
            
            if ($receipt && $this->sendEmail($receipt)) {
                $sent++;
            } else {
                $failed++;
            }
        }

        return [
            'sent' => $sent,
            'failed' => $failed,
        ];
    }
}
