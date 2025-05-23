<x-layouts.app.clean :title="'Laporan Transaksi'">
    <div class="min-h-screen bg-gray-50 flex flex-col items-center p-4">
        <div class="w-full max-w-4xl bg-white rounded-lg shadow-sm overflow-hidden print:max-w-full print:shadow-none">
            <!-- Print controls (hide when printing) -->
            <div class="mb-6 text-center print:hidden">
                <button onclick="window.print()"
                    class="inline-flex items-center justify-center px-5 py-2 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 mr-3">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                        </path>
                    </svg>
                    Cetak Laporan
                </button>
                <button onclick="window.close()"
                    class="inline-flex items-center justify-center px-5 py-2 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Tutup
                </button>
            </div>

            <!-- Report content -->
            <div class="report-content print:w-full mx-auto p-6">
                <!-- Header -->
                <div class="text-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">LAPORAN HARIAN</h1>
                    <p class="text-gray-600 mt-2">Periode: {{ $date }}</p>
                    <p class="text-gray-500 text-sm">Dicetak pada: {{ $printDate }}</p>
                </div>

                <!-- Report Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    No
                                </th>
                                <th scope="col"
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kode
                                </th>
                                <th scope="col"
                                    class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Jenis Bayar
                                </th>
                                <th scope="col"
                                    class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($reports as $index => $report)
                                <tr>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 text-center">
                                        {{ $loop->iteration }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 text-center">
                                        {{ $report->transaction_code }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 text-center">
                                        {{ $paymentMethodLabels[$report->payment_method] }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 text-right">
                                        Rp {{ number_format(($report->subtotal + $report->tax), 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4"
                                        class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                        Tidak ada data transaksi untuk periode yang dipilih
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <th colspan="3" class="px-4 py-3 text-left text-sm font-medium text-gray-900">
                                    TOTAL
                                </th>
                                <th class="px-4 py-3 text-right text-sm font-medium text-gray-900">
                                    Rp {{ number_format($summary['total_pendapatan'], 0, ',', '.') }}
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Footer -->
                <div class="mt-8 pt-4 border-t border-gray-200 text-sm text-gray-500">
                    <div class="flex justify-between">
                        <div>
                            <p class="font-medium">Dicetak oleh:</p>
                            <p class="mt-2">_________________________</p>
                        </div>
                        <div class="text-right">
                            <p class="font-medium">Mengetahui:</p>
                            <p class="mt-2">_________________________</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto print when page loads if in print view
        if (window.location.search.includes('print')) {
            window.onload = function() {
                setTimeout(function() {
                    window.print();
                }, 500);

                // Close window after print if not canceled
                window.onafterprint = function() {
                    window.close();
                };
            }
        }
    </script>

    <style>
        @media print {
            @page {
                margin: 10mm;
                size: auto;
            }

            body {
                padding: 0;
                background: white;
            }

            .report-content {
                width: 100%;
                margin: 0 auto;
                padding: 0 !important;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            th,
            td {
                padding: 8px 12px;
                border: 1px solid #e2e8f0;
            }

            th {
                background-color: #f8fafc;
            }

            tfoot {
                font-weight: bold;
                background-color: #f1f5f9;
            }
        }
    </style>
</x-layouts.app.clean>
