<?php

namespace App\Mail;

use App\Models\Receipt;
use App\Models\GeneralSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReceiptMail extends Mailable
{
    use Queueable, SerializesModels;

    public Receipt $receipt;
    public string $pdfContent;
    public array $settings;

    public function __construct(Receipt $receipt, string $pdfContent)
    {
        $this->receipt = $receipt;
        $this->pdfContent = $pdfContent;
        $this->settings = [
            'site_name' => GeneralSetting::getSetting('site_name', 'Foundation'),
            'site_email' => GeneralSetting::getSetting('email', 'noreply@example.com'),
        ];
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Payment Receipt - ' . $this->receipt->receipt_no,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.receipt',
            with: [
                'receipt' => $this->receipt,
                'settings' => $this->settings,
            ],
        );
    }

    public function attachments(): array
    {
        return [
            \Illuminate\Mail\Mailables\Attachment::fromData(
                fn () => $this->pdfContent,
                'Receipt-' . $this->receipt->receipt_no . '.pdf'
            )->withMime('application/pdf'),
        ];
    }
}
