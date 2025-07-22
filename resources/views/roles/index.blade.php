@extends('layouts.app')
@section('title', 'Role Management')
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"><i class="ri-settings-line"></i> @yield('title')</h4>
                </div>
                <div class="card-body">
                    @can('roles.create')
                        <button class="btn btn-primary mb-3" id="btn-add-role">+ Add Role</button>
                    @endcan
                    <div class="table-reponsive">
                        <table class="table table-bordered table-hover table-striped table-sm" id="role-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Permissions</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalRole" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <form id="formRole">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Role</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" id="role_id">
                        <input type="text" class="form-control mb-2" id="role_name" placeholder="Role name">

                        <label>Permissions:</label><br>
                        <table id="permissionTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Modul</th>
                                    <th class="text-center">Create</th>
                                    <th class="text-center">Read</th>
                                    <th class="text-center">Update</th>
                                    <th class="text-center">Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($groupedPermissions as $module => $actions)
                                    <tr>
                                        <td>{{ ucfirst($module) }}</td>
                                        @foreach (['create', 'read', 'update', 'delete'] as $action)
                                            <td class="text-center">
                                                @if (isset($actions[$action]))
                                                    <input type="checkbox" name="permissions[]"
                                                        class="form-check-input permission-checkbox"
                                                        id="perm_{{ $actions[$action]->id }}"
                                                        value="{{ $actions[$action]->name }}">
                                                @else
                                                    ❌
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        @can('roles.create')
                            <button type="submit" class="btn btn-success"><i class="ri-save-line"></i> Save</button>
                        @endcan
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i
                                class="ri-close-line"></i>Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            loadRoles();

            function loadRoles() {
                $.get("{{ route('roles.index') }}", function(res) {
                    if ($.fn.DataTable.isDataTable('#role-table')) {
                        $('#role-table').DataTable().destroy();
                    }

                    $('#role-table tbody').html('');

                    $.each(res.roles, function(i, r) {
                        $('#role-table tbody').append(`
                <tr>
                    <td>${r.name}</td>
                    <td>
                        ${r.permissions.map(p => {
                            let color = 'secondary';
                            if (p.name.includes('.create')) color = 'success';
                            else if (p.name.includes('.read')) color = 'info';
                            else if (p.name.includes('.update')) color = 'warning';
                            else if (p.name.includes('.delete')) color = 'danger';
                            return `<span class="badge bg-${color} me-1">${p.name}</span>`;
                        }).join('')}
                    </td>
                    <td>
                        @can('roles.create')
                        <button class="btn btn-sm btn-warning btn-edit" data-id="${r.id}"><i class="ri-pencil-line"></i></button>
                        @endcan
                            
                            @can('roles.delete')
                        <button class="btn btn-sm btn-danger btn-delete" data-id="${r.id}"><i class="ri-delete-bin-6-line"></i></button>
                        @endcan
                    </td>
                </tr>
            `);
                    });

                    $('#role-table').DataTable({
                        responsive: true,
                        paging: true,
                        searching: true,
                        ordering: false,
                        info: true
                    });
                });
            }

            $('#btn-add-role').click(() => {
                $('#modalRole').modal('show');
                $('#formRole')[0].reset();
                $('.permission-checkbox').prop('checked', false);
                $('#role_id').val('');
                $('.modal-title').text('Add Role');
            });

            $(document).on('click', '.btn-edit', function() {
                let id = $(this).data('id');
                $.get(`/roles/${id}`, function(data) {
                    $('#modalRole').modal('show');
                    $('#role_id').val(data.id);
                    $('#role_name').val(data.name);
                    $('.permission-checkbox').prop('checked', false);
                    data.permissions.forEach(p => {
                        $(`.permission-checkbox[value="${p}"]`).prop('checked', true);
                    });
                });
            });

            $('#formRole').submit(function(e) {
                e.preventDefault();
                let id = $('#role_id').val();
                let url = id ? `/roles/${id}` : `{{ route('roles.store') }}`;
                let method = id ? 'PUT' : 'POST';
                let permissions = $('.permission-checkbox:checked').map(function() {
                    return this.value;
                }).get();

                $.ajax({
                    url,
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        _method: method,
                        name: $('#role_name').val(),
                        permissions
                    },
                    success: function(res) {
                        $('#modalRole').modal('hide');
                        Swal.fire('Success', res.message, 'success');
                        loadRoles();
                    }
                });
            });

            $(document).on('click', '.btn-delete', function() {
                let id = $(this).data('id');
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
                        $.post(`/roles/${id}`, {
                            _token: "{{ csrf_token() }}",
                            _method: 'DELETE'
                        }, function(res) {
                            Swal.fire('Deleted!', res.message, 'success');
                            loadRoles();
                        });
                    }
                });
            });

            $('#permissionTable').DataTable({
                paging: true,
                searching: true,
                ordering: false,
                info: false,
                pageLength: 50,
                lengthMenu: [5, 10, 20, 50, ]
            });
        });
    </script>
@endpush
