<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Outstanding Due Report</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; }
        .header { text-align: center; margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { border: 1px solid #ddd; padding: 6px; }
        th { background: #f5f5f5; }
        .text-end { text-align: right; }
        .text-danger { color: red; }
        .footer { margin-top: 20px; text-align: center; font-size: 9px; color: #999; }
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ $settings['site_name'] }}</h2>
        <p>Outstanding Due Report</p>
        <p>{{ $memberCount }} members with outstanding dues</p>
    </div>

    <div style="text-align: center; margin-bottom: 15px; background: #ffebee; padding: 15px;">
        <strong>Total Outstanding Due:</strong> <span class="text-danger">{{ number_format($totalDue, 2) }} SAR</span>
    </div>

    <table>
        <thead>
            <tr>
                <th>Member ID</th>
                <th>Name</th>
                <th>Contact</th>
                <th class="text-end">Due Count</th>
                <th class="text-end">Due Amount</th>
            </tr>
        </thead>
        <tbody>
            @forelse($membersWithDue as $item)
            <tr>
                <td>{{ $item['member']->member_id }}</td>
                <td>{{ $item['member']->name }}</td>
                <td>{{ $item['member']->phone ?? '-' }}</td>
                <td class="text-end">{{ $item['due_count'] }}</td>
                <td class="text-end text-danger">{{ number_format($item['due_amount'], 2) }}</td>
            </tr>
            @empty
            <tr><td colspan="5" style="text-align:center;">No outstanding dues</td></tr>
            @endforelse
            <tr style="background: #f5f5f5;">
                <td colspan="4" class="text-end"><strong>Total</strong></td>
                <td class="text-end text-danger"><strong>{{ number_format($totalDue, 2) }}</strong></td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>Generated on {{ now()->format('d M Y H:i') }}</p>
    </div>
</body>
</html>
