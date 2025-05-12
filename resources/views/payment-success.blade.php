<x-layouts.app.clean :title="'Payment Success'">
    <div class="min-h-screen bg-gray-50 flex flex-col items-center justify-center p-4">
        <div class="w-full max-w-md bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Success header with checkmark -->
            <div class="bg-teal-500 p-6 flex justify-center">
                <div class="rounded-full bg-white p-2">
                    <svg class="w-12 h-12 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>
            
            <!-- Success message -->
            <div class="p-6 text-center">
                <h2 class="text-2xl font-bold text-gray-800 mb-2"> {{ $method == 'method_cash' ? 'Order' : 'Pembayaran' }} berhasi!</h2>
                <p class="text-gray-600 mb-6">{{ $method == 'method_cash' ? 'Silakan menuju ke kasir untuk melakukan pembayaran' : 'Silakan menunggu pesanan anda' }}</p>
                
                <!-- Order details -->
                <div class="bg-gray-50 rounded-md p-4 mb-6">
                    <div class="flex justify-between text-sm text-gray-600 mb-2">
                        <span>Transaction ID:</span>
                        <span class="font-medium">{{ $transactionCode }}</span>
                    </div>
                    
                </div>
                
                <!-- Actions -->
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <a href="{{ route('home', ['mejaId' => $mejaId ?? null]) ?? '#' }}" class="inline-flex items-center justify-center px-5 py-2 border border-transparent text-base font-medium rounded-md text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Return Home
                    </a>
                    <a href="{{ route('receipt.print', ['transactionCode' => $transactionCode, 'mejaId' => $mejaId ?? null]) }}" target="_blank" class="inline-flex items-center justify-center px-5 py-2 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        Struk
                    </a>
                </div>
            </div>
            
          
        </div>
        
    </div>
</x-layouts.app.clean>