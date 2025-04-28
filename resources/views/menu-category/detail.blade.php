<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Menu Category Detail') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Menu Category Detail</h3>
                        <a href="{{ route('menu-category.index') }}" class="btn btn-secondary">
                            Back
                        </a>
                    </div>
                    <div class="overflow-x-hidden">
                        <div class="row mb-3">
                            <div class="col-4 fw-bold">
                                ID
                            </div>
                            <div class="col-8">
                                {{ $data->id }}
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-4 fw-bold">
                                Name
                            </div>
                            <div class="col-8">
                                {{ $data->name }}
                            </div>
                        </div>
                        <button class="btn btn-md btn-warning" onclick="openEditModal( {{ $data->id }} )">
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

    @include('components.menu-category.edit-modal')

    @section('script')
        <script>
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
