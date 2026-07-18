<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Payment;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QRVerificationController extends Controller
{
    public function verify($code)
    {
        // Try to find member by QR code
        $member = Member::where('member_id', $code)
            ->orWhere('qr_code', $code)
            ->first();

        if (!$member) {
            return view('public.verify.error', [
                'title' => 'Verification Failed',
                'message' => 'No record found for the provided QR code.',
            ]);
        }

        return view('public.verify.member', [
            'title' => 'Member Verified',
            'member' => $member,
        ]);
    }

    public function paymentVerify($receiptNumber)
    {
        $payment = Payment::where('receipt_number', $receiptNumber)->first();

        if (!$payment) {
            return view('public.verify.error', [
                'title' => 'Verification Failed',
                'message' => 'No payment record found for the provided receipt number.',
            ]);
        }

        return view('public.verify.payment', [
            'title' => 'Payment Verified',
            'payment' => $payment,
        ]);
    }

    public function generateMemberQR($memberId)
    {
        $member = Member::findOrFail($memberId);
        
        $qrCode = QrCode::size(300)->generate(route('verify.member', $member->member_id));
        
        return view('admin.members.qr-code', [
            'title' => 'Member QR Code',
            'page_title' => 'QR Code for ' . $member->name,
            'member' => $member,
            'qrCode' => $qrCode,
        ]);
    }

    public function generatePaymentQR(Payment $payment)
    {
        $qrCode = QrCode::size(300)->generate(route('verify.payment', $payment->receipt_number));
        
        return view('admin.payments.qr-code', [
            'title' => 'Payment QR Code',
            'page_title' => 'QR Code for Receipt ' . $payment->receipt_number,
            'payment' => $payment,
            'qrCode' => $qrCode,
        ]);
    }

    public function downloadMemberQR($memberId)
    {
        $member = Member::findOrFail($memberId);
        
        $qrCode = QrCode::format('png')
            ->size(300)
            ->generate(route('verify.member', $member->member_id));
        
        $filename = 'QR_' . $member->member_id . '.png';
        
        return response($qrCode)
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    public function downloadPaymentQR(Payment $payment)
    {
        $qrCode = QrCode::format('png')
            ->size(300)
            ->generate(route('verify.payment', $payment->receipt_number));
        
        $filename = 'QR_Receipt_' . $payment->receipt_number . '.png';
        
        return response($qrCode)
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}
