<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Monthly Report - {{ $monthName }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; }
        .header { text-align: center; margin-bottom: 15px; }
        .header h2 { margin: 0; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; font-size: 10px; }
        th, td { border: 1px solid #ddd; padding: 5px; }
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
        <p>Monthly Financial Report - {{ $monthName }}</p>
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

    <div style="display: table; width: 100%;">
        <div style="display: table-cell; width: 48%; vertical-align: top; padding-right: 2%;">
            <h4>Income by Category</h4>
            <table>
                <thead><tr><th>Category</th><th class="text-end">Amount</th></tr></thead>
                <tbody>
                    @forelse($incomeByCategory as $catId => $amount)
                    <tr>
                        <td>{{ $incomes->firstWhere('category_id', $catId)?->category->name ?? 'N/A' }}</td>
                        <td class="text-end text-success">{{ number_format($amount, 2) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="2" style="text-align:center;">No income</td></tr>
                    @endforelse
                    <tr style="background:#e8f5e9;"><td><strong>Total</strong></td><td class="text-end"><strong>{{ number_format($totalIncome, 2) }}</strong></td></tr>
                </tbody>
            </table>
        </div>
        <div style="display: table-cell; width: 48%; vertical-align: top; padding-left: 2%;">
            <h4>Expense by Category</h4>
            <table>
                <thead><tr><th>Category</th><th class="text-end">Amount</th></tr></thead>
                <tbody>
                    @forelse($expenseByCategory as $catId => $amount)
                    <tr>
                        <td>{{ $expenses->firstWhere('category_id', $catId)?->category->name ?? 'N/A' }}</td>
                        <td class="text-end text-danger">{{ number_format($amount, 2) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="2" style="text-align:center;">No expense</td></tr>
                    @endforelse
                    <tr style="background:#ffebee;"><td><strong>Total</strong></td><td class="text-end"><strong>{{ number_format($totalExpense, 2) }}</strong></td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="footer">
        <p>Generated on {{ now()->format('d M Y H:i') }}</p>
    </div>
</body>
</html>
