<x-layouts.app :title="'Laporan Transaksi'">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Laporan Rekap</h1>
    </div>


    <!-- Filter Form -->
    <div class="bg-white shadow-sm rounded-lg p-6 mb-6">
        <form id="filter" method="GET" action="{{ route('reports.index') }}" class="space-y-4">
            <!-- Report Type -->
            <div>
                <label for="report_type" class="block text-sm font-medium text-gray-700">Tipe Laporan</label>
                <select id="report_type" name="report_type"
                    class="mt-1 block w-full pl-3 pr-10 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="recap" {{ $reportType == 'recap' ? 'selected' : '' }}>Rekap</option>
                    <option value="transaction" {{ $reportType == 'transaction' ? 'selected' : '' }}>Transaksi</option>
                </select>
            </div>

            <!-- Date Range -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                    <input type="date" name="start_date" id="start_date" value="{{ $startDate }}"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700">Tanggal Akhir</label>
                    <input type="date" name="end_date" id="end_date" value="{{ $endDate }}"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
            
            <!-- Buttons -->
            <div class="flex flex-wrap items-center gap-2">
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Filter
                </button>
                <button type="button" onclick="setToday()"
                    class="px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                    Hari Ini
                </button>
                <a href="{{ route('reports.index') }}"
                    class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    Reset
                </a>
                <a href="#" target="_blank" id="print-report"
                    onclick="handlePrintReport(event)"
                    class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    Cetak Laporan
                </a>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white shadow-sm rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900">Total Transaksi</h3>
            <p class="mt-2 text-3xl font-semibold text-blue-600">{{ $summary['total_transaksi'] }}</p>
            <p class="mt-1 text-sm text-gray-500">Periode: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} -
                {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</p>
        </div>
        <div class="bg-white shadow-sm rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900">Total Tunai</h3>
            <p class="mt-2 text-3xl font-semibold text-green-600">Rp
                {{ number_format($summary['total_cash'], 0, ',', '.') }}</p>
            <p class="mt-1 text-sm text-gray-500">Periode: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} -
                {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</p>
        </div>
        <div class="bg-white shadow-sm rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900">Total Digital</h3>
            <p class="mt-2 text-3xl font-semibold text-green-600">Rp
                {{ number_format($summary['total_digital'], 0, ',', '.') }}</p>
            <p class="mt-1 text-sm text-gray-500">Periode: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} -
                {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</p>
        </div>
        <div class="bg-white shadow-sm rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900">Total Pendapatan Bersih</h3>
            <p class="mt-2 text-3xl font-semibold text-green-600">Rp
                {{ number_format($summary['total_pendapatan_bersih'], 0, ',', '.') }}</p>
            <div class="mt-1 text-sm text-gray-500">
                <p>Kotor: Rp {{ number_format($summary['total_pendapatan_kotor'], 0, ',', '.') }}</p>
                <p class="text-red-500">Refund: Rp {{ number_format($summary['total_refund'], 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <!-- Report Table -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Transaksi</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total Cash</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total Digital</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total Refund</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($reports as $index => $report)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="text-sm text-gray-900">{{ $loop->iteration }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ \Carbon\Carbon::parse($report->tanggal_transaksi)->format('d/m/Y') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="text-sm text-gray-900">{{ $report->jumlah_transaksi }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="text-sm text-gray-900">Rp
                                        {{ number_format($report->total_cash, 0, ',', '.') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="text-sm text-gray-900">Rp
                                        {{ number_format($report->total_digital, 0, ',', '.') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="text-sm text-red-500">- Rp
                                        {{ number_format($report->total_refund, 0, ',', '.') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="text-sm text-gray-900">Rp
                                        {{ number_format($report->total_pendapatan_bersih, 0, ',', '.') }}</div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    Tidak ada data transaksi untuk periode yang dipilih
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <th colspan="2" class="px-6 py-4 text-left text-sm font-medium text-gray-900">
                                TOTAL
                            </th>
                            <th class="px-6 py-4 text-center text-sm font-medium text-gray-900">
                                {{ $summary['total_transaksi'] }}
                            </th>
                            <th class="px-6 py-4 text-right text-sm font-medium text-gray-900">
                                Rp {{ number_format($summary['total_cash'], 0, ',', '.') }}
                            </th>
                            <th class="px-6 py-4 text-right text-sm font-medium text-gray-900">
                                Rp {{ number_format($summary['total_digital'], 0, ',', '.') }}
                            </th>
                            <th class="px-6 py-4 text-right text-sm font-medium text-red-500">
                                - Rp {{ number_format($summary['total_refund'], 0, ',', '.') }}
                            </th>
                            <th class="px-6 py-4 text-right text-sm font-medium text-gray-900">
                                Rp {{ number_format($summary['total_pendapatan_bersih'], 0, ',', '.') }}
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <script>
    function handlePrintReport(event) {
        try {
            event.preventDefault();
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;

            const url = `{{ route('reports.print') }}?start_date=${startDate}&end_date=${endDate}`;
            window.open(url, '_blank');
        } catch (error) {
            console.error('Error:', error);
        }
    }
    function setToday() {
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('start_date').value = today;
        document.getElementById('end_date').value = today;
        // Submit the form
        document.getElementById('filter').submit();
    }

    function handlePrintReport(event) {
        try {
            event.preventDefault();
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;
            const reportType = document.getElementById('report_type').value;

            const url = `{{ route('reports.print') }}?start_date=${startDate}&end_date=${endDate}&report_type=${reportType}`;
            window.open(url, '_blank');
        } catch (error) {
            console.error('Error:', error);
        }
    }
    </script>
    
</x-layouts.app>