<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Table') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-gray-500 text-sm mb-2">Total Table</h3>
                    <p class="text-3xl font-bold">{{ $totalData }}</p>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Table</h3>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#createTableModal">
                            New Data
                        </button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="table table-bordered data-table" id="table-index">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>QR Link</th>
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

    @include('components.table.create-modal', ['baseUrl' => $baseUrl])
    @include('components.table.edit-modal', ['baseUrl' => $baseUrl])

    @section('script')
        <script>
            $(function() {
                var table = $('#table-index').DataTable({ // Gunakan ID untuk inisialisasi
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('table.index') }}",
                        type: 'GET',
                        error: function(xhr) {
                            console.error('AJAX Error:', xhr.statusText);
                        }
                    },
                    columns: [{
                            data: 'id',
                            name: 'id'
                        },
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'qr_link',
                            name: 'qr_link'
                        },
                        {
                            data: 'is_active',
                            name: 'is_active',
                            render: function(data, type, row) {
                                return data ? 'Active' : 'Inactive';
                            }
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        }
                    ]
                });
            })

            $(document).ready(function() {
                $(document).on('click', '.delete-btn', function() {
                    var id = $(this).data('id');
                    if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                        $.ajax({
                            url: `{{ route('table.destroy', ':id') }}`.replace(':id', id),
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

            function openEditModal(table) {
                fetch(`/table/${table}/edit`)
                    .then(response => response.json())
                    .then(data => {
                        $('#editTableForm').attr('action', `/table/${data.id}`);

                        $('#edit_name').val(data.name);
                        $('#edit_qr_link').val(data.qr_link);
                        $('#edit_status').val(data.is_active ? '1' : '0');

                        new bootstrap.Modal(document.getElementById('editTableModal')).show();
                    })
                    .catch(error => console.error('Error:', error));
            }
        </script>
    @endsection
</x-app-layout>
