<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    
    <div style="background: #0d6efd; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0;">
        <h1 style="margin: 0;">{{ $settings['site_name'] ?? 'Foundation' }}</h1>
        <p style="margin: 5px 0 0 0; opacity: 0.9;">Payment Receipt</p>
    </div>
    
    <div style="background: #f8f9fa; padding: 20px; border: 1px solid #ddd;">
        <h2 style="color: #0d6efd; text-align: center; margin-top: 0;">Receipt #{{ $receipt->receipt_no }}</h2>
        
        <table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
            <tr>
                <td style="padding: 8px 0; color: #666;">Receipt Type</td>
                <td style="padding: 8px 0; text-align: right; font-weight: bold;">
                    {{ str_replace('_', ' ', ucfirst($receipt->type)) }}
                </td>
            </tr>
            @if($receipt->member)
            <tr>
                <td style="padding: 8px 0; color: #666;">Member Name</td>
                <td style="padding: 8px 0; text-align: right; font-weight: bold;">
                    {{ $receipt->member->name }}
                </td>
            </tr>
            @endif
            <tr>
                <td style="padding: 8px 0; color: #666;">Payment Date</td>
                <td style="padding: 8px 0; text-align: right; font-weight: bold;">
                    {{ $receipt->paid_at?->format('d M Y, h:i A') }}
                </td>
            </tr>
            <tr>
                <td style="padding: 8px 0; color: #666;">Payment Method</td>
                <td style="padding: 8px 0; text-align: right; font-weight: bold;">
                    {{ ucfirst(str_replace('_', ' ', $receipt->payment_method)) }}
                </td>
            </tr>
            @if($receipt->purpose)
            <tr>
                <td style="padding: 8px 0; color: #666;">Purpose</td>
                <td style="padding: 8px 0; text-align: right;">
                    {{ $receipt->purpose }}
                </td>
            </tr>
            @endif
        </table>
        
        <div style="background: #0d6efd; color: white; padding: 20px; text-align: center; border-radius: 8px; margin: 20px 0;">
            <div style="font-size: 12px; opacity: 0.9;">Amount Paid</div>
            <div style="font-size: 28px; font-weight: bold;">
                {{ number_format($receipt->amount, 2) }} {{ $receipt->currency }}
            </div>
        </div>
        
        <p style="text-align: center; color: #666; font-size: 14px; margin-top: 20px;">
            Please find your official receipt as an attachment to this email.<br>
            You can also verify this receipt at any time using the link below.
        </p>
        
        <p style="text-align: center; margin-top: 20px;">
            <a href="{{ route('receipt.verify', ['receipt_no' => $receipt->receipt_no]) }}" 
               style="display: inline-block; background: #0d6efd; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px;">
                Verify Receipt Online
            </a>
        </p>
    </div>
    
    <div style="text-align: center; padding: 20px; color: #999; font-size: 12px; border-top: 1px solid #ddd;">
        <p style="margin: 0 0 10px 0;">
            {{ $settings['site_name'] ?? 'Foundation' }}<br>
            {{ $settings['site_email'] ?? '' }}
        </p>
        <p style="margin: 0; opacity: 0.7;">
            This is an automated message. Please do not reply to this email.
        </p>
    </div>
    
</body>
</html>
