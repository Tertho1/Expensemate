
// Create file: resources/views/exports/transactions_pdf.blade.php
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Transaction Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .subtitle {
            font-size: 14px;
            margin-bottom: 10px;
        }
        .summary {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .income {
            color: green;
        }
        .expense {
            color: red;
        }
        .footer {
            margin-top: 20px;
            font-size: 10px;
            text-align: center;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Transaction Report</div>
        <div class="subtitle">{{ Carbon\Carbon::parse($startDate)->format('M d, Y') }} - {{ Carbon\Carbon::parse($endDate)->format('M d, Y') }}</div>
        <div class="subtitle">Generated on {{ Carbon\Carbon::now()->format('M d, Y h:i A') }}</div>
    </div>
    
    <div class="summary">
        <h3>Financial Summary</h3>
        <div class="summary-row">
            <span>Total Income:</span>
            <span class="income">${{ number_format($totalIncome, 2) }}</span>
        </div>
        <div class="summary-row">
            <span>Total Expenses:</span>
            <span class="expense">${{ number_format($totalExpense, 2) }}</span>
        </div>
        <div class="summary-row">
            <span>Balance:</span>
            <span class="{{ $balance >= 0 ? 'income' : 'expense' }}">${{ number_format($balance, 2) }}</span>
        </div>
    </div>
    
    <h3>Transaction History</h3>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Type</th>
                <th>Category</th>
                <th>Amount</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $transaction)
            <tr>
                <td>{{ $transaction->date->format('M d, Y') }}</td>
                <td>{{ ucfirst($transaction->type) }}</td>
                <td>{{ $transaction->category->name }}</td>
                <td class="{{ $transaction->type === 'income' ? 'income' : 'expense' }}">
                    {{ $transaction->type === 'income' ? '+' : '-' }}${{ number_format($transaction->amount, 2) }}
                </td>
                <td>{{ $transaction->note }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="footer">
        <p>This report was generated using ExpenseMate. All amounts are in USD.</p>
    </div>
</body>
</html>
