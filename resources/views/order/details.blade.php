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

    @if(session('error'))
        <x-alert type="error" :message="session('error')" />
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
                            <span class="font-medium">Rp {{ number_format($order->subtotal ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex">
                            <span class="text-gray-500 w-40">Pajak</span>
                            <span class="font-medium">Rp {{ number_format($order->tax ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex">
                            <span class="text-gray-500 w-40">Total Refund</span>
                            <span class="font-medium text-red-600">- Rp {{ number_format($order->refunds->sum('refund_amount') ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex border-t border-gray-200 pt-2">
                            <span class="text-gray-500 w-40 font-bold">Total Bersih</span>
                            <span class="font-bold">Rp {{ number_format(($order->subtotal + $order->tax) - $order->refunds->sum('refund_amount'), 0, ',', '.') }}</span>
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
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
        <div class="p-6 bg-white border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Daftar Menu</h2>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Menu</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Refund</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($order->orderDetails as $index => $item)
                            @php
                                $itemRefund = $item->refundItems->sum('refund_amount');
                                $itemQtyRefund = $item->refundItems->sum('quantity');
                            @endphp
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $loop->iteration }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $item->menu->nama }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">Rp {{ number_format($item->harga ?? 0, 0, ',', '.') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $item->qty }}
                                        @if($itemQtyRefund > 0)
                                            <span class="text-xs text-red-500">(Refund: {{ $itemQtyRefund }})</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-red-500">
                                        @if($itemRefund > 0)
                                            - Rp {{ number_format($itemRefund, 0, ',', '.') }}
                                        @else
                                            -
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        Rp {{ number_format(($item->harga * $item->qty) - $itemRefund, 0, ',', '.') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $item->catatan ?? '-' }}</div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    Tidak ada item menu
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Refund History -->
    @if($order->refunds->count() > 0)
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
        <div class="p-6 bg-white border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Riwayat Refund</h2>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Metode</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alasan</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dibuat Oleh</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($order->refunds as $refund)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $refund->created_at->format('d/m/Y H:i') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-red-600">- Rp {{ number_format($refund->refund_amount, 0, ',', '.') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        @if($refund->refund_method === \App\Models\Refund::METHOD_CASH)
                                            Tunai
                                        @else
                                            Transfer
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if($refund->status === \App\Models\Refund::STATUS_APPROVED) bg-green-100 text-green-800
                                        @elseif($refund->status === \App\Models\Refund::STATUS_PENDING) bg-yellow-100 text-yellow-800
                                        @elseif($refund->status === \App\Models\Refund::STATUS_REJECTED) bg-red-100 text-red-800
                                        @else bg-blue-100 text-blue-800 @endif">
                                        @if($refund->status === \App\Models\Refund::STATUS_APPROVED) Disetujui
                                        @elseif($refund->status === \App\Models\Refund::STATUS_PENDING) Pending
                                        @elseif($refund->status === \App\Models\Refund::STATUS_REJECTED) Ditolak
                                        @else Selesai @endif
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $refund->reason ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $refund->processedBy->name ?? '-' }}</div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- Action Buttons -->
    <div class="mt-6 flex justify-end space-x-4">
        <a href="{{ route('receipt.print', ['transactionCode' => $order->transaction_code, 'mejaId' => $mejaId ?? null]) }}" target="_blank" class="inline-flex items-center justify-center px-5 py-2 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
            </svg>
            Struk
        </a>

        @if($order->status !== \App\Models\Order::STATUS_DONE)
        <a 
            href="{{ route('orders.edit', $order) }}" 
            class="px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500"
        >
            Edit Pesanan
        </a>
        @endif

        @if($order->status === \App\Models\Order::STATUS_WAITING_CASH && auth()->user()->role !== 'role_admin')
            <button 
                type="button" 
                onclick="openPaymentModal()"
                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
                Konfirmasi Pembayaran
            </button>
        @endif

        @if($order->status === \App\Models\Order::STATUS_PAID && auth()->user()->role === 'role_dapur')
            <a 
                type="button" 
                href="{{ route('order.process', $order) }}"
                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
                Proses Pesanan
            </a>
        @endif
        
        @if($order->status === \App\Models\Order::STATUS_PROCESS && auth()->user()->role === 'role_dapur')
            <a 
                type="button" 
                href="{{ route('order.done', $order) }}"
                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
                Selesaikan Pesanan
            </a>
        @endif
    </div>

    <!-- Payment Confirmation Modal -->
    <div id="paymentModal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form method="POST" action="{{ route('order.confirm', [$order]) }}">
                    @csrf
                    @method('GET')
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">
                            Konfirmasi Pembayaran
                        </h3>
                        <div class="space-y-4">
                           <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="totalAmount">
                                    Total yang harus dibayar
                                </label>
                                <input 
                                    type="text" 
                                    id="totalAmount" 
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                                    value="Rp {{ number_format(($order->subtotal ?? 0) + ($order->tax ?? 0), 0, ',', '.') }}" 
                                    readonly
                                >
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="amountPaid">
                                    Jumlah yang dibayarkan
                                </label>
                                <input 
                                    type="number" 
                                    id="amountPaid" 
                                    name="amount_paid" 
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                                    required
                                    oninput="calculateChange()"
                                >
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="changeAmount">
                                    Kembalian
                                </label>
                                <input 
                                    type="text" 
                                    id="changeAmount" 
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                                    readonly
                                >
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button 
                            type="submit" 
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            Konfirmasi
                        </button>
                        <button 
                            type="button" 
                            onclick="closePaymentModal()"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openPaymentModal() {
            document.getElementById('paymentModal').classList.remove('hidden');
            document.getElementById('amountPaid').focus();
        }
        
        function closePaymentModal() {
            document.getElementById('paymentModal').classList.add('hidden');
        }
        
        function calculateChange() {
            const totalAmount = <?= $order->subtotal + $order->tax ?>;
            const amountPaid = parseFloat(document.getElementById('amountPaid').value) || 0;
            const change = amountPaid - totalAmount;
            
            document.getElementById('changeAmount').value = formatCurrency(change);
        }
        
        function formatCurrency(amount) {
            return 'Rp ' + Math.abs(amount).toLocaleString('id-ID');
        }
        
        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('paymentModal');
            if (event.target === modal) {
                closePaymentModal();
            }
        }
    </script>
</x-layouts.app>