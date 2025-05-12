<x-layouts.app.clean :title="'Home'">
<div class="flex flex-col items-center justify-center min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 p-6">
    <div class="w-full max-w-md bg-white rounded-xl shadow-lg p-8 text-center">
        <!-- Header Icon -->
        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-indigo-100 mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
            </svg>
        </div>
        
        <!-- Title -->
        <h1 class="text-2xl font-bold text-gray-800 mb-2">Scan QR Code</h1>
        
        <!-- Instruction -->
        <p class="text-gray-600 mb-8">Silakan scan code QR pada meja menggunakan aplikasi scanner pada android atau kamera pada iphone untuk melanjutkan</p>
        
    </div>
</div>
</x-layouts.app.clean>
