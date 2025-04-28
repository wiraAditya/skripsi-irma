<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Menu Category') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-gray-500 text-sm mb-2">Total Menu Category</h3>
                    <p class="text-3xl font-bold">{{ $totalData }}</p>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Menu Category</h3>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#createMenuCategoryModal">
                            New Data
                        </button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="table table-bordered data-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
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

    @include('components.menu-category.create-modal')
    @include('components.menu-category.edit-modal')

    @section('script')
        <script>
            $(function() {
                var table = $('.data-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('menu-category.index') }}",
                    columns: [{
                            data: 'id',
                            name: 'id'
                        },
                        {
                            data: 'name',
                            name: 'name'
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
                            url: `{{ route('menu-category.destroy', ':id') }}`.replace(':id', id),
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

            function openEditModal(menuCategory) {
                fetch(`/menu-category/${menuCategory}/edit`)
                    .then(response => response.json())
                    .then(data => {
                        $('#editMenuCategoryForm').attr('action', `/menu-category/${data.id}`);

                        $('#edit_name').val(data.name);

                        new bootstrap.Modal(document.getElementById('editMenuCategoryModal')).show();
                    })
                    .catch(error => console.error('Error:', error));
            }
        </script>
    @endsection
</x-app-layout>
