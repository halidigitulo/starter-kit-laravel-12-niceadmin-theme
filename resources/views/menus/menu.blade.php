<div class="row my-3">
    <div class="col">
        @can('menus.create')
            <button class="btn btn-primary mb-3" id="btn-add-menu"><i class="ri-add-line"></i> Add Menu</button>
        @endcan
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped" id="menu-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>URL</th>
                        <th>Icon</th>
                        <th>Parent</th>
                        <th>Permission</th>
                        <th>Protected</th>
                        <th>Action</th>
                    </tr>
                </thead>
                {{-- <tbody></tbody> --}}
            </table>
        </div>


        <!-- Modal -->
        <div class="modal fade" id="modalMenu" tabindex="-1">
            <div class="modal-dialog">
                <form id="formMenu">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"><i class="ri-add-line"></i> Add Menu</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            @csrf
                            <input type="hidden" id="menu_id">
                            <input type="text" class="form-control mb-2" id="menu_name" placeholder="Name" required>
                            <input type="text" class="form-control mb-2" id="menu_url"
                                placeholder="URL (default: #)">
                            <input type="text" class="form-control mb-2" id="menu_icon"
                                placeholder="Icon (default: circle-line)">
                            <select class="mb-2 form-select select2" id="menu_parent_id" name="parent_id">
                                <option value="">-- No Parent (Root) --</option>
                                @foreach ($menus as $menu)
                                    <option value="{{ $menu->id }}">{{ $menu->name }}</option>
                                @endforeach
                            </select>

                            <input type="text" class="form-control mb-2" id="menu_permission" name="permission_name"
                                placeholder="Permission Name">
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success"><i class="ri-save-line"></i> Save</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i
                                    class="ri-close-line"></i> Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@push('style')
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script>
        $(function() {
            fetchData();

            function fetchData() {
                $(document).ready(function() {

                    $('#menu-table').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: {
                            url: '{{ route('menus.index') }}',
                            type: 'GET'
                        },
                        columns: [{
                                data: 'DT_RowIndex',
                                name: 'DT_RowIndex',
                                orderable: false,
                                searchable: false
                            },
                            {
                                data: 'name',
                                name: 'name'
                            },
                            {
                                data: 'url',
                                name: 'url'
                            },
                            {
                                data: 'icon',
                                name: 'icon'
                            },
                            {
                                data: 'parent_name',
                                name: 'parent_name'
                            },
                            {
                                data: 'permission_name',
                                name: 'permission_name'
                            },
                            {
                                data: 'protected',
                                name: 'protected'
                            },
                            {
                                data: 'aksi',
                                name: 'aksi'
                            }
                        ],
                        order: [
                            [1, 'asc']
                        ], // Default ordering by the second column (kode_bank)
                        responsive: true, // Makes the table responsive
                        autoWidth: false, // Prevents auto-sizing of columns
                        lengthMenu: [
                            [5, 10, 25, 50, -1],
                            [5, 10, 25, 50, "All"]
                        ], // Controls the page length options
                        pageLength: 5, // Default page length
                        // columnDefs: [{
                        //     targets: [0, 1, 2], // Adjust column indexes as needed
                        //     className: 'center-align'
                        // }]
                    });
                });
            }

            $('#btn-add-menu').click(() => {
                $('#modalMenu').modal('show');
                $('#formMenu')[0].reset();
                $('#menu_id').val('');
                $('.modal-title').text('Add Menu');
            });

            $(document).on('click', '.edit-user', function() {
                let id = $(this).data('id');
                $.get(`/menus/${id}`, function(data) {
                    $('#modalMenu').modal('show');
                    $('.modal-title').text('Edit Menu');
                    $('#menu_id').val(data.id);
                    $('#menu_name').val(data.name);
                    $('#menu_url').val(data.url);
                    $('#menu_icon').val(data.icon);
                    $('#menu_parent_id').val(data.parent_id);
                    let parentSelect = document.querySelector('#menu_parent_id').tomselect;
                    parentSelect.setValue(data.parent_id || '');
                    $('#menu_permission').val(data.permission_name);
                });
            });

            $('#formMenu').submit(function(e) {
                e.preventDefault();
                let id = $('#menu_id').val();
                let url = id ? `/menus/${id}` : `{{ route('menus.store') }}`;
                let method = id ? 'PUT' : 'POST';

                $.ajax({
                    url,
                    type: 'POST',
                    data: {
                        _token: $('input[name=_token]').val(),
                        _method: method,
                        name: $('#menu_name').val(),
                        url: $('#menu_url').val() || '#',
                        icon: $('#menu_icon').val() || 'circle-line',
                        parent_id: $('#menu_parent_id').val(),
                        permission_name: $('#menu_permission').val(),
                    },
                    success: function(res) {
                        $('#menu-table').DataTable().ajax.reload();
                        Swal.fire({
                            title: 'Success!',
                            text: res.message,
                            icon: 'success',
                            timer: 3000,
                            toast: true,
                            position: 'top-end',
                        });
                        $('#modalMenu').modal('hide');
                        $('#formMenu')[0].reset(); // Reset form
                    },
                    error: function(err) {
                        Swal.fire('Error', 'Failed to save menu', 'error');
                    }
                });
            });

            $(document).on('click', '.hapus-user', function() {
                let id = $(this).data('id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This action cannot be undone!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                }).then(result => {
                    if (result.isConfirmed) {
                        $.post(`/menus/${id}`, {
                            _token: "{{ csrf_token() }}",
                            _method: "DELETE"
                        }, function(response) {
                            $('#menu-table').DataTable().ajax.reload();
                            Swal.fire('Deleted!', response.message, 'success');
                            // fetchData();
                        });
                    }
                });
            });

        });
    </script>
    <script>
        $('#modalMenu').on('shown.bs.modal', function() {
            const selects = ['#menu_parent_id'];

            selects.forEach(function(selector) {
                new TomSelect(selector, {
                    sortField: {
                        field: "text",
                        direction: "asc"
                    },
                    // dropdownParent: $('#modal-personalia'), // Ensure the dropdown remains properly aligned in the modal
                    closeAfterSelect: true // Optional: close dropdown after selecting an option
                });
            });
        });
    </script>
@endpush
