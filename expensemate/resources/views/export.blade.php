@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-6xl">
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h1 class="text-2xl font-bold text-gray-800">Export Data</h1>
                    <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </a>
                </div>
            </div>

            <div class="p-6">
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-4">Filter by Date Range</h3>
                    <form id="export-form" class="flex flex-wrap gap-4 items-end">
                        <div class="flex-1 min-w-[200px]">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                            <input type="date" id="start_date" name="start_date"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div class="flex-1 min-w-[200px]">
                            <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                            <input type="date" id="end_date" name="end_date"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </form>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- CSV Export -->
                    <div
                        class="border-2 border-dashed border-gray-200 rounded-lg p-6 text-center hover:border-green-500 hover:bg-green-50 transition">
                        <div class="mb-4">
                            <svg class="w-12 h-12 text-green-500 mx-auto" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Export to CSV</h3>
                        <p class="text-gray-600 mb-4">Basic CSV format for simple data import and lightweight file size.</p>
                        <button onclick="exportData('csv')" id="csv-btn"
                            class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg transition">
                            Download CSV
                        </button>
                        <p class="text-xs text-gray-500 mt-2 pt-1">
                            <strong>Best for:</strong> Simple data imports, lightweight files
                        </p>
                    </div>

                    <!-- Excel Export (NEW) -->
                    <div
                        class="border-2 border-dashed border-gray-200 rounded-lg p-6 text-center hover:border-blue-500 hover:bg-blue-50 transition">
                        <div class="mb-4">
                            <svg class="w-12 h-12 text-blue-500 mx-auto" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h2a2 2 0 002-2z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Export to Excel</h3>
                        <p class="text-gray-600 mb-4">Professional Excel format with auto-sized columns, formatting, and
                            summary data.</p>
                        <button onclick="exportData('excel')" id="excel-btn"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition">
                            Download Excel
                        </button>
                        <p class="text-xs text-gray-500 mt-2 pt-1">
                            <strong>Best for:</strong> Professional reports, analysis, presentations
                        </p>
                    </div>

                    <!-- PDF Export -->
                    <div
                        class="border-2 border-dashed border-gray-200 rounded-lg p-6 text-center hover:border-red-500 hover:bg-red-50 transition">
                        <div class="mb-4">
                            <svg class="w-12 h-12 text-red-500 mx-auto" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Export to PDF</h3>
                        <p class="text-gray-600 mb-4">Professional PDF report with summaries, category breakdowns, and
                            detailed listings.</p>
                        <button onclick="exportData('pdf')" id="pdf-btn"
                            class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-lg transition">
                            Download PDF
                        </button>
                        <p class="text-xs text-gray-500 mt-2 pt-1">
                            <strong>Best for:</strong> Formal reports, sharing, archiving
                        </p>
                    </div>
                </div>

                <div class="mt-8 p-6 bg-blue-50 rounded-lg">
                    <h4 class="font-semibold text-blue-900 mb-4">Export Format Comparison</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                        <div class="bg-white p-4 rounded">
                            <h5 class="font-semibold text-green-700 mb-2">CSV Format</h5>
                            <ul class="text-gray-600 space-y-1">
                                <li>• Lightweight and fast</li>
                                <li>• Universal compatibility</li>
                                <li>• Simple data structure</li>
                                <li>• Easy to import elsewhere</li>
                            </ul>
                        </div>
                        <div class="bg-white p-4 rounded">
                            <h5 class="font-semibold text-blue-700 mb-2">Excel Format</h5>
                            <ul class="text-gray-600 space-y-1">
                                <li>• Professional formatting</li>
                                <li>• Auto-sized columns</li>
                                <li>• Summary calculations</li>
                                <li>• Color-coded data</li>
                            </ul>
                        </div>
                        <div class="bg-white p-4 rounded">
                            <h5 class="font-semibold text-red-700 mb-2">PDF Format</h5>
                            <ul class="text-gray-600 space-y-1">
                                <li>• Print-ready layout</li>
                                <li>• Fixed formatting</li>
                                <li>• Category breakdowns</li>
                                <li>• Professional appearance</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                    <h4 class="font-semibold text-gray-900 mb-2">Export Information</h4>
                    <ul class="text-sm text-gray-700 space-y-1">
                        <li>• All exports include only your personal transactions</li>
                        <li>• Leave date fields empty to export all transactions</li>
                        <li>• Excel format includes financial summaries and professional formatting</li>
                        <li>• Default period: Start of current month to today</li>
                        <li>• Large datasets may take a moment to process</li>
                    </ul>
                </div>

                <!-- Status Messages -->
                <div id="export-status" class="mt-4 hidden">
                    <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded">
                        <span id="export-message">Preparing your download...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function exportData(format) {
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;

            // Show status message
            const statusDiv = document.getElementById('export-status');
            const messageSpan = document.getElementById('export-message');
            const button = document.getElementById(format + '-btn');

            // Disable button and show loading
            button.disabled = true;
            button.textContent = 'Preparing...';
            statusDiv.classList.remove('hidden');
            messageSpan.textContent = `Preparing your ${format.toUpperCase()} download...`;

            // Use string concatenation for different formats
            let url = '';
            switch (format) {
                case 'csv':
                    url = '{{ route("export.csv") }}';
                    break;
                case 'excel':
                    url = '{{ route("export.excel") }}';
                    break;
                case 'pdf':
                    url = '{{ route("export.pdf") }}';
                    break;
            }

            const params = new URLSearchParams();

            if (startDate) params.append('start_date', startDate);
            if (endDate) params.append('end_date', endDate);

            if (params.toString()) {
                url += '?' + params.toString();
            }

            // Create a temporary link for download
            const link = document.createElement('a');
            link.href = url;
            link.download = '';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            // Reset button after delay
            setTimeout(() => {
                button.disabled = false;
                const buttonText = format === 'csv' ? 'Download CSV' :
                    format === 'excel' ? 'Download Excel' : 'Download PDF';
                button.textContent = buttonText;
                statusDiv.classList.add('hidden');
            }, 2000);
        }

        // Set default dates: Start of current month to TODAY
        document.addEventListener('DOMContentLoaded', function () {
            const now = new Date();
            const startOfMonth = new Date(now.getFullYear(), now.getMonth(), 1);

            document.getElementById('start_date').value = startOfMonth.toISOString().split('T')[0];
            document.getElementById('end_date').value = now.toISOString().split('T')[0];
        });
    </script>
@endsection