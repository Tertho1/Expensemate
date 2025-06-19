<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ExportController extends Controller
{
    public function index()
    {
        return view('export');
    }

    public function csv(Request $request)
    {
        $userId = Auth::id();
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $query = Transaction::with('category')
            ->where('user_id', $userId)
            ->orderBy('date', 'desc');

        if ($startDate) {
            $query->whereDate('date', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('date', '<=', $endDate);
        }

        $transactions = $query->get();

        $filename = 'transactions_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
            'Pragma' => 'public',
        ];

        $callback = function () use ($transactions) {
            $output = fopen('php://output', 'w');

            // Add BOM for proper UTF-8 encoding in Excel
            fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Add CSV headers with wider spacing to accommodate data
            fputcsv($output, [
                'Transaction Date', // Longer header name
                'Type',
                'Category Name',
                'Amount ($)',
                'Notes'
            ]);

            // Add data rows with optimized formatting
            foreach ($transactions as $transaction) {
                $row = [
                    // Use a date format that Excel recognizes and displays properly
                    $transaction->date->format('Y-m-d'), // ISO format works best
                    ucfirst($transaction->type),
                    $transaction->category->name,
                    // Remove number_format to avoid string issues in Excel
                    $transaction->amount, // Let Excel handle the number formatting
                    $transaction->note ?? ''
                ];

                fputcsv($output, $row);
            }

            fclose($output);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function excel(Request $request)
    {
        $userId = Auth::id();
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $query = Transaction::with('category')
            ->where('user_id', $userId)
            ->orderBy('date', 'desc');

        if ($startDate) {
            $query->whereDate('date', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('date', '<=', $endDate);
        }

        $transactions = $query->get();

        // Calculate totals for summary
        $totalIncome = $transactions->where('type', 'income')->sum('amount');
        $totalExpense = $transactions->where('type', 'expense')->sum('amount');
        $balance = $totalIncome - $totalExpense;

        // Create new Spreadsheet
        $spreadsheet = new Spreadsheet();

        // Set document properties
        $spreadsheet->getProperties()
            ->setCreator('ExpenseMate')
            ->setTitle('Transaction Report')
            ->setSubject('Financial Transaction Export')
            ->setDescription('Transaction data exported from ExpenseMate');

        // Get active sheet
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Transactions');

        // Add header with logo/title
        $sheet->setCellValue('A1', 'ExpenseMate Transaction Report');
        $sheet->mergeCells('A1:E1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Add date range
        $dateRange = '';
        if ($startDate && $endDate) {
            $dateRange = 'Period: ' . Carbon::parse($startDate)->format('M d, Y') . ' - ' . Carbon::parse($endDate)->format('M d, Y');
        } elseif ($startDate) {
            $dateRange = 'From: ' . Carbon::parse($startDate)->format('M d, Y');
        } elseif ($endDate) {
            $dateRange = 'Until: ' . Carbon::parse($endDate)->format('M d, Y');
        } else {
            $dateRange = 'All Transactions';
        }

        $sheet->setCellValue('A2', $dateRange);
        $sheet->mergeCells('A2:E2');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Add generation date
        $sheet->setCellValue('A3', 'Generated on: ' . now()->format('M d, Y \a\t g:i A'));
        $sheet->mergeCells('A3:E3');
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Add summary section
        $row = 5;
        $sheet->setCellValue('A' . $row, 'FINANCIAL SUMMARY');
        $sheet->mergeCells('A' . $row . ':E' . $row);
        $sheet->getStyle('A' . $row)->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A' . $row)->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('E5E7EB');

        $row++;
        $sheet->setCellValue('A' . $row, 'Total Income:');
        $sheet->setCellValue('B' . $row, '$' . number_format($totalIncome, 2));
        $sheet->getStyle('B' . $row)->getFont()->getColor()->setRGB('059669'); // Green
        $sheet->getStyle('A' . $row)->getFont()->setBold(true);

        $sheet->setCellValue('D' . $row, 'Total Expenses:');
        $sheet->setCellValue('E' . $row, '$' . number_format($totalExpense, 2));
        $sheet->getStyle('E' . $row)->getFont()->getColor()->setRGB('DC2626'); // Red
        $sheet->getStyle('D' . $row)->getFont()->setBold(true);

        $row++;
        $sheet->setCellValue('A' . $row, 'Net Balance:');
        $sheet->setCellValue('B' . $row, '$' . number_format($balance, 2));
        $sheet->getStyle('B' . $row)->getFont()->getColor()->setRGB($balance >= 0 ? '059669' : 'DC2626');
        $sheet->getStyle('A' . $row)->getFont()->setBold(true);

        $sheet->setCellValue('D' . $row, 'Total Transactions:');
        $sheet->setCellValue('E' . $row, $transactions->count());
        $sheet->getStyle('D' . $row)->getFont()->setBold(true);

        // Add transaction data headers
        $row += 3;
        $headers = ['Date', 'Type', 'Category', 'Amount ($)', 'Notes'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . $row, $header);
            $col++;
        }

        // Style headers
        $headerRange = 'A' . $row . ':E' . $row;
        $sheet->getStyle($headerRange)->getFont()->setBold(true);
        $sheet->getStyle($headerRange)->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('F3F4F6');
        $sheet->getStyle($headerRange)->getBorders()->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        // Add transaction data
        $row++;
        foreach ($transactions as $transaction) {
            $sheet->setCellValue('A' . $row, $transaction->date->format('Y-m-d'));
            $sheet->setCellValue('B' . $row, ucfirst($transaction->type));
            $sheet->setCellValue('C' . $row, $transaction->category->name);
            $sheet->setCellValue('D' . $row, $transaction->amount);
            $sheet->setCellValue('E' . $row, $transaction->note ?? '');

            // Style amount column based on type
            if ($transaction->type === 'income') {
                $sheet->getStyle('D' . $row)->getFont()->getColor()->setRGB('059669'); // Green
            } else {
                $sheet->getStyle('D' . $row)->getFont()->getColor()->setRGB('DC2626'); // Red
            }

            // Add borders to data rows
            $sheet->getStyle('A' . $row . ':E' . $row)->getBorders()->getAllBorders()
                ->setBorderStyle(Border::BORDER_THIN);

            $row++;
        }

        // Auto-size columns
        foreach (['A', 'B', 'C', 'D', 'E'] as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Set minimum column widths for better appearance
        $sheet->getColumnDimension('A')->setWidth(max(12, $sheet->getColumnDimension('A')->getWidth())); // Date
        $sheet->getColumnDimension('B')->setWidth(max(10, $sheet->getColumnDimension('B')->getWidth())); // Type
        $sheet->getColumnDimension('C')->setWidth(max(20, $sheet->getColumnDimension('C')->getWidth())); // Category
        $sheet->getColumnDimension('D')->setWidth(max(15, $sheet->getColumnDimension('D')->getWidth())); // Amount
        $sheet->getColumnDimension('E')->setWidth(max(30, $sheet->getColumnDimension('E')->getWidth())); // Note

        // Format amount column as currency
        $dataStartRow = 10; // Adjust based on your layout
        $dataEndRow = $row - 1;
        $sheet->getStyle('D' . $dataStartRow . ':D' . $dataEndRow)->getNumberFormat()
            ->setFormatCode('$#,##0.00');

        // Create writer and download
        $filename = 'transactions_' . date('Y-m-d') . '.xlsx';

        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function pdf(Request $request)
    {
        $userId = Auth::id();
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $query = Transaction::with('category')
            ->where('user_id', $userId)
            ->orderBy('date', 'desc');

        if ($startDate) {
            $query->whereDate('date', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('date', '<=', $endDate);
        }

        $transactions = $query->get();

        // Calculate totals
        $totalIncome = $transactions->where('type', 'income')->sum('amount');
        $totalExpense = $transactions->where('type', 'expense')->sum('amount');
        $balance = $totalIncome - $totalExpense;

        // Group by category
        $categoryTotals = $transactions->groupBy('category.name')->map(function ($items) {
            return [
                'count' => $items->count(),
                'total' => $items->sum('amount'),
                'type' => $items->first()->type
            ];
        });

        $pdf = Pdf::loadView('exports.pdf', compact(
            'transactions',
            'totalIncome',
            'totalExpense',
            'balance',
            'categoryTotals',
            'startDate',
            'endDate'
        ));

        // Set PDF options for better formatting
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOptions([
            'dpi' => 150,
            'defaultFont' => 'sans-serif'
        ]);

        $filename = 'transaction_report_' . date('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }
}
