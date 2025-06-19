@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Export Data</h1>

        <div class="bg-white p-6 rounded-lg shadow mb-8">
            <h2 class="text-xl font-semibold mb-4">Select Date Range</h2>

            <form id="exportForm" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 mb-2">Start Date</label>
                        <input type="date" name="start_date"
                            value="{{ \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d') }}"
                            class="w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-gray-700 mb-2">End Date</label>
                        <input type="date" name="end_date"
                            value="{{ \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d') }}"
                            class="w-full border rounded px-3 py-2">
                    </div>
                </div>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- CSV Export -->
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex items-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-500 mr-3" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                            clip-rule="evenodd" />
                    </svg>
                    <h3 class="text-xl font-semibold">CSV Export</h3>
                </div>
                <p class="text-gray-600 mb-4">
                    Export your transaction data in CSV format, compatible with Excel, Google Sheets and other spreadsheet
                    applications.
                </p>
                <button id="csvExportButton"
                    class="bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                    Download CSV
                </button>
            </div>

            <!-- PDF Export -->
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex items-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-500 mr-3" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                            clip-rule="evenodd" />
                    </svg>
                    <h3 class="text-xl font-semibold">PDF Report</h3>
                </div>
                <p class="text-gray-600 mb-4">
                    Generate a professional PDF report with transaction history, category breakdowns and summary totals.
                </p>
                <button id="pdfExportButton"
                    class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                    Download PDF
                </button>
            </div>
        </div>
    </div>

    <script>
        // Store the routes in JavaScript variables (this prevents JS syntax errors)
        const csvExportUrl = "{{ route('export.csv') }}";
        const pdfExportUrl = "{{ route('export.pdf') }}";

        // Function to handle form submission
        function submitExportForm(url) {
            const form = document.getElementById('exportForm');
            const startDate = form.querySelector('input[name="start_date"]').value;
            const endDate = form.querySelector('input[name="end_date"]').value;

            // Use string concatenation instead of template literals
            window.location.href = url + "?start_date=" + startDate + "&end_date=" + endDate;
        }

        // Add event listeners after the DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('csvExportButton').addEventListener('click', function() {
                submitExportForm(csvExportUrl);
            });

            document.getElementById('pdfExportButton').addEventListener('click', function() {
                submitExportForm(pdfExportUrl);
            });
        });
    </script>
@endsection