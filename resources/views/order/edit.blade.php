<x-layouts.app :title="'Edit Pesanan #' . $order->transaction_code">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Edit Pesanan</h1>
            <p class="text-sm text-gray-500 mt-1">Kode: {{ $order->transaction_code }}</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('orders.detail', $order) }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition">
                Kembali
            </a>
            <button type="submit" form="editOrderForm" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                Simpan Perubahan
            </button>
        </div>
    </div>
    @if(session('error'))
          <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
              <strong class="font-bold">Error: </strong> {{ session('error') }}
              <button type="button" class="absolute top-2 right-2 text-red-500 hover:text-red-700" onclick="this.parentElement.remove()" aria-label="Close">
                  &times;
              </button>
          </div>
      @endif

      @if(session('success'))
          <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
              <strong class="font-bold">Success: </strong> {{ session('success') }}
              <button type="button" class="absolute top-2 right-2 text-green-500 hover:text-green-700" onclick="this.parentElement.remove()" aria-label="Close">
                  &times;
              </button>
          </div>
      @endif
    <form id="editOrderForm" method="POST" action="{{ route('orders.update', $order) }}">
        @csrf
        @method('PUT')

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
                <div class="mt-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-2">Catatan</h2>
                    <textarea name="catatan" class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm resize-none min-h-[80px]">{{ $order->catatan }}</textarea>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-medium text-gray-900">Daftar Menu</h2>
                </div>
                
                <!-- Input Form for Adding/Editing Items -->
                <div id="itemInputForm" class="bg-gray-50 p-4 rounded-lg mb-6">
                  <div class="flex flex-wrap items-end space-x-4">
                    <!-- Product Selection -->
                    <div class="flex flex-col">
                      <label for="produk" class="text-sm font-medium mb-1 text-gray-700">Produk</label>
                      <select id="produk" class="menu-select border border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm px-3 py-2 w-48">
                        @foreach($activeMenus as $category => $menus)
                          <optgroup label="{{ $category }}">
                            @foreach($menus as $menu)
                              <option value="{{ $menu->id }}" data-price="{{ $menu->harga }}">
                                {{ $menu->nama }} (Rp {{ number_format($menu->harga, 0, ',', '.') }})
                              </option>
                            @endforeach
                          </optgroup>
                        @endforeach
                      </select>
                    </div>

                    <!-- Notes Input -->
                    <div class="flex flex-col flex-1 min-w-[150px]">
                      <label for="catatan" class="text-sm font-medium mb-1 text-gray-700">Catatan</label>
                      <input type="text" id="catatan" placeholder="Catatan item..."
                            class="border border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm px-3 py-2 w-full">
                    </div>

                    <!-- Quantity Input -->
                    <div class="flex flex-col w-20">
                      <label for="qty" class="text-sm font-medium mb-1 text-gray-700">Qty</label>
                      <input type="number" id="qty" value="1" min="1"
                            class="qty-input border border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm px-3 py-2">
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex space-x-2">
                      <button type="button" id="addItemBtn"
                              class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition flex items-center justify-center">
                        +
                      </button>
                      <button type="button" id="updateItemBtn"
                              class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition hidden">
                        +
                      </button>
                      <button type="button" id="cancelEditBtn"
                              class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition hidden">
                        Batal
                      </button>
                    </div>
                  </div>
                </div>


                <!-- Items Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Menu</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="orderItems">
                        @foreach($order->orderDetails as $item)
                            <tr class="item-row" data-id="{{ $item->id }}" data-index="{{ $loop->index }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="menu-name">{{ $item->menu->nama }}</span>
                                    <input type="hidden" name="items[{{ $loop->index }}][menu_id]" value="{{ $item->menu_id }}">
                                    <input type="hidden" name="items[{{ $loop->index }}][id]" value="{{ $item->id }}">
                                    <input type="hidden" name="items[{{ $loop->index }}][harga]" value="{{ $item->harga }}" class="price-input">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap price-display">
                                    Rp {{ number_format($item->harga, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="qty-display">{{ $item->qty }}</span>
                                    <input type="hidden" name="items[{{ $loop->index }}][qty]" value="{{ $item->qty }}">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap subtotal-display">
                                    Rp {{ number_format($item->harga * $item->qty, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="catatan-display">{{ $item->catatan }}</span>
                                    <input type="hidden" name="items[{{ $loop->index }}][catatan]" value="{{ $item->catatan }}">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex space-x-2">
                                        <button type="button" class="edit-btn text-blue-600 hover:text-blue-900 text-sm font-medium transition flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Edit
                                        </button>
                                        <button type="button" class="remove-btn text-red-600 hover:text-red-900 text-sm font-medium transition flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </form>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize variables
        let currentEditingRow = null;
        let itemCounter = {{ count($order->orderDetails) }};
        
        const addItemBtn = document.getElementById('addItemBtn');
        const updateItemBtn = document.getElementById('updateItemBtn');
        const cancelEditBtn = document.getElementById('cancelEditBtn');
        
        // Add new item
        addItemBtn.addEventListener('click', function() {
            addNewItem();
        });
        
        // Update existing item
        updateItemBtn.addEventListener('click', function() {
            updateExistingItem();
        });
        
        // Cancel edit
        cancelEditBtn.addEventListener('click', cancelEdit);
        
        // Function to add a new item
        function addNewItem() {
          const inputs = document.querySelectorAll('input[name*="[menu_id]"]');
          const menuSelect = document.getElementById('produk');
          const qtyInput = document.getElementById('qty');
          const catatanInput = document.getElementById('catatan');

          // Find the input that matches the selected value
          const matchingInput = Array.from(inputs).find(input => input.value === menuSelect.value);

          if (matchingInput) {
              // Get the parent tr element
              currentEditingRow = matchingInput.closest('tr');
              
              // Now you can work with the tr element
              updateExistingItem();
              return;
          }
            // Validate input
            if (!menuSelect.value || parseInt(qtyInput.value) < 1) {
                alert('Please select a product and enter a valid quantity');
                return;
            }
            
            const selectedOption = menuSelect.options[menuSelect.selectedIndex];
            const menuId = selectedOption.value;
            const menuName = selectedOption.text.split(' (')[0];
            const price = selectedOption.getAttribute('data-price');
            const qty = qtyInput.value;
            const catatan = catatanInput.value;
            const subtotal = price * qty;
            
            // Create a new row
            const newRow = document.createElement('tr');
            newRow.className = 'item-row';
            newRow.dataset.id = 'new-' + itemCounter;
            newRow.dataset.index = itemCounter;
            
            newRow.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="menu-name">${menuName}</span>
                    <input type="hidden" name="items[${itemCounter}][menu_id]" value="${menuId}">
                    <input type="hidden" name="items[${itemCounter}][id]" value="">
                    <input type="hidden" name="items[${itemCounter}][harga]" value="${price}" class="price-input">
                </td>
                <td class="px-6 py-4 whitespace-nowrap price-display">
                    Rp ${parseInt(price).toLocaleString('id-ID')}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="qty-display">${qty}</span>
                    <input type="hidden" name="items[${itemCounter}][qty]" value="${qty}">
                </td>
                <td class="px-6 py-4 whitespace-nowrap subtotal-display">
                    Rp ${subtotal.toLocaleString('id-ID')}
                </td>
                <td class="px-6 py-4">
                    <span class="catatan-display">${catatan}</span>
                    <input type="hidden" name="items[${itemCounter}][catatan]" value="${catatan}">
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex space-x-2">
                        <button type="button" class="edit-btn text-blue-600 hover:text-blue-900 text-sm font-medium transition flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit
                        </button>
                        <button type="button" class="remove-btn text-red-600 hover:text-red-900 text-sm font-medium transition flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Hapus
                        </button>
                    </div>
                </td>
            `;
            
            document.getElementById('orderItems').appendChild(newRow);
            
            // Add event listeners to the new buttons
            newRow.querySelector('.edit-btn').addEventListener('click', function() {
                editItem(this);
            });
            
            newRow.querySelector('.remove-btn').addEventListener('click', function() {
                removeItem(this);
            });
            
            itemCounter++;
            
            // Reset the form
            menuSelect.selectedIndex = 0;
            qtyInput.value = 1;
            catatanInput.value = '';
        }
        
        // Function to update existing item
        function updateExistingItem() {
            if (!currentEditingRow) {
                console.error('No row is being edited');
                return;
            }
            
            const menuSelect = document.getElementById('produk');
            const qtyInput = document.getElementById('qty');
            const catatanInput = document.getElementById('catatan');
            
            // Validate input
            if (!menuSelect.value || parseInt(qtyInput.value) < 1) {
                alert('Please select a product and enter a valid quantity');
                return;
            }
            
            const selectedOption = menuSelect.options[menuSelect.selectedIndex];
            const menuId = selectedOption.value;
            const menuName = selectedOption.text.split(' (')[0];
            const price = selectedOption.getAttribute('data-price');
            const qty = qtyInput.value;
            const catatan = catatanInput.value;
            const subtotal = price * qty;
            
            // Update the row
            const index = currentEditingRow.dataset.index;
            
            currentEditingRow.querySelector('.menu-name').textContent = menuName;
            currentEditingRow.querySelector('input[name*="[menu_id]"]').value = menuId;
            currentEditingRow.querySelector('.price-input').value = price;
            currentEditingRow.querySelector('.price-display').textContent = 'Rp ' + parseInt(price).toLocaleString('id-ID');
            currentEditingRow.querySelector('.qty-display').textContent = qty;
            currentEditingRow.querySelector('input[name*="[qty]"]').value = qty;
            currentEditingRow.querySelector('.subtotal-display').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
            currentEditingRow.querySelector('.catatan-display').textContent = catatan;
            currentEditingRow.querySelector('input[name*="[catatan]"]').value = catatan;
            
            // Reset edit state
            cancelEdit();
        }
        
        // Function to edit an item (setup the form for editing)
        function editItem(button) {
            const row = button.closest('tr');
            currentEditingRow = row;
            console.log('row', row);
            // Get data from row
            const menuId = row.querySelector('input[name*="[menu_id]"]').value;
            const qty = row.querySelector('input[name*="[qty]"]').value;
            const catatan = row.querySelector('input[name*="[catatan]"]').value;
            
            // Set form values
            document.getElementById('produk').value = menuId;
            document.getElementById('qty').value = qty;
            document.getElementById('catatan').value = catatan;
            
            // Show update and cancel buttons, hide add button
            addItemBtn.classList.add('hidden');
            updateItemBtn.classList.remove('hidden');
            cancelEditBtn.classList.remove('hidden');
        }
        
        function cancelEdit() {
            // Reset form
            document.getElementById('produk').selectedIndex = 0;
            document.getElementById('qty').value = 1;
            document.getElementById('catatan').value = '';
            
            // Show add button, hide update and cancel buttons
            addItemBtn.classList.remove('hidden');
            updateItemBtn.classList.add('hidden');
            cancelEditBtn.classList.add('hidden');
            
            currentEditingRow = null;
        }
        
        function removeItem(button) {
            const row = button.closest('tr');
            
            // Add animation for removal
            row.classList.add('bg-red-50');
            setTimeout(() => {
                row.style.transition = 'opacity 0.3s ease-out';
                row.style.opacity = 0;
                
                setTimeout(() => {
                    row.remove();
                    reindexItems();
                }, 300);
            }, 50);
        }
        
        function reindexItems() {
            const rows = document.querySelectorAll('#orderItems tr.item-row');
            rows.forEach((row, index) => {
                // Update all hidden inputs with new index
                row.dataset.index = index;
                const inputs = row.querySelectorAll('input');
                inputs.forEach(input => {
                    if (input.name) {
                        input.name = input.name.replace(/items\[\d+\]/, `items[${index}]`);
                    }
                });
            });
        }
        
        // Add event listeners to existing edit and remove buttons
        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                editItem(this);
            });
        });
        
        document.querySelectorAll('.remove-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                removeItem(this);
            });
        });
    });
</script>
</x-layouts.app>