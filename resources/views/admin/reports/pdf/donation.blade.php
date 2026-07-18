<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Donation Report</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; }
        .header { text-align: center; margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { border: 1px solid #ddd; padding: 6px; }
        th { background: #f5f5f5; }
        .text-end { text-align: right; }
        .text-success { color: green; }
        .footer { margin-top: 20px; text-align: center; font-size: 9px; color: #999; }
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ $settings['site_name'] }}</h2>
        <p>Donation Report</p>
    </div>

    <div style="text-align: center; margin-bottom: 15px;">
        <strong>Total Donations:</strong> {{ number_format($totalDonations, 2) }} SAR |
        <strong>Total Donors:</strong> {{ $totalCount }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Donor</th>
                <th>Email</th>
                <th class="text-end">Amount</th>
                <th>Purpose</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($donations as $d)
            <tr>
                <td>{{ $d->display_name }}</td>
                <td>{{ $d->email ?? '-' }}</td>
                <td class="text-end text-success">{{ number_format($d->amount, 2) }}</td>
                <td>{{ ucfirst($d->purpose) }}</td>
                <td>{{ $d->received_at?->format('d M Y') ?? '-' }}</td>
            </tr>
            @empty
            <tr><td colspan="5" style="text-align:center;">No donations</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Generated on {{ now()->format('d M Y H:i') }}</p>
    </div>
</body>
</html>
