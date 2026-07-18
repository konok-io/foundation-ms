<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Yearly Report - {{ $year }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 10px; }
        .header { text-align: center; margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { border: 1px solid #ddd; padding: 4px; font-size: 9px; }
        th { background: #f5f5f5; }
        .text-end { text-align: right; }
        .text-success { color: green; }
        .text-danger { color: red; }
        .footer { margin-top: 20px; text-align: center; font-size: 9px; color: #999; }
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ $settings['site_name'] }}</h2>
        <p>Yearly Financial Report - {{ $year }}</p>
    </div>

    <div style="display: table; width: 100%; margin-bottom: 15px;">
        <div style="display: table-cell; text-align: center; background: #e8f5e9; padding: 10px;">
            <strong>Total Income</strong><br>
            <span class="text-success">{{ number_format($totalIncome, 2) }} SAR</span>
        </div>
        <div style="display: table-cell; text-align: center; background: #ffebee; padding: 10px;">
            <strong>Total Expense</strong><br>
            <span class="text-danger">{{ number_format($totalExpense, 2) }} SAR</span>
        </div>
        <div style="display: table-cell; text-align: center; background: #e3f2fd; padding: 10px;">
            <strong>Net Balance</strong><br>
            <span class="{{ $netBalance >= 0 ? 'text-success' : 'text-danger' }}">{{ number_format($netBalance, 2) }} SAR</span>
        </div>
    </div>

    <h4>Monthly Breakdown</h4>
    <table>
        <thead>
            <tr>
                <th>Month</th>
                <th class="text-end">Income</th>
                <th class="text-end">Expense</th>
                <th class="text-end">Balance</th>
            </tr>
        </thead>
        <tbody>
            @foreach(range(1, 12) as $m)
            <tr>
                <td>{{ date('F', mktime(0, 0, 0, $m, 1)) }}</td>
                <td class="text-end text-success">{{ number_format($incomeByMonth[$m] ?? 0, 2) }}</td>
                <td class="text-end text-danger">{{ number_format($expenseByMonth[$m] ?? 0, 2) }}</td>
                <td class="text-end">{{ number_format(($incomeByMonth[$m] ?? 0) - ($expenseByMonth[$m] ?? 0), 2) }}</td>
            </tr>
            @endforeach
            <tr style="background: #f5f5f5;">
                <td><strong>Total</strong></td>
                <td class="text-end text-success"><strong>{{ number_format($totalIncome, 2) }}</strong></td>
                <td class="text-end text-danger"><strong>{{ number_format($totalExpense, 2) }}</strong></td>
                <td class="text-end"><strong>{{ number_format($netBalance, 2) }}</strong></td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>Generated on {{ now()->format('d M Y H:i') }}</p>
    </div>
</body>
</html>
