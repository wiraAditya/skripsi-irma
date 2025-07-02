<x-layouts.app :title="'Daftar Pesanan'">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Daftar Pesanan</h1>

        <!-- Search Form -->
        <form method="GET" action="{{ route('order.index') }}" class="flex items-center">
            <div class="relative rounded-md shadow-sm">
                <input type="text" name="search" placeholder="Cari kode order..." value="{{ request('search') }}"
                    class="block w-full pl-4 pr-10 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
            </div>
            <button type="submit"
                class="ml-2 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Cari
            </button>
            @if (request('search'))
                <a href="{{ route('order.index') }}"
                    class="ml-2 px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Cashier Session Status Card -->
    @if($needValidateSession)
        <div class="mb-6 p-4 rounded-lg {{ $cashierSession ? 'bg-blue-50 border border-blue-200' : 'bg-yellow-50 border border-yellow-200' }}">
            @if ($cashierSession)
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="font-bold text-lg text-blue-800">Sesi Kasir Aktif</h3>
                        <p class="text-sm">Dibuka: {{ $cashierSession->start_time->format('d/m/Y H:i') }}</p>
                        <p class="text-sm">Saldo Awal: Rp {{ number_format($cashierSession->starting_cash, 0, ',', '.') }}</p>
                        <p class="text-sm">Sesi Kasir: {{ $cashierSession->user->name }} -- {{ $cashierSession->user->email }}</p>
                    </div>
                    <a href="{{ route('admin.sessions.close', $cashierSession->id) }}" 
                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm">
                        Tutup Sesi
                    </a>
                </div>
            @else
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="font-bold text-lg text-yellow-800">Kasir Belum Dibuka</h3>
                        <p class="text-sm">Silakan buka sesi kasir untuk memproses pembayaran tunai</p>
                    </div>
                    <a href="{{ route('admin.sessions.open') }}" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm">
                        Buka Sesi Kasir
                    </a>
                </div>
            @endif
        </div>
    @endif
    <!-- Revenue Summary Cards -->
     @if(auth()->user()->role != "role_dapur")
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white shadow-sm rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900">Total Pendapatan Hari Ini</h3>
            <p class="mt-2 text-3xl font-semibold text-green-600">Rp {{ number_format($summary['total_pendapatan'], 0, ',', '.') }}</p>
            <div class="text-sm text-gray-500 mt-1">
                <span class="{{ $summary['total_refund'] > 0 ? 'text-red-500' : 'text-gray-500' }}">
                    Refund: Rp {{ number_format($summary['total_refund'], 0, ',', '.') }}
                </span>
            </div>
        </div>
        <div class="bg-white shadow-sm rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900">Pendapatan Cash</h3>
            <p class="mt-2 text-xl font-semibold text-green-600">Rp {{ number_format($summary['pendapatan_cash'], 0, ',', '.') }}</p>
        </div>
        <div class="bg-white shadow-sm rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900">Pendapatan Digital</h3>
            <p class="mt-2 text-xl font-semibold text-green-600">Rp {{ number_format($summary['pendapatan_digital'], 0, ',', '.') }}</p>
        </div>
        <div class="bg-white shadow-sm rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900">Total Kotor</h3>
            <p class="mt-2 text-xl font-semibold text-blue-600">Rp {{ number_format($summary['gross_pendapatan'], 0, ',', '.') }}</p>
        </div>
    </div>
    @endif

    @if (session('success'))
        <x-alert type="success" :message="session('success')" />
    @endif

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div>
            <form method="GET" action="{{ route('order.index') }}">
                <div class="row flex justify-end items-center">
                    <div class="relative rounded-md shadow-sm">
                        <label for="meja" class="block text-sm font-medium text-gray-700">Kode Order</label>
                        <input type="text" name="kode" placeholder="Cari ..." value="{{ request('kode') }}"
                            class="block w-full pl-4 pr-10 py-2 mt-1 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <div class="absolute inset-y-0 right-0 flex items-center mt-5 pr-3 pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>

                    <div class="col-auto px-2 py-3">
                        <label for="meja" class="block text-sm font-medium text-gray-700">Pilih Meja</label>
                        <select name="meja" id="meja"
                            class="w-45 px-3 py-2 mt-1 text-gray-700 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150">
                            <option value="">Semua Meja</option>
                            @foreach ($mejas as $meja)
                                <option value="{{ $meja->id }}" {{ request('meja') == $meja->id ? 'selected' : '' }}>
                                    {{ $meja->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-auto px-2 py-3">
                        <label for="date" class="block text-sm font-medium text-gray-700">Tanggal Order</label>
                        <input type="date" name="date" id="date" value="{{ $date }}"
                            class="mt-1 block w-50 border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div class="col-auto px-2 py-3 mt-5">
                        <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Filter
                        </button>
                    </div>

                    <div class="col-auto px-2 py-3 mt-5">
                        <a href="{{ route('order.index') }}"
                            class="px-6 py-3 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <div class="p-6 bg-white border-b border-gray-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nomor Meja</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode Order</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Metode Pembayaran</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($orders as $index => $order)
                            @php
                                $orderTotal = $order->subtotal + $order->tax;
                                $refundAmount = $order->refunds_sum_refund_amount ?? 0;
                                $netTotal = $orderTotal - $refundAmount;
                            @endphp
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ ($orders->currentPage() - 1) * $orders->perPage() + $loop->iteration }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $order->meja->nama ?? '-' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $order->nama ?? '-' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $order->transaction_code ?? '-' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $order->tanggal->format('d/m/Y H:i') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 font-medium">
                                        Rp {{ number_format($netTotal, 0, ',', '.') }}
                                    </div>
                                    @if($refundAmount > 0)
                                        <div class="text-xs text-red-500">
                                            <span class="inline-flex items-center">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                                </svg>
                                                Refund: Rp {{ number_format($refundAmount, 0, ',', '.') }}
                                            </span>
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            Awal: Rp {{ number_format($orderTotal, 0, ',', '.') }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $paymentMethodLabels[$order->payment_method] }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusConfig[$order->status]['class'] }}">
                                        {{ $statusConfig[$order->status]['label'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('orders.detail', $order) }}"
                                        class="bg-blue-500 hover:bg-blue-600 text-white rounded-sm px-2.5 py-1">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    @if (request('search'))
                                        Tidak ditemukan pesanan dengan kode "{{ request('search') }}"
                                    @else
                                        Tidak ada pesanan tersedia
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $orders->appends(['search' => request('search')])->links() }}
            </div>
        </div>
    </div>
</x-layouts.app>