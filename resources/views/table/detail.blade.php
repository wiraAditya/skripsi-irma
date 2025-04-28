<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Menu Detail') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Menu Detail</h3>
                        <a href="{{ route('table.index') }}" class="btn btn-secondary">
                            Back
                        </a>
                    </div>
                    <div class="overflow-x-hidden">
                        <div class="row mb-3">
                            <div class="col-4 fw-bold">
                                ID
                            </div>
                            <div class="col-8">
                                {{ $menu->id }}
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-4 fw-bold">
                                Name
                            </div>
                            <div class="col-8">
                                {{ $menu->name }}
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-4 fw-bold">
                                QR Link
                            </div>
                            <div class="col-8">
                                {{ $menu->qr_link }}
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-4 fw-bold">
                                QR Code
                            </div>
                            <div class="col-8">
                                <img src="{{ asset('/storage/table/qr-codes/' . $menu->qr_url) }}" alt="QR Codes"
                                    class="img-fluid rounded mb-2">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-4 fw-bold">
                                Status
                            </div>
                            <div class="col-8">
                                {{ $menu->is_active ? 'Active' : 'Not Active' }}
                            </div>
                        </div>
                        <button class="btn btn-md btn-warning" onclick="openEditModal( {{ $menu->id }} )">
                            Edit
                        </button>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <footer class="mt-8 text-center text-sm text-gray-600">
                © 2025 Admin Panel. All rights reserved.
            </footer>
        </div>
    </div>

    @include('components.table.edit-modal', ['categories' => $categories])

    @section('script')
        <script>
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
