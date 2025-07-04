<x-layouts.app :title="'Buat Pengembalian Dana'">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Buat Pengembalian Dana</h1>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            @if($step === 1)
                <!-- Langkah 1: Pilih Pesanan -->
                <form action="{{ route('refunds.create') }}" method="GET">
                    <div class="mb-4">
                        <flux:input
                            name="order_id"
                            id="order_id"
                            :value="old('order_id')"
                            label="ID Pesanan atau Kode Transaksi"
                            type="text"
                            required
                            placeholder="Masukkan ID pesanan atau kode transaksi"
                        />
                        @error('order_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Tampilkan error umum jika ada -->
                    @if ($errors->any() && !$errors->has('order_id'))
                        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-md">
                            <div class="flex">
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">
                                        Terdapat masalah dengan input Anda.
                                    </h3>
                                    <div class="mt-2 text-sm text-red-700">
                                        <ul class="list-disc pl-5 space-y-1">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <div class="flex justify-end gap-1">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Lanjut
                        </button>
                        <a href="{{ route('refunds.index') }}" class="px-4 py-2 border-1 border-gray-600 text-gray-600 rounded-md hover:bg-gray-700 hover:text-white">
                            Batal
                        </a>
                    </div>
                </form>
            @elseif($step === 2)
                <!-- Langkah 2: Pilih Item -->
                <form action="{{ route('refunds.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="order_id" value="{{ $order->id }}">

                    <!-- Tampilkan error validasi umum -->
                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-md">
                            <div class="flex">
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">
                                        Harap perbaiki error berikut:
                                    </h3>
                                    <div class="mt-2 text-sm text-red-700">
                                        <ul class="list-disc pl-5 space-y-1">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Item untuk Direfund</label>
                        @error('items')
                            <p class="text-red-500 text-sm mb-2">{{ $message }}</p>
                        @enderror

                        <div class="space-y-4">
                            @foreach($order->orderDetails as $index => $item)
                            <div class="border rounded-md {{ $errors->has('items.'.$index.'.qty') || $errors->has('items.'.$index.'.id') || $errors->has('items.'.$index.'.reason') ? 'border-red-300 bg-red-50' : 'border-gray-200' }}">
                                <div class="flex items-start p-3">
                                    <div class="flex items-center h-5">
                                        <input id="item-{{ $item->id }}" name="items[{{ $index }}][id]" type="checkbox" 
                                            value="{{ $item->id }}" 
                                            class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded"
                                            @if(old('items.'.$index.'.id') == $item->id) checked @endif
                                            onchange="toggleItemDetails({{ $index }})">
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <label for="item-{{ $item->id }}" class="block text-sm text-gray-700">
                                            <span class="font-medium">{{ $item->menu->name ?? 'Menu Item' }}</span>
                                            <span class="block text-xs text-gray-500">
                                                Rp {{ number_format($item->harga, 0, ',', '.') }} × {{ $item->qty }} (Tersedia: {{ $item->qty }})
                                            </span>
                                        </label>
                                        <div class="mt-1 flex items-center">
                                            <label for="qty-{{ $item->id }}" class="mr-2 text-sm text-gray-600">Jumlah:</label>
                                            <input type="number" id="qty-{{ $item->id }}" name="items[{{ $index }}][qty]" 
                                                min="1" max="{{ $item->qty }}" 
                                                value="{{ old('items.'.$index.'.qty', 1) }}"
                                                class="w-20 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm {{ $errors->has('items.'.$index.'.qty') ? 'border-red-300' : '' }}">
                                            
                                            @error('items.'.$index.'.qty')
                                                <p class="ml-2 text-red-500 text-sm">{{ $message }}</p>
                                            @enderror
                                            @error('items.'.$index.'.id')
                                                <p class="ml-2 text-red-500 text-sm">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Alasan per item - ditampilkan ketika checkbox dicentang -->
                                <div id="item-details-{{ $index }}" class="px-3 pb-3 border-t border-gray-100 bg-gray-50" style="display: {{ old('items.'.$index.'.id') == $item->id ? 'block' : 'none' }};">
                                    <div class="mt-3">
                                        <label for="reason-{{ $item->id }}" class="block text-sm font-medium text-gray-700 mb-1">
                                            Alasan refund item ini
                                        </label>
                                        <textarea id="reason-{{ $item->id }}" name="items[{{ $index }}][reason]" 
                                            rows="2" 
                                            placeholder="Masukkan alasan spesifik untuk refund item ini..."
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm {{ $errors->has('items.'.$index.'.reason') ? 'border-red-300' : '' }}">{{ old('items.'.$index.'.reason') }}</textarea>
                                        
                                        @error('items.'.$index.'.reason')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Metode Pengembalian</label>
                        @error('method')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <div class="mt-2 space-y-2">
                            <div class="flex items-center">
                                <input id="method-cash" name="method" type="radio" value="cash" 
                                    {{ old('method', 'cash') == 'cash' ? 'checked' : '' }}
                                    class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                                <label for="method-cash" class="ml-2 block text-sm text-gray-700">
                                    Tunai
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input id="method-transfer" name="method" type="radio" value="transfer"
                                    {{ old('method') == 'transfer' ? 'checked' : '' }}
                                    class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                                <label for="method-transfer" class="ml-2 block text-sm text-gray-700">
                                    Transfer Bank
                                </label>
                            </div>
                        </div>
                    </div>

                    <script>
                        function toggleItemDetails(index) {
                            const checkbox = document.getElementById('item-' + document.querySelector(`input[name="items[${index}][id]"]`).value);
                            const detailsDiv = document.getElementById('item-details-' + index);
                            const reasonTextarea = document.querySelector(`textarea[name="items[${index}][reason]"]`);
                            
                            if (checkbox.checked) {
                                detailsDiv.style.display = 'block';
                                reasonTextarea.required = true;
                            } else {
                                detailsDiv.style.display = 'none';
                                reasonTextarea.required = false;
                                reasonTextarea.value = '';
                            }
                        }
                        
                        // Inisialisasi saat halaman dimuat
                        document.addEventListener('DOMContentLoaded', function() {
                            const checkboxes = document.querySelectorAll('input[type="checkbox"][name*="[id]"]');
                            checkboxes.forEach((checkbox, index) => {
                                if (checkbox.checked) {
                                    toggleItemDetails(index);
                                }
                            });
                        });
                    </script>

                    <div class="flex justify-end gap-1">
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                            Proses Pengembalian
                        </button>
                        <a href="{{ route('refunds.create') }}" class="px-4 py-2 border-1 border-gray-600 text-gray-600 rounded-md hover:bg-gray-700 hover:text-white">
                            Batal
                        </a>
                    </div>
                </form>
            @endif
        </div>
    </div>
</x-layouts.app>