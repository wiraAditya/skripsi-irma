<div class="modal fade" id="createTableModal" tabindex="-1" aria-labelledby="createTableModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('table.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="createTableModalLabel">New Table</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>

                    <div class="mb-3">
                        <label for="qr_link" class="form-label">QR Link</label>
                        <input type="text" class="form-control" id="qr_link" name="qr_link" readonly required>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="is_active" name="is_active" required>
                            <option value="1">Active</option>
                            <option value="0">Not Active</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('modal-create-script')
    <script>
        $(document).ready(function() {
            $('#name').on('input', function() {
                let baseUrl = "{{ $baseUrl }}"; // ambil dari config
                $('#name').on('input', function() {
                    let nameValue = $(this).val();
                    $('#qr_link').val(baseUrl + encodeURIComponent(nameValue));
                });
            });
        });
    </script>
@endsection
