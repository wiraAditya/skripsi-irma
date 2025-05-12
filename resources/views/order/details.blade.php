<x-layouts.app :title="'Detail Pesanan #' . $order->transaction_code">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Detail Pesanan</h1>
            <p class="text-sm text-gray-500 mt-1">Kode: {{ $order->transaction_code }}</p>
        </div>
        <a 
            href="{{ route('order.index') }}" 
            class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"
        >
            Kembali
        </a>
    </div>

    @if(session('success'))
        <x-alert type="success" :message="session('success')" />
    @endif

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
        <div class="p-6 bg-white border-b border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Order Information -->
                <div>
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Informasi Pesanan</h2>
                    <div class="space-y-3">
                        <div class="flex">
                            <span class="text-gray-500 w-40">Status</span>
                            <span class="font-medium">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusConfig[$order->status]['class'] }}">
                                    {{ $statusConfig[$order->status]['label'] }}
                                </span>
                            </span>
                        </div>
                        <div class="flex">
                            <span class="text-gray-500 w-40">Tanggal</span>
                            <span class="font-medium">{{ $order->tanggal->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="flex">
                            <span class="text-gray-500 w-40">Meja</span>
                            <span class="font-medium">{{ $order->meja->nama ?? '-' }}</span>
                        </div>
                        <div class="flex">
                            <span class="text-gray-500 w-40">Metode Pembayaran</span>
                            <span class="font-medium">{{ $paymentMethodLabels[$order->payment_method] }}</span>
                        </div>
                    </div>
                </div>

                <!-- Payment Information -->
                <div>
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Informasi Pembayaran</h2>
                    <div class="space-y-3">
                        <div class="flex">
                            <span class="text-gray-500 w-40">Subtotal</span>
                            <span class="font-medium">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex">
                            <span class="text-gray-500 w-40">Pajak</span>
                            <span class="font-medium">Rp {{ number_format($order->tax, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex border-t border-gray-200 pt-2">
                            <span class="text-gray-500 w-40 font-bold">Total</span>
                            <span class="font-bold">Rp {{ number_format($order->subtotal + $order->tax, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Notes -->
            @if($order->catatan)
                <div class="mt-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-2">Catatan</h2>
                    <div class="bg-gray-50 p-4 rounded-md">
                        <p class="text-gray-700">{{ $order->catatan }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Order Items -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Daftar Menu</h2>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                No
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Menu
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Harga
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Qty
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Subtotal
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Catatan
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($order->orderDetails as $index => $item)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $loop->iteration }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $item->menu->nama }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">Rp {{ number_format($item->harga, 0, ',', '.') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $item->qty }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">Rp {{ number_format($item->harga * $item->qty, 0, ',', '.') }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $item->catatan ?? '-' }}</div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    Tidak ada item menu
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
        <div class="mt-6 flex justify-end space-x-4">
          <a 
              href="{{ route('orders.edit', $order) }}" 
              class="px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500"
          >
              Edit Pesanan
          </a>
          @if($order->status === \App\Models\Order::STATUS_WAITING_CASH)
                <form method="PATCH" action="{{ route('order.confirm', [$order]) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Konfirmasi Pembayaran
                    </button>
                </form>
            @endif
            
        </div>
</x-layouts.app>