<!DOCTYPE html>
<html>

<head>
    <title>Transaction Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .summary {
            margin-bottom: 30px;
        }

        .summary-item {
            display: inline-block;
            margin: 10px 20px;
            padding: 10px;
            border: 1px solid #ddd;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f5f5f5;
        }

        .income {
            color: green;
        }

        .expense {
            color: red;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>ExpenseMate Transaction Report</h1>
        @if($startDate || $endDate)
            <p>
                @if($startDate && $endDate)
                    Period: {{ Carbon\Carbon::parse($startDate)->format('M d, Y') }} -
                    {{ Carbon\Carbon::parse($endDate)->format('M d, Y') }}
                @elseif($startDate)
                    From: {{ Carbon\Carbon::parse($startDate)->format('M d, Y') }}
                @else
                    Until: {{ Carbon\Carbon::parse($endDate)->format('M d, Y') }}
                @endif
            </p>
        @endif
        <p>Generated on {{ now()->format('M d, Y \a\t g:i A') }}</p>
    </div>

    <div class="summary">
        <h2>Summary</h2>
        <div class="summary-item">
            <strong>Total Income:</strong><br>
            <span class="income">${{ number_format($totalIncome, 2) }}</span>
        </div>
        <div class="summary-item">
            <strong>Total Expenses:</strong><br>
            <span class="expense">${{ number_format($totalExpense, 2) }}</span>
        </div>
        <div class="summary-item">
            <strong>Net Balance:</strong><br>
            <span class="{{ $balance >= 0 ? 'income' : 'expense' }}">${{ number_format($balance, 2) }}</span>
        </div>
        <div class="summary-item">
            <strong>Total Transactions:</strong><br>
            {{ $transactions->count() }}
        </div>
    </div>

    @if($categoryTotals->count() > 0)
        <div class="category-breakdown">
            <h2>Category Breakdown</h2>
            <table>
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Type</th>
                        <th>Count</th>
                        <th>Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categoryTotals as $categoryName => $data)
                        <tr>
                            <td>{{ $categoryName }}</td>
                            <td>{{ ucfirst($data['type']) }}</td>
                            <td class="text-center">{{ $data['count'] }}</td>
                            <td class="{{ $data['type'] === 'income' ? 'income' : 'expense' }}">
                                ${{ number_format($data['total'], 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <div class="transactions">
        <h2>Transaction Details</h2>
        @if($transactions->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Category</th>
                        <th>Amount</th>
                        <th>Note</th>
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
                            <td>{{ $transaction->note ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No transactions found for the selected period.</p>
        @endif
    </div>
</body>

</html>