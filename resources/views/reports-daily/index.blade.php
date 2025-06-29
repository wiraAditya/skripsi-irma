<x-layouts.app :title="'Laporan Harian'">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Laporan Harian</h1>
    </div>

    <!-- Filter Form -->
    <div class="bg-white shadow-sm rounded-lg p-6 mb-6">
        <form method="GET" action="{{ route('reports.index.daily') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="date" class="block text-sm font-medium text-gray-700">Tanggal</label>
                <input type="date" name="date" id="date" value="{{ $date }}"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Filter
                </button>
                <a href="{{ route('reports.index.daily') }}"
                    class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    Reset
                </a>
                <a href="#" target="_blank" id="print-daily-report"
                    onclick="handlePrintDailyReport(event)"
                    class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    Cetak Laporan
                </a>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white shadow-sm rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900">Total Penjualan</h3>
            <p class="mt-2 text-3xl font-semibold text-blue-600">{{ $summary['total_penjualan'] }}</p>
            <p class="mt-1 text-sm text-gray-500">Periode: {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</p>
        </div>
        <div class="bg-white shadow-sm rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900">Total Tunai</h3>
            <p class="mt-2 text-3xl font-semibold text-green-600">Rp
                {{ number_format($summary['total_cash'], 0, ',', '.') }}</p>
            <p class="mt-1 text-sm text-gray-500">Periode: {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</p>
        </div>
        <div class="bg-white shadow-sm rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900">Total Digital</h3>
            <p class="mt-2 text-3xl font-semibold text-green-600">Rp
                {{ number_format($summary['total_digital'], 0, ',', '.') }}</p>
            <p class="mt-1 text-sm text-gray-500">Periode: {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</p>
        </div>
        <div class="bg-white shadow-sm rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900">Total Pendapatan</h3>
            <p class="mt-2 text-3xl font-semibold text-green-600">Rp
                {{ number_format($summary['total_pendapatan'], 0, ',', '.') }}</p>
            <p class="mt-1 text-sm text-gray-500">Periode: {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</p>
        </div>
    </div>

    <!-- Report Table -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                No
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Kode Order
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Jenis Bayar
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Total
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($reports as $index => $report)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $loop->iteration }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $report['transaction_code'] }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="text-sm text-gray-900">
                                        {{ $paymentMethodLabels[$report->payment_method] }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="text-sm text-gray-900">Rp
                                        {{ number_format(($report['subtotal'] + $report['tax']), 0, ',', '.') }}</div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4"
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    Tidak ada data transaksi untuk periode yang dipilih
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <th colspan="3" class="px-6 py-4 text-left text-sm font-medium text-gray-900">
                                TOTAL
                            </th>
                            <th class="px-6 py-4 text-right text-sm font-medium text-gray-900">
                                Rp {{ number_format($summary['total_pendapatan'], 0, ',', '.') }}
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <script>
        function handlePrintDailyReport(event) {
            try {
                event.preventDefault();
                const date = document.getElementById('date').value;

                // Ganti route sesuai kebutuhan
                const url = `{{ route('reports.print.daily') }}?date=${date}`;
                window.open(url, '_blank');
            } catch (error) {
                console.error('Error:', error);
            }
        }
    </script>

</x-layouts.app>
