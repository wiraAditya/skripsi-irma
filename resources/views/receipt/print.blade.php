<x-layouts.app.clean :title="'Receipt #' . $transaction->transaction_code">
    <div class="min-h-screen bg-gray-50 flex flex-col items-center p-4">
        <div class="w-full max-w-sm bg-white rounded-lg shadow-sm overflow-hidden print:max-w-full print:shadow-none">
            <!-- Print controls (hide when printing) -->
            <div class="mb-6 text-center print:hidden">
                <button onclick="window.print()" class="inline-flex items-center justify-center px-5 py-2 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 mr-3">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    Print Receipt
                </button>
                <button onclick="window.close()" class="inline-flex items-center justify-center px-5 py-2 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Close
                </button>
            </div>

            <!-- Receipt content -->
            <div class="receipt-content print:w-80 mx-auto p-6">
                <!-- Receipt header -->
                <div class="text-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-800 mb-1">Amedian Dapur Restoran</h1>
                    <p class="text-gray-600 text-sm">jl I Ketut Natih Amed Karangasem</p>
                </div>
                
                <!-- Receipt details -->
                <div class="border-t border-b border-gray-200 py-4 mb-5">
                    <div class="flex justify-between text-sm mb-2">
                        <span class="font-medium text-gray-600">Receipt #:</span>
                        <span class="font-semibold">{{ $transaction->transaction_code }}</span>
                    </div>
                    <div class="flex justify-between text-sm mb-2">
                        <span class="font-medium text-gray-600">Nama #:</span>
                        <span class="font-semibold">{{ $transaction->nama }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="font-medium text-gray-600">Date:</span>
                        <span>{{ $transaction->created_at->format('M d, Y h:i A') }}</span>
                    </div>
                </div>
                
                <!-- Items -->
                <div class="mb-6">
                    <div class="text-sm font-semibold text-gray-700 mb-3 pb-1 border-b border-gray-200">ORDER DETAILS</div>
                    @foreach($transaction->orderDetails as $item)
                    <div class="flex justify-between text-sm mb-3">
                        <div>
                            <span class="font-medium">{{ $item->menu->nama }}</span>
                            <span class="text-gray-500 text-xs block">Qty: {{ $item->qty }}</span>
                        </div>
                        <span class="font-medium">Rp {{ number_format($item->harga, 0, ',', '.') }}</span>
                    </div>
                    @endforeach
                </div>
                
                <!-- Totals -->
                <div class="border-t border-gray-200 pt-4 mb-6">
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-gray-600">Subtotal:</span>
                        <span>Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-gray-600">Tax ({{ round(($transaction->tax/$transaction->subtotal)*100) }}%):</span>
                        <span>Rp {{ number_format($transaction->tax, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between font-bold text-base mt-3 pt-2 border-t border-gray-200">
                        <span>Total:</span>
                        <span>Rp {{ number_format($transaction->subtotal + $transaction->tax, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex flex-col text-base mt-3 pt-2 border-t border-gray-200">
                        <span class="font-bold">Catatan:</span>
                        <span>{{$transaction->catatan }}</span>
                    </div>
                </div>
                
                <!-- Payment method -->
                <div class="mb-6 text-sm">
                    <div class="font-semibold text-gray-700 mb-1">Payment Method:</div>
                    <div class="text-gray-600">Cash</div>
                </div>
                
                <!-- Footer -->
                <div class="text-center text-sm text-gray-500 pt-4 border-t border-gray-200">
                    <p class="mb-2">Thank you for your purchase!</p>
                    <p>Please keep this receipt for your records</p>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Auto print when page loads
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        }
    </script>

    <style>
        @media print {
            @page {
                margin: 5mm;
                size: auto;
            }
            body {
                padding: 0;
                background: white;
            }
            .receipt-content {
                width: 80mm;
                margin: 0 auto;
                padding: 0 !important;
            }
        }
    </style>
</x-layouts.app.clean>