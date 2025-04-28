<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Menu') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-gray-500 text-sm mb-2">Total Menu</h3>
                    <p class="text-3xl font-bold">{{ $totalData }}</p>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Menu</h3>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#createMenuModal">
                            New Data
                        </button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="table table-bordered data-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <footer class="mt-8 text-center text-sm text-gray-600">
                © 2025 Admin Panel. All rights reserved.
            </footer>
        </div>
    </div>

    @include('components.menu.create-modal', ['categories' => $categories])
    @include('components.menu.edit-modal', ['categories' => $categories])

    @section('script')
        <script>
            $(function() {
                var table = $('.data-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('menu.index') }}",
                    columns: [{
                            data: 'id',
                            name: 'id'
                        },
                        {
                            data: 'image',
                            name: 'image'
                        },
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'description',
                            name: 'description'
                        },
                        {
                            data: 'price',
                            name: 'price'
                        },
                        {
                            data: 'is_active',
                            name: 'is_active'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: true,
                            searchable: true
                        },
                    ]
                });
            });

            $(document).ready(function() {
                $(document).on('click', '.delete-btn', function() {
                    var id = $(this).data('id');
                    if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                        $.ajax({
                            url: `{{ route('menu.destroy', ':id') }}`.replace(':id', id),
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                // Reload datatable
                                Swal.fire(
                                    'Success!',
                                    'Data deleted successfully.',
                                    'success'
                                );
                                $('.data-table').DataTable().ajax.reload(null, false);
                            },
                            error: function(xhr) {
                                alert('Error deleting data.');
                            }
                        });
                    }
                });
            });

            function previewImage(event) {
                const reader = new FileReader();
                const imagePreview = document.getElementById('imagePreview');

                if (!imagePreview) {
                    console.error('Element imagePreview not found!');
                    return;
                }

                reader.onload = function() {
                    imagePreview.style.display = 'block';
                    imagePreview.src = reader.result;
                }

                if (event.target.files[0]) {
                    reader.readAsDataURL(event.target.files[0]);
                } else {
                    imagePreview.style.display = 'none';
                    imagePreview.src = "#";
                }
            }
            window.previewImage = previewImage;

            function openEditModal(menu) {
                fetch(`/menu/${menu}/edit`)
                    .then(response => response.json())
                    .then(data => {
                        $('#editMenuForm').attr('action', `/menu/${data.id}`);

                        $('#edit_category').val(data.menu_category_id);
                        $('#edit_name').val(data.name);
                        $('#edit_description').val(data.description);
                        $('#edit_price').val(data.price);
                        $('#edit_status').val(data.is_active ? '1' : '0');

                        const preview = document.getElementById('editImagePreview');
                        if (data.image) {
                            preview.style.display = 'block';
                            preview.src = data.image.startsWith('http') ? data.image :
                                '/storage/menu_images/' + `${data.image}`;
                        } else {
                            preview.style.display = 'none';
                            preview.src = "#";
                        }

                        new bootstrap.Modal(document.getElementById('editMenuModal')).show();
                    })
                    .catch(error => console.error('Error:', error));
            }

            function previewEditImage(event) {
                const reader = new FileReader();
                const preview = document.getElementById('editImagePreview');

                reader.onload = function() {
                    preview.style.display = 'block';
                    preview.src = reader.result;
                }

                if (event.target.files[0]) {
                    reader.readAsDataURL(event.target.files[0]);
                } else {
                    preview.style.display = 'none';
                    preview.src = "#";
                }
            }
        </script>
    @endsection
</x-app-layout>
