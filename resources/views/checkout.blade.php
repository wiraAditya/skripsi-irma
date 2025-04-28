<x-frontend-layout>
    <div class="container px-3">
        <div class="my-3">
            <p class="text-center fs-1 fw-bold" style="font-family: Playfair Display, Serif">ORDER REVIEW</p>
        </div>
        <div class="row">
            <div class="col-12 col-lg-6 order-last order-lg-first my-5">
                <div style="font-family: 'Courier New', Courier, monospace">
                    <h4 class="mb-2 fw-bold">Order Details</h4>
                    <div class="row mb-3">
                        <div class="col-12">Order date {{ Carbon\Carbon::parse($order->date)->format('l, j F Y') }}</div>
                        <div class="col-12" data-table-name="{{ $table?->name }}">Table {{ $table?->name }}</div>
                    </div>

                    <form id="form-checkout">
                        @csrf
                        <h4 class="mb-2 fw-bold">Guest Details</h4>
                        <input type="hidden" name="order_id" value="{{ $order->id }}">
                        <input type="hidden" name="table_id" value="{{ $table->id }}">
                        <div class="row mb-3">
                            <div class="col-12 col-md-6 col-lg-6 mb-3">
                                <label for="guest_name" class="form-label">Name</label>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                            <div class="col-12 col-md-6 col-lg-6 mb-3">
                                <label for="guest_email" class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" required>
                            </div>
                        </div>

                        <h5 class="mb-2 fw-bold">How would you like to pay?</h5>
                        <div class="row mb-4">
                            <div class="col-lg-6 col-sm-12 mb-3">
                                <div class="card d-flex flex-column h-100">
                                    <label class="card-body flex-fill h-100 m-0">
                                        <div class="h-100">
                                            <div class="text-center fs-5 p-3">
                                                <p>Online Payment Methods</p>
                                            </div>
                                            <div class="text-center mb-3">
                                                <input type="radio" name="payment_method" value="midtrans"
                                                    class="form-selectgroup-input" required>
                                            </div>
                                            <div class="text-center mb-3 fs-5">
                                                <p class="fw-bold">MIDTRANS</p>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12 mb-3">
                                <div class="card d-flex flex-column h-100">
                                    <label class="card-body flex-fill h-100 m-0">
                                        <div class="h-100">
                                            <div class="text-center fs-5 p-3">
                                                <p>Offline Payment Methods</p>
                                            </div>
                                            <div class="text-center mb-3">
                                                <input type="radio" name="payment_method" value="cashier"
                                                    class="form-selectgroup-input" required>
                                            </div>
                                            <div class="text-center mb-3 fs-5">
                                                <p class="fw-bold">CASHIER</p>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12 mt-3 text-center">
                            <button type="submit" class="btn w-100" style="background-color: #80492A; color: white">Pay
                                Now</button>
                        </div>
                    </form>

                </div>
            </div>
            <div class="col-12 col-lg-6 order-first order-lg-last my-5">
                <div class="card">
                    <div class="card-body">
                        @foreach ($order->details as $detail)
                            <div class="row border-bottom p-2">
                                <div class="col-3 d-flex flex-column justify-content-center">
                                    <img src="{{ asset('/storage/menu_images/' . $detail->menu?->image) }}"
                                        alt="Menu Image" class="img-fluid mb-2" style="max-height: 125px">
                                </div>
                                <div class="col-7 d-flex flex-column justify-content-center">
                                    <div class="fw-bold fs-5">
                                        {{ $detail->quantity }}x {{ $detail->menu->name }}
                                    </div>
                                    <div class="mb-3">
                                        {{ 'Rp ' . number_format($detail->total_price, 0, ',', '.') }}
                                    </div>
                                    <div class="input-group mb-3" style="max-width: 150px">
                                        <a href="javascript:void(0)"
                                            onclick="decreaseQuantity('{{ $detail->menu->id }}', -1)"
                                            class="btn btn-outline-secondary" type="button" id="button-decrease"
                                            data-menu-id="{{ $detail->menu->id }}">-</a>
                                        <input type="number" class="form-control text-center border-0"
                                            value="{{ $detail->quantity }}"
                                            id="quantity-input-{{ $detail->menu->id }}" readonly>
                                        <a href="javascript:void(0)"
                                            onclick="increaseQuantity('{{ $detail->menu->id }}', 1)"
                                            class="btn btn-outline-secondary" type="button" id="button-increase"
                                            data-menu-id="{{ $detail->menu->id }}">+</a>
                                    </div>
                                </div>
                                <div class="col-2 d-flex flex-column justify-content-center">
                                    <div class="text-danger delete-item fs-2" style="cursor: pointer"
                                        data-menu-id="{{ $detail->menu->id }}" data-order-id="{{ $order->id }}">
                                        <i class="bi bi-x"></i>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div class="d-flex flex-row justify-content-between p-2">
                            <div class="fw-bold fs-4">Total</div>
                            <div class="fs-4">{{ 'Rp ' . number_format($order->total_price, 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-frontend-layout>
