<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - {{ $receipt->receipt_no }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
        }
        
        .container {
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
            padding: 15px;
        }
        
        .header {
            text-align: center;
            border-bottom: 2px solid #0d6efd;
            padding-bottom: 15px;
            margin-bottom: 15px;
        }
        
        .logo {
            font-size: 20px;
            font-weight: bold;
            color: #0d6efd;
            margin-bottom: 5px;
        }
        
        .org-name {
            font-size: 14px;
            font-weight: bold;
            color: #333;
        }
        
        .address {
            font-size: 9px;
            color: #666;
            margin-top: 5px;
        }
        
        .receipt-title {
            background: #0d6efd;
            color: white;
            text-align: center;
            padding: 8px;
            font-size: 14px;
            font-weight: bold;
            margin: 15px 0;
            border-radius: 4px;
        }
        
        .receipt-no {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            color: #0d6efd;
            margin-bottom: 15px;
        }
        
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        
        .info-table td {
            padding: 6px 0;
            vertical-align: top;
        }
        
        .info-table td:first-child {
            width: 40%;
            color: #666;
        }
        
        .info-table td:last-child {
            font-weight: bold;
        }
        
        .amount-box {
            background: #f8f9fa;
            border: 2px solid #0d6efd;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            margin: 15px 0;
        }
        
        .amount-label {
            font-size: 10px;
            color: #666;
            text-transform: uppercase;
        }
        
        .amount-value {
            font-size: 24px;
            font-weight: bold;
            color: #0d6efd;
        }
        
        .amount-currency {
            font-size: 12px;
            color: #666;
        }
        
        .qr-section {
            text-align: center;
            margin: 20px 0;
            padding: 15px;
            border-top: 1px dashed #ddd;
            border-bottom: 1px dashed #ddd;
        }
        
        .qr-code img {
            width: 120px;
            height: 120px;
        }
        
        .qr-text {
            font-size: 8px;
            color: #666;
            margin-top: 5px;
        }
        
        .footer {
            text-align: center;
            font-size: 8px;
            color: #999;
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #eee;
        }
        
        .verification {
            font-size: 8px;
            color: #0d6efd;
            margin-top: 5px;
        }
        
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="org-name">{{ $settings['site_name'] ?? 'Foundation' }}</div>
            <div class="address">
                {{ $settings['site_address'] ?? '' }}<br>
                {{ $settings['site_phone'] ?? '' }} | {{ $settings['site_email'] ?? '' }}
            </div>
        </div>
        
        <div class="receipt-title">📄 PAYMENT RECEIPT</div>
        
        <div class="receipt-no">{{ $receipt->receipt_no }}</div>
        
        <table class="info-table">
            <tr>
                <td>Receipt Type</td>
                <td>{{ str_replace('_', ' ', ucfirst($receipt->type)) }}</td>
            </tr>
            @if($receipt->member)
            <tr>
                <td>Member Name</td>
                <td>{{ $receipt->member->name }}</td>
            </tr>
            <tr>
                <td>Member ID</td>
                <td>{{ $receipt->member->member_id }}</td>
            </tr>
            @endif
            <tr>
                <td>Payment Date</td>
                <td>{{ $receipt->paid_at->format('d M Y, h:i A') }}</td>
            </tr>
            <tr>
                <td>Payment Method</td>
                <td>{{ ucfirst(str_replace('_', ' ', $receipt->payment_method)) }}</td>
            </tr>
            @if($receipt->purpose)
            <tr>
                <td>Purpose</td>
                <td>{{ $receipt->purpose }}</td>
            </tr>
            @endif
            @if($receipt->description)
            <tr>
                <td>Description</td>
                <td>{{ $receipt->description }}</td>
            </tr>
            @endif
        </table>
        
        <div class="amount-box">
            <div class="amount-label">Amount Paid</div>
            <div class="amount-value">{{ number_format($receipt->amount, 2) }}</div>
            <div class="amount-currency">{{ $receipt->currency }}</div>
        </div>
        
        @if($qrCodePath)
        <div class="qr-section">
            <div class="qr-code">
                <img src="{{ $qrCodePath }}" alt="QR Code">
            </div>
            <div class="qr-text">Scan to verify this receipt</div>
        </div>
        @endif
        
        <div class="footer">
            <p>This is a computer-generated receipt and does not require a signature.</p>
            <p>Generated on: {{ now()->format('d M Y, h:i A') }}</p>
            @if(isset($receipt->creator) && $receipt->creator)
            <p>Issued by: {{ $receipt->creator->name }}</p>
            @endif
            <p class="verification">Verify at: {{ route('receipt.verify', ['receipt_no' => $receipt->receipt_no]) }}</p>
        </div>
    </div>
</body>
</html>
