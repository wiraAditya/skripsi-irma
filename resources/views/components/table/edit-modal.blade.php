<div class="modal fade" id="editTableModal" tabindex="-1" aria-labelledby="editTableModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="editTableForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editTableModalLabel">Edit Table</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit_qr_link" class="form-label">QR Link</label>
                        <input class="form-control" id="edit_qr_link" name="qr_link" rows="3" readonly></input>
                    </div>

                    <div class="mb-3">
                        <label for="edit_status" class="form-label">Status</label>
                        <select class="form-select" id="edit_status" name="is_active" required>
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

@section('modal-edit-script')
    <script>
        $(document).ready(function() {
            $('#edit_name').on('input', function() {
                let baseUrl = "{{ $baseUrl }}"; // ambil dari config
                $('#edit_name').on('input', function() {
                    let nameValue = $(this).val();
                    $('#edit_qr_link').val(baseUrl + encodeURIComponent(nameValue));
                });
            });
        });
    </script>
@endsection
