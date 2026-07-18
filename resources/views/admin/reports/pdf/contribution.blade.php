<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Member Contribution Report</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; }
        .header { text-align: center; margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { border: 1px solid #ddd; padding: 6px; }
        th { background: #f5f5f5; }
        .text-end { text-align: right; }
        .footer { margin-top: 20px; text-align: center; font-size: 9px; color: #999; }
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ $settings['site_name'] }}</h2>
        <p>Member Contribution Report</p>
    </div>

    <div style="display: table; width: 100%; margin-bottom: 15px;">
        <div style="display: table-cell; text-align: center; padding: 10px;"><strong>Total</strong><br>{{ $stats['total'] }}</div>
        <div style="display: table-cell; text-align: center; background: #e8f5e9; padding: 10px;"><strong>Paid</strong><br>{{ $stats['paid'] }}</div>
        <div style="display: table-cell; text-align: center; background: #fff3e0; padding: 10px;"><strong>Partial</strong><br>{{ $stats['partial'] }}</div>
        <div style="display: table-cell; text-align: center; background: #ffebee; padding: 10px;"><strong>Unpaid</strong><br>{{ $stats['unpaid'] }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Member</th>
                <th>Period</th>
                <th class="text-end">Amount</th>
                <th class="text-end">Paid</th>
                <th class="text-end">Due</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($contributions as $c)
            <tr>
                <td>{{ $c->member?->name ?? 'N/A' }}</td>
                <td>{{ $c->month_name }} {{ $c->year }}</td>
                <td class="text-end">{{ number_format($c->amount, 2) }}</td>
                <td class="text-end">{{ number_format($c->paid_amount, 2) }}</td>
                <td class="text-end">{{ number_format($c->due_amount, 2) }}</td>
                <td>{{ ucfirst($c->status) }}</td>
            </tr>
            @empty
            <tr><td colspan="6" style="text-align:center;">No records</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Generated on {{ now()->format('d M Y H:i') }}</p>
    </div>
</body>
</html>
