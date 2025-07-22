@extends('layouts.app')
@section('title', 'User Management')
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"><i class="ri-settings-line"></i> @yield('title')</h4>
                </div>
                <div class="card-body">
                    @can('users.create')
                        <div class="row">
                            <div class="col">
                                    <a href="" class="btn btn-primary waves-effect waves-light" data-bs-toggle="modal"
                                        data-bs-target="#userModal" id="addUserButton"><i class="ri-add-line"></i> Add User</a>
                            </div>
                        </div>
                    @endcan
                    <div class="row">
                        <div class="col table-responsive">
                            <table id="table_user" class="table table-hover table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Avatar</th>
                                        <th>Nama</th>
                                        <th>Username</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>

                    {{-- Modal  --}}
                    <div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="userModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="userModalLabel">Add User</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form id="userForm" enctype="multipart/form-data">
                                    <div class="modal-body">
                                        <input type="hidden" id="user_id">
                                        <div class="form-group">
                                            <label for="name">Nama</label>
                                            <input type="text" class="form-control" id="name" name="name" autocomplete="off"
                                                required>
                                        </div>
                                        <div class="form-group">
                                            <label for="username">Username</label>
                                            <input type="text" class="form-control" id="username" name="username" autocomplete="off" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="password">Password</label>
                                            <input type="password" class="form-control" id="password" name="password"
                                                autocomplete="off">
                                        </div>
                                        <div class="form-group">
                                            <label for="role">Role</label>
                                            <select name="role_id" id="user_role_id" class="form-control select2">
                                                <option value="">-- Pilih Role --</option>
                                                @foreach ($role as $role)
                                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <p class="text-muted">Foto</p>
                                            <input type="file" class="form-control" id="avatar" name="avatar"
                                                onchange="previewFoto(event)">
                                            <img id="preview-foto" alt="Preview" class="rounded img-fluid mt-2"
                                                style="max-width: 200px;">
                                        </div>
                                        <div class="form-group mt-3">
                                            <label for="is_active" class="form-check-label">Aktif?</label>
                                            <input type="checkbox" id="is_active" name="is_active" class="form-check-input">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light waves-effect"
                                            data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary" id="saveuser">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('style')
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script>
        function previewFoto(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const output = document.getElementById('preview-foto');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }

        $(document).ready(function() {
            fetchData();
            const canUpdateStatus = @json($canUpdateStatus);
            // Include CSRF token in all AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // ambil data
            function fetchData() {
                var storageBaseUrl = "{{ asset('uploads/users') }}";
                $('#table_user').DataTable({
                    serverSide: true,
                    processing: true,
                    ajax: {
                        url: "{{ route('users.index') }}",
                        type: 'GET'
                    },
                    columns: [{
                            orderable: false,
                            render: function(data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1;
                            }
                        },
                        {
                            data: 'avatar',
                            name: 'avatar',
                            orderable: false,
                            searchable: false,
                            render: function(data, type, row) {
                                if (data) {
                                    return `<img src="${storageBaseUrl}/${data}" alt="Photo" class="img-thumbnail rounded-circle square-image" style="width: 50px; height: 50px;" />`;
                                } else {
                                    return `<span class="text-muted">No Photo</span>`;
                                }
                            }
                        },
                        {
                            data: 'name',
                            name: 'name',
                        },
                        {
                            data: 'username',
                            name: 'username',
                        },

                        {
                            data: 'role',
                            name: 'role_id',
                        },
                        {
                            data: 'is_active',
                            name: 'is_active',
                            orderable: false,
                            render: function(data, type, row) {
                                let checked = data == 1 ? 'checked' : '';
                                if (canUpdateStatus) {
                                    return `<input type="checkbox" class="form-check-input check-user" data-id="${row.id}" ${checked} />`;
                                } else {
                                    return `<input type="checkbox" class="form-check-input" disabled ${checked} title="Tidak punya akses" />`;
                                }
                            }
                        },
                        {
                            data: 'aksi',
                            name: 'aksi',
                            orderable: false
                        },
                    ],
                    // "columnDefs": [{
                    //     "targets": [3], // Center Price, Quantity, and Total columns
                    //     "className": "text-center"
                    // }],
                    order: [
                        [1, 'asc']
                    ], // Default ordering by the second column (kode_user)
                    responsive: true, // Makes the table responsive
                    autoWidth: false, // Prevents auto-sizing of columns
                    lengthMenu: [
                        [10, 25, 50, -1],
                        [10, 25, 50, "All"]
                    ], // Controls the page length options
                    pageLength: 10, // Default page length
                })
            }

            // Show modal for creating a new record
            $('#adduserButton').click(function() {
                $('#userForm')[0].reset(); // Clear the form
                $('#user_id').val(''); // Clear hidden input
                $('#userModalLabel').text('Add User'); // Set modal title
                $('#saveuser').text('Create'); // Change button text
                $('#userModal').modal('show'); // Show modal
            });


            $(document).on('click', '.edit-user', function() {
                const id = $(this).data('id'); // Ambil ID dari tombol yang diklik

                $.ajax({
                    url: `/users/${id}/edit`,
                    type: 'GET',
                    headers: {
                        'Accept': 'application/json'
                    },
                    success: function(data) {
                        $('#user_id').val(data.id);
                        $('#name').val(data.name);
                        $('#username').val(data.username);
                        $('#password').val('');
                        $('#user_role_id').val(data.role_id);
                        $('#is_active').prop('checked', data.is_active == 1);
                        if (data.avatar) {
                            $('#preview-foto').attr('src',
                                `/uploads/users/${data.avatar}`).show();
                        } else {
                            $('#preview-foto').hide();
                        }
                        $('#userModalLabel').text('Edit User');
                        $('#saveuser').text('Update');
                        $('#userModal').modal('show');
                    },
                    error: function(err) {
                        if (err.status === 403) {
                            Swal.fire('Akses Ditolak', err.responseJSON?.message ||
                                'Anda tidak punya izin', 'error');
                        } else {
                            Swal.fire('Gagal', 'Terjadi kesalahan', 'error');
                        }
                    }
                });
            });

            // Handle form submission for both create and update
            $('#userForm').submit(function(e) {
                e.preventDefault(); // Prevent default form submission

                let id = $('#user_id').val();
                let url = id ? `/users/${id}` :
                    '/users'; // Update if ID exists, otherwise create
                let method = id ? 'POST' :
                    'POST'; // Laravel doesn't support PUT with FormData in jQuery

                let formData = new FormData(this); // Automatically includes all input fields
                // Set the value for is_active based on its checked state
                formData.set('is_active', $('#is_active').is(':checked') ? '1' : '0');
                // Append the _method for PUT requests
                if (id) {
                    formData.append('_method', 'PUT');
                }
                formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

                $.ajax({
                    url: url,
                    type: method,
                    data: formData,
                    contentType: false, // **Important for file uploads**
                    processData: false, // **Prevent jQuery from processing FormData**
                    success: function(response) {
                        $('#table_user').DataTable().ajax.reload();
                        Swal.fire({
                            title: 'Success!',
                            text: response.message,
                            icon: 'success',
                            timer: 3000,
                            toast: true,
                            position: 'top-end',
                        });
                        $('#userModal').modal('hide'); // Hide the modal
                        $('#userForm')[0].reset(); // Reset the form
                    },
                    error: function(xhr) {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Something went wrong.',
                            icon: 'error',
                            timer: 3000,
                            toast: true,
                            position: 'top-end',
                        });
                    },
                });
            });

            // update status via table
            $(document).on('change', '.check-user', function() {
                let is_active = $(this).is(':checked') ? 1 : 0;
                let id = $(this).data('id');

                $.ajax({
                    url: `/users/${id}`, // Define your route for updating status
                    type: 'PUT',
                    data: {
                        id: id,
                        is_active: is_active,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        Swal.fire({
                            position: "top-end",
                            width: '400px',
                            icon: 'success',
                            title: 'Sukses',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1000,
                            toast: true,
                            background: '#28a745',
                            color: '#fff'
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            title: 'Error!',
                            text: xhr.responseJSON.text ||
                                'An error occurred.',
                            icon: 'error',
                            position: 'top-end',
                            width: '400px',
                            showConfirmButton: false,
                            timer: 3000,
                            toast: true,
                            background: '#dc3545',
                            color: '#fff'
                        });
                    }
                });
            });

            // Hapus Data 
            $(document).on('click', '.hapus-user', function() {
                let id = $(this).data('id');
                let deleteUrl = '{{ route('users.destroy', ':id') }}'.replace(':id', id);

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This action cannot be undone!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: deleteUrl,
                            type: 'DELETE',
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                $('#table_user').DataTable().ajax
                                    .reload(); // Reload the DataTable
                                Swal.fire('Deleted!', response.message,
                                    'success');
                            },
                            error: function(xhr) {
                                Swal.fire('Error!',
                                    'Failed to delete record.',
                                    'error');
                            },
                        });
                    }
                });
            });
        })
    </script>
    <script>
        $('#userModal').on('shown.bs.modal', function() {
            const selects = ['#user_role_id'];

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
