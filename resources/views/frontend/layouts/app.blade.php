<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="{{ asset('css/frontend.css') }}" rel="stylesheet" />

<body class="antialiased dark:bg-black dark:text-white/50">
    <main>
        {{ $slot }}
    </main>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous">
    </script>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000
        });

        @if (Session::has('message'))
            var type = "{{ Session::get('alert-type', 'info') }}";
            switch (type) {
                case 'info':
                    Toast.fire({
                        icon: 'info',
                        title: '{{ Session::get('message') }}'
                    });
                    break;
                case 'warning':
                    Toast.fire({
                        icon: 'warning',
                        title: '{{ Session::get('message') }}'
                    });
                    break;
                case 'success':
                    Toast.fire({
                        icon: 'success',
                        title: '{{ Session::get('message') }}'
                    });
                    break;
                case 'error':
                    Toast.fire({
                        icon: 'error',
                        title: '{{ Session::get('message') }}'
                    });
                    break;
            }
        @endif

        function addToCart(menuId) {
            $.ajax({
                url: '/add-to-cart',
                method: 'POST',
                data: {
                    menu_id: menuId,
                    quantity: 1,
                    table: $('[data-table-name]').data('tableName'),
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    Toast.fire({
                        icon: 'success',
                        title: response.message
                    });
                },
                error: function(xhr) {
                    Toast.fire({
                        icon: 'error',
                        title: xhr.responseJSON.message
                    });
                }
            });
        }

        $('.badge').click(function() {
            var categoryId = $(this).data('category-id'); // ambil category id
            var currentUrl = new URL(window.location.href); // ambil URL sekarang

            currentUrl.searchParams.set('category_id', categoryId); // update atau tambah param category_id

            if (categoryId == 0) {
                currentUrl.searchParams.delete('category_id');
            }

            $('.badge').removeClass('active');
            $(this).addClass('active');

            window.location.href = currentUrl.toString(); // reload dengan URL baru
        });

        var currentUrl = new URL(window.location.href);
        var activeCategoryId = currentUrl.searchParams.get('category_id');

        if (activeCategoryId) {
            $('.badge').each(function() {
                if ($(this).data('category-id') == activeCategoryId) {
                    $(this).addClass('active');
                }
            });
        } else {
            $('.badge[data-category-id="0"]').addClass('active');
        }

        function increaseQuantity(menuId, quantity) {
            const input = $('#quantity-input-' + menuId);
            $.ajax({
                url: '/add-to-cart',
                method: 'POST',
                data: {
                    menu_id: menuId,
                    quantity: quantity,
                    table: $('[data-table-name]').data('tableName'),
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    input.value = parseInt(input.value) + quantity;
                    location.reload();
                },
                error: function(xhr) {
                    Toast.fire({
                        icon: 'error',
                        title: xhr.responseJSON.message
                    });
                }
            });
        }

        function decreaseQuantity(menuId, quantity) {
            const input = $('#quantity-input-' + menuId);
            $.ajax({
                url: '/add-to-cart',
                method: 'POST',
                data: {
                    menu_id: menuId,
                    quantity: quantity,
                    table: $('[data-table-name]').data('tableName'),
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    input.value = parseInt(input.value) + quantity;
                    location.reload();
                },
                error: function(xhr) {
                    Toast.fire({
                        icon: 'error',
                        title: xhr.responseJSON.message
                    });
                }
            });
        }

        $('#form-checkout').submit(function(e) {
            e.preventDefault(); // Mencegah form agar tidak melakukan reload halaman

            var formData = $(this).serialize(); // Mengambil data dari form

            $.ajax({
                url: '{{ route('checkout.store') }}', // URL untuk menangani checkout
                method: 'POST',
                data: formData,
                success: function(response) {
                    if (response.snap_token) {
                        snap.pay(response.snap_token, {
                            onSuccess: function(result) {
                                fetch('/payment-success', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'Accept': 'application/json',
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                        },
                                        body: JSON.stringify({
                                            order_id: result.order_id,
                                            transaction_status: result
                                                .transaction_status
                                        })
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        console.log('Payment Success API response:',
                                            data);
                                        window.location.href = '/payment/success';
                                    })
                                    .catch(error => {
                                        console.error('Error calling paymentSuccess:',
                                            error);
                                    });
                            },
                            onPending: function(result) {
                                console.log(result);
                            },
                            onError: function(result) {
                                console.log(result);
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'success',
                            title: response.message
                        }).then(() => {
                            window.location.href = '/payment/success';
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Checkout Failed',
                        text: xhr.responseJSON.message
                    });
                }
            });
        });
    </script>
</body>

</html>
