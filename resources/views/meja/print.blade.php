<x-layouts.app.clean :title="'QR Code Meja ' . $meja->nomor">
    <div class="min-h-screen bg-gray-50 flex flex-col items-center p-4">
        <div class="w-full max-w-sm bg-white rounded-lg shadow-sm overflow-hidden print:max-w-full print:shadow-none">
            <!-- Print controls (hide when printing) -->
            <div class="mb-6 text-center print:hidden">
                <button onclick="window.print()" class="inline-flex items-center justify-center px-5 py-2 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 mr-3">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    Print QR Code
                </button>
                <button onclick="window.close()" class="inline-flex items-center justify-center px-5 py-2 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Close
                </button>
            </div>

            <!-- QR Code content -->
            <div class="qr-content print:w-80 mx-auto p-6">
                <!-- Header -->
                <div class="text-center mb-6">
                    <p class="text-gray-600 text-sm">Scan QR Code untuk memesan</p>
                </div>
                
                <!-- QR Code details -->
                <div class="border-t border-b border-gray-200 py-4 mb-5">
                    <div class="flex justify-between text-sm mb-2">
                        <span class="font-medium text-gray-600">Meja #:</span>
                        <span class="font-semibold">{{ $meja->nama }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="font-medium text-gray-600">Generated:</span>
                        <span>{{ now()->format('M d, Y h:i A') }}</span>
                    </div>
                </div>
                
                <!-- QR Code Image -->
                <div class="flex justify-center mb-6 p-4 bg-white rounded">
                    <div class="p-2 border border-gray-200 rounded">
                        {!! $qrCode !!}
                    </div>
                </div>
                
                <!-- Instructions -->
                <div class="mb-6 text-sm text-center">
                    <div class="font-semibold text-gray-700 mb-2">Cara Penggunaan:</div>
                    <ol class="list-decimal list-inside text-gray-600 space-y-1 text-left">
                        <li>Scan QR code dengan kamera ponsel</li>
                        <li>Pilih menu yang diinginkan</li>
                        <li>Lakukan pembayaran</li>
                        <li>Tunggu pesanan Anda disajikan</li>
                    </ol>
                </div>
                
                <!-- Footer -->
                <div class="text-center text-sm text-gray-500 pt-4 border-t border-gray-200">
                    <p class="mb-2">Terima kasih telah menggunakan layanan kami</p>
                    <p>Tunjukkan QR ini ke staff untuk bantuan</p>
                </div>
            </div>
        </div>
    </div>
    
    @if($autoPrint)
    <script>
        // Auto print when page loads
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
            
            // Close window after print if not canceled
            window.onafterprint = function() {
                window.close();
            };
        }
    </script>
    @endif

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
            .qr-content {
                width: 80mm;
                margin: 0 auto;
                padding: 0 !important;
            }
            .qr-code svg {
                width: 100% !important;
                height: auto !important;
            }
        }
    </style>
</x-layouts.app.clean>