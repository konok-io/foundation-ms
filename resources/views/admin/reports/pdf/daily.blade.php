<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Daily Report - {{ $date }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; }
        .header p { margin: 5px 0; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f5f5f5; }
        .text-end { text-align: right; }
        .text-success { color: green; }
        .text-danger { color: red; }
        .summary { margin-top: 20px; }
        .footer { margin-top: 30px; text-align: center; font-size: 10px; color: #999; }
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ $settings['site_name'] }}</h2>
        <p>Daily Financial Report</p>
        <p>{{ date('l, d F Y', strtotime($date)) }}</p>
    </div>

    <div class="summary">
        <table>
            <tr>
                <td style="text-align: center; background: #e8f5e9;">
                    <strong>Total Income</strong><br>
                    <span class="text-success">{{ number_format($totalIncome, 2) }} SAR</span>
                </td>
                <td style="text-align: center; background: #ffebee;">
                    <strong>Total Expense</strong><br>
                    <span class="text-danger">{{ number_format($totalExpense, 2) }} SAR</span>
                </td>
                <td style="text-align: center; background: #e3f2fd;">
                    <strong>Net Balance</strong><br>
                    <span class="{{ $netBalance >= 0 ? 'text-success' : 'text-danger' }}">
                        {{ number_format($netBalance, 2) }} SAR
                    </span>
                </td>
            </tr>
        </table>
    </div>

    <h4>Income Details</h4>
    <table>
        <thead>
            <tr>
                <th>Voucher No</th>
                <th>Category</th>
                <th>Received From</th>
                <th class="text-end">Amount</th>
            </tr>
        </thead>
        <tbody>
            @forelse($incomes as $income)
            <tr>
                <td>{{ $income->voucher_no }}</td>
                <td>{{ $income->category->name ?? 'N/A' }}</td>
                <td>{{ $income->received_from ?? '-' }}</td>
                <td class="text-end text-success">{{ number_format($income->amount, 2) }}</td>
            </tr>
            @empty
            <tr><td colspan="4" style="text-align: center;">No income records</td></tr>
            @endforelse
            <tr style="background: #e8f5e9;">
                <td colspan="3"><strong>Total</strong></td>
                <td class="text-end"><strong>{{ number_format($totalIncome, 2) }}</strong></td>
            </tr>
        </tbody>
    </table>

    <h4>Expense Details</h4>
    <table>
        <thead>
            <tr>
                <th>Voucher No</th>
                <th>Category</th>
                <th>Payee</th>
                <th class="text-end">Amount</th>
            </tr>
        </thead>
        <tbody>
            @forelse($expenses as $expense)
            <tr>
                <td>{{ $expense->voucher_no }}</td>
                <td>{{ $expense->category->name ?? 'N/A' }}</td>
                <td>{{ $expense->payee_name ?? '-' }}</td>
                <td class="text-end text-danger">{{ number_format($expense->amount, 2) }}</td>
            </tr>
            @empty
            <tr><td colspan="4" style="text-align: center;">No expense records</td></tr>
            @endforelse
            <tr style="background: #ffebee;">
                <td colspan="3"><strong>Total</strong></td>
                <td class="text-end"><strong>{{ number_format($totalExpense, 2) }}</strong></td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>Generated on {{ now()->format('d M Y H:i') }} | {{ $settings['site_name'] }}</p>
    </div>
</body>
</html>
