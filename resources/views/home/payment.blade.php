<x-layouts.app.clean :title="'Payment'">

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-md mx-auto">
            <!-- Order Summary -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h1 class="text-2xl font-bold mb-6">Complete Your Payment</h1>

                <!-- Payment Summary -->
                <div class="mb-8">
                    <div class="space-y-2 mb-6">
                        <div class="flex justify-between">
                            <span class="font-medium">Total Amount</span>
                            <span class="font-bold">Rp {{ number_format($subTotal * 1.1, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- Customer Information -->
                    <div class="mb-6">
                        <h2 class="text-lg font-semibold mb-4">Customer Information</h2>
                        <div class="space-y-4">
                            <div>
                                <label for="customer-name"
                                    class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                                <input type="text" id="customer-name"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500"
                                    placeholder="Masukkan nama Anda">
                            </div>
                            <div>
                                <label for="customer-notes"
                                    class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                                <textarea id="customer-notes" rows="3"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500"
                                    placeholder="Tambahkan catatan (opsional)"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Methods -->
                    <div>
                        <h2 class="text-lg font-semibold mb-4">Select Payment Method</h2>
                        <div class="space-y-4">
                            <!-- Cash Payment -->
                            <div class="payment-option" onclick="selectPayment('cash')">
                                <input type="radio" id="cash" name="payment" class="hidden">
                                <label for="cash" class="flex items-center p-4 border-2 rounded-lg cursor-pointer">
                                    <div
                                        class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center mr-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="font-medium">Cash</h3>
                                        <p class="text-sm text-gray-500">Pay with cash</p>
                                    </div>
                                </label>
                            </div>

                            <!-- Digital Payment -->
                            <div class="payment-option" onclick="selectPayment('digital')">
                                <input type="radio" id="digital" name="payment" class="hidden">
                                <label for="digital" class="flex items-center p-4 border-2 rounded-lg cursor-pointer">
                                    <div
                                        class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center mr-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="font-medium">Digital</h3>
                                        <p class="text-sm text-gray-500">Pay with QRIS/EWallet</p>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Button -->
                    <div class="mt-8">
                        <button id="pay-button"
                            class="w-full bg-teal-600 text-white py-3 px-4 rounded-md hover:bg-teal-700 disabled:opacity-50 disabled:cursor-not-allowed"
                            disabled onclick="processPayment()">
                            Complete Payment
                        </button>
                    </div>
                </div>
            </div>

            <!-- Back to Cart -->
            <div class="text-center">
                <a href="{{ route('cart.index') . (request()->getQueryString() ? '?' . request()->getQueryString() : '') }}"
                    class="text-teal-600 hover:text-teal-800 font-medium">
                    ← Back to Cart
                </a>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}"></script>
    <script>
        let selectedPayment = null;
        const totalAmount = {{ $subTotal * 1.1 }};
        const tableId = "{{ $table->unique_code ?? '' }}";
        const csrfToken = "{{ csrf_token() }}";

        function selectPayment(method) {
            selectedPayment = method;

            // Update UI
            document.querySelectorAll('.payment-option').forEach(option => {
                const label = option.querySelector('label');
                if (option.querySelector('input').id === method) {
                    label.classList.add('border-teal-500', 'bg-teal-50');
                    label.classList.remove('border-gray-200');
                } else {
                    label.classList.remove('border-teal-500', 'bg-teal-50');
                    label.classList.add('border-gray-200');
                }
            });

            // Enable payment button
            document.getElementById('pay-button').disabled = false;
        }

        function createAndSubmitForm(methodName) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = "{{ route('payment.paid') }}";

            // Add CSRF token
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken;
            form.appendChild(csrfInput);

            // Add method parameter
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = 'method';
            methodInput.value = methodName;
            form.appendChild(methodInput);

            // Add customer name
            const nameInput = document.createElement('input');
            nameInput.type = 'hidden';
            nameInput.name = 'nama';
            nameInput.value = document.getElementById('customer-name').value;
            form.appendChild(nameInput);

            // Add customer notes
            const notesInput = document.createElement('input');
            notesInput.type = 'hidden';
            notesInput.name = 'catatan';
            notesInput.value = document.getElementById('customer-notes').value;
            form.appendChild(notesInput);

            // Add table ID if available
            if (tableId) {
                const tableInput = document.createElement('input');
                tableInput.type = 'hidden';
                tableInput.name = 'mejaId';
                tableInput.value = tableId;
                form.appendChild(tableInput);
            }

            // Submit the form
            document.body.appendChild(form);
            form.submit();
        }

        function processPayment() {
            if (!selectedPayment) {
                alert('Please select a payment method');
                return;
            }

            // Log payment data to console
            console.log('Processing payment:', {
                method: selectedPayment,
                amount: totalAmount,
                tableId: tableId
            });

            if (selectedPayment === 'cash') {
                createAndSubmitForm('method_cash');
            } else {
                fetch('/token', {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Token received:', data);
                        snap.pay(data.token, {
                            onSuccess: function(result) {
                                console.log('Payment success:', result);
                                createAndSubmitForm('method_digital');
                            },
                            onPending: function(result) {
                                console.log('Payment pending:', result);
                            },
                            onError: function(result) {
                                console.log('Payment error:', result);
                            }
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching token:', error);
                    });
            }
        }
    </script>
    <style>
        .payment-option label {
            border-color: #e5e7eb;
            transition: all 0.2s ease;
        }

        .payment-option label:hover {
            transform: translateY(-2px);
            border-color: #0d9488;
        }
    </style>
</x-layouts.app.clean>
