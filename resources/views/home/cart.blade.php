<x-layouts.app.clean :title="'Home'">
    @section('content')
        <div class="container mx-auto px-4 py-8">
            <h1 class="text-2xl font-bold mb-6">Shopping Cart</h1>

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                    role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if (count($cartItems) > 0)
                <div class="flex flex-col md:flex-row gap-6">
                    <!-- Cart Items -->
                    <div class="w-full md:w-2/3">
                        <div class="bg-white rounded-lg shadow-md p-6 mb-4">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b">
                                        <th class="text-left py-2">Item</th>
                                        <th class="text-center py-2">Price</th>
                                        <th class="text-center py-2">Quantity</th>
                                        <th class="text-center py-2">Total</th>
                                        <th class="text-right py-2">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cartItems as $index => $item)
                                        <tr class="border-b">
                                            <td class="py-4">
                                                <div class="flex items-center">
                                                    <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}"
                                                        class="h-16 w-16 object-cover rounded mr-4">
                                                    <div>
                                                        <h3 class="font-medium text-gray-900">{{ $item['name'] }}</h3>
                                                        @if (!empty($item['notes']))
                                                            <p class="text-sm text-gray-500">Note: {{ $item['notes'] }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center py-4">Rp {{ number_format($item['price'], 0, ',', '.') }}
                                            </td>
                                            <td class="text-center py-4">{{ $item['quantity'] }}</td>
                                            <td class="text-center py-4">Rp
                                                {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</td>
                                            <td class="text-right py-4">
                                                <div class="flex justify-end space-x-2">
                                                    <button type="button" class="edit-cart-item-btn"
                                                        data-index="<?= $index ?>" data-item='<?= json_encode($item) ?>'
                                                        class="text-blue-600 hover:text-blue-800">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                            viewBox="0 0 20 20" fill="currentColor">
                                                            <path
                                                                d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                                        </svg>
                                                    </button>
                                                    <a href="{{ route('cart.remove', ['id' => $index]) . (request()->getQueryString() ? '?' . request()->getQueryString() : '') }}"
                                                        onclick="return confirm('Are you sure you want to remove this item?')"
                                                        class="text-red-600 hover:text-red-800">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                            viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd"
                                                                d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="flex justify-between mt-4">
                            <a href="{{ route('home') . (request()->getQueryString() ? '?' . request()->getQueryString() : '') }}"
                                class="bg-gray-200 text-gray-800 py-2 px-4 rounded hover:bg-gray-300">
                                Continue Shopping
                            </a>
                            <form action="{{ route('cart.clear', ['mejaId' => $table->unique_code ?? '']) }}"
                                method="POST">
                                @csrf
                                <button type="submit" class="bg-red-500 text-white py-2 px-4 rounded hover:bg-red-600">
                                    Clear Cart
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="w-full md:w-1/3">
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <h2 class="text-lg font-semibold mb-4">Order Summary</h2>
                            <div class="flex justify-between mb-2">
                                <span>Subtotal</span>
                                <span>Rp {{ number_format($subTotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between mb-2">
                                <span>Tax (10%)</span>
                                <span>Rp {{ number_format($subTotal * 0.1, 0, ',', '.') }}</span>
                            </div>
                            <div class="border-t pt-2 mt-2">
                                <div class="flex justify-between font-semibold">
                                    <span>Grand Total</span>
                                    <span>Rp {{ number_format($subTotal * 1.1, 0, ',', '.') }}</span>
                                </div>
                            </div>
                            <div class="mt-6">
                                <a href="{{ route('payment.index') . (request()->getQueryString() ? '?' . request()->getQueryString() : '') }}"
                                    class="block w-full bg-teal-600 text-white text-center py-3 px-4 rounded-md hover:bg-teal-700">
                                    Proceed to Payment
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <div class="mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-semibold mb-2">Your cart is empty</h2>
                    <p class="text-gray-600 mb-6">Looks like you haven't added anything to your cart yet.</p>
                    <a href="{{ route('home') . (request()->getQueryString() ? '?' . request()->getQueryString() : '') }}"
                        class="bg-teal-600 text-white py-2 px-4 rounded-md hover:bg-teal-700">
                        Start Shopping
                    </a>
                </div>
            @endif
            <x-order-modal />

        </div>

        <!-- Edit Cart Item Script -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const editModal = document.getElementById('addToCartModal');
                const cartForm = document.getElementById('cartItemForm');
                // Handle edit cart item button clicks
                document.querySelectorAll('.edit-cart-item-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        console.log('click hit')
                        const itemData = JSON.parse(this.dataset.item);
                        cartForm.action = "{{ route('cart.update') }}"
                        // Set form values
                        document.getElementById('editItemIndex').value = this.dataset.index;
                        document.getElementById('modalMenuId').value = itemData.id;
                        document.getElementById('modalMenuName').textContent = itemData.name;
                        document.getElementById('modalMenuNameInput').value = itemData.name;
                        document.getElementById('modalMenuPrice').value = itemData.price;
                        document.getElementById('modalMenuImageInput').value = itemData.image || '';
                        document.getElementById('quantity').value = itemData.quantity;
                        document.getElementById('notes').value = itemData.notes || '';

                        // Set image display
                        const imgElement = document.getElementById('modalMenuImage');
                        const imgContainer = imgElement.parentElement;
                        const iconElement = imgContainer.querySelector('i');

                        if (iconElement) {
                            iconElement.remove();
                        }

                        if (itemData.image && itemData.image !== '-') {
                            imgElement.src = `${itemData.image}`;
                            imgElement.alt = itemData.name;
                            imgElement.classList.remove('hidden');
                        } else {
                            imgElement.classList.add('hidden');
                            imgContainer.insertAdjacentHTML('beforeend',
                                `<i class="fas fa-utensils text-2xl text-gray-400"></i>`);
                        }

                        editModal.classList.remove('hidden');
                    });
                });

            });
        </script>

    </x-layouts.app.clean>
