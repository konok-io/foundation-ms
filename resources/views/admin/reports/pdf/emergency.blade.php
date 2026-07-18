<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Emergency Fund Report</title>
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
        <p>Emergency Fund Report</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Collection</th>
                <th class="text-end">Amount/Member</th>
                <th class="text-end">Total Expected</th>
                <th class="text-end">Collected</th>
                <th class="text-end">Due</th>
            </tr>
        </thead>
        <tbody>
            @forelse($collections as $collection)
            @php $stat = $stats[$collection->id]; @endphp
            <tr>
                <td>{{ $collection->title }}</td>
                <td class="text-end">{{ number_format($collection->amount, 2) }}</td>
                <td class="text-end">{{ number_format($stat['total'], 2) }}</td>
                <td class="text-end">{{ number_format($stat['collected'], 2) }}</td>
                <td class="text-end">{{ number_format($stat['due'], 2) }}</td>
            </tr>
            @empty
            <tr><td colspan="5" style="text-align:center;">No records</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Generated on {{ now()->format('d M Y H:i') }}</p>
    </div>
</body>
</html>
