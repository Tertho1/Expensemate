<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ExportController extends Controller
{
    public function index()
    {
        return view('export');
    }

    public function downloadPdf(Request $request)
    {
        $userId = Auth::id();

        if (!$userId) {
            return redirect()->route('login');
        }

        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $transactions = Transaction::with('category')
            ->where('user_id', $userId)
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'desc')
            ->get();

        // Calculate totals
        $totalIncome = $transactions->where('type', 'income')->sum('amount');
        $totalExpense = $transactions->where('type', 'expense')->sum('amount');
        $balance = $totalIncome - $totalExpense;

        // Generate PDF
        $pdf = Pdf::loadView('exports.transactions_pdf', compact(
            'transactions',
            'startDate',
            'endDate',
            'totalIncome',
            'totalExpense',
            'balance'
        ));

        return $pdf->download('transactions-' . Carbon::now()->format('Y-m-d') . '.pdf');
    }

    public function downloadCsv(Request $request)
    {
        $userId = Auth::id();

        if (!$userId) {
            return redirect()->route('login');
        }

        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        // Get transactions
        $transactions = Transaction::with('category')
            ->where('user_id', $userId)
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'desc')
            ->get();

        // Create CSV data
        $csvData = "Date,Type,Category,Amount,Notes\n";

        foreach ($transactions as $transaction) {
            $date = $transaction->date instanceof Carbon ? $transaction->date->format('Y-m-d') : $transaction->date;
            $csvData .= $date . ",";
            $csvData .= $transaction->type . ",";
            $csvData .= $transaction->category->name . ",";
            $csvData .= $transaction->amount . ",";
            $csvData .= str_replace(",", " ", $transaction->note ?? '') . "\n";
        }

        // Set headers for download
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="transactions-' . Carbon::now()->format('Y-m-d') . '.csv"',
        ];

        return response($csvData, 200, $headers);
    }
}
