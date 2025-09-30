@extends('layouts.app')
@section('title', 'Role Management')
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center mb-2" style="height: 60px">
                    <h4 class="card-title"><i class="ri-settings-line"></i> @yield('title')</h4>
                    @can('roles.create')
                    <div>
                        <div class="col">
                            <button type="button" class="btn btn-primary" id="btn-add-role">+ Add Role</button>
                        </div>
                    </div>
                    @endcan
                </div>
                <div class="card-body">
                    <div class="table-reponsive">
                        <table class="table table-bordered table-hover table-striped table-sm" id="role-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Role</th>
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
                                    <th>
                                        <div class="row">
                                            <div class="col d-flex justify-content-between">
                                                <div>
                                                    Modul
                                                </div>
                                                <div>
                                                    <!-- ✅ Centang semua -->
                                                    <input type="checkbox" id="checkAll" class="select-all">
                                                    <label for="checkAll" class="form-check-label">Check All</label>
                                                </div>
                                            </div>
                                        </div>
                                    </th>
                                    <th class="text-center">Create</th>
                                    <th class="text-center">Read</th>
                                    <th class="text-center">Update</th>
                                    <th class="text-center">Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($groupedPermissions as $module => $actions)
                                    <tr>
                                        <td>
                                            <div class="row">
                                                <div class="col d-flex justify-content-between">
                                                    <div>{{ ucfirst($module) }} </div>
                                                    <div>
                                                        <!-- ✅ Centang per modul -->
                                                        <input type="checkbox" class="check-module text-end select-all"
                                                            data-module="{{ $module }}"
                                                            id="check_{{ $module }}">
                                                        <label for="check_{{ $module }}"
                                                            class="form-check-label">Check All</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        @foreach (['create', 'read', 'update', 'delete'] as $action)
                                            <td class="text-center">
                                                @if (isset($actions[$action]))
                                                    <input type="checkbox" name="permissions[]"
                                                        class="form-check-input permission-checkbox permission-{{ $module }}"
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
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="ri-close-line"></i>Cancel
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(function() {
            $(document).ready(function() {
                // ✅ Centang semua modul + permissions
                $("#checkAll").on("change", function() {
                    let status = $(this).is(":checked");
                    $(".permission-checkbox, .check-module").prop("checked", status);
                });

                // ✅ Centang per modul
                $(".check-module").on("change", function() {
                    let module = $(this).data("module");
                    let status = $(this).is(":checked");
                    $(".permission-" + module).prop("checked", status);

                    // update global check all
                    updateGlobalCheckAll();
                });

                // ✅ Sinkronisasi balik: kalau ada perubahan di permission
                $(".permission-checkbox").on("change", function() {
                    let classes = $(this).attr("class").split(" ");
                    let moduleClass = classes.find(c => c.startsWith("permission-"));
                    let module = moduleClass.replace("permission-", "");

                    let total = $(".permission-" + module).length;
                    let checked = $(".permission-" + module + ":checked").length;

                    // 🔄 update check-module sesuai kondisi
                    $(".check-module[data-module='" + module + "']").prop("checked", total ===
                        checked);

                    // update global check all
                    updateGlobalCheckAll();
                });

                // ✅ Fungsi bantu: update global check all
                function updateGlobalCheckAll() {
                    let allCheckbox = $(".permission-checkbox").length;
                    let allChecked = $(".permission-checkbox:checked").length;
                    $("#checkAll").prop("checked", allCheckbox > 0 && allCheckbox === allChecked);
                    $(".check_{{ $module }}").prop("checked", allCheckbox > 0 && allCheckbox === allChecked);
                }
            });


            loadRoles();

            function loadRoles() {
                if ($.fn.DataTable.isDataTable('#role-table')) {
                    $('#role-table').DataTable().clear().destroy(); // destroy dulu
                }

                $('#role-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('roles.index') }}",
                        type: 'GET'
                    },
                    columns: [{
                            orderable: false,
                            render: function(data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1;
                            }
                        },
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'permissions',
                            name: 'permissions',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        }
                    ],
                    order: [
                        [1, 'asc']
                    ],
                    responsive: true,
                    autoWidth: false,
                    lengthMenu: [
                        [10, 25, 50, -1],
                        [10, 25, 50, "All"]
                    ],
                    pageLength: 10,
                    language: {
                        lengthMenu: "Show _MENU_", // supaya tampil dropdown lengthMenu
                        search: "Search:",
                        paginate: {
                            previous: "Prev",
                            next: "Next"
                        }
                    }
                });
            }

            let currentPermissions = [];

            const permissionTable = $('#permissionTable').DataTable({
                paging: true,
                searching: true,
                ordering: false,
                info: false,
                pageLength: 5,
                lengthMenu: [5, 10, 20, 50],
            });

            // ----- Helpers sinkronisasi -----
            function syncModuleToggleState(module) {
                const total = permissionTable.$(`.permission-${module}`, {
                    page: 'all'
                }).length;
                const checked = permissionTable.$(`.permission-${module}:checked`, {
                    page: 'all'
                }).length;
                // toggle per modul yang terlihat (di halaman sekarang) diset sesuai agregat semua halaman
                $(`#permissionTable .check-module[data-module="${module}"]`).prop('checked', total > 0 && total ===
                    checked);
            }

            function syncGlobalToggleState() {
                const totalAll = permissionTable.$('.permission-checkbox', {
                    page: 'all'
                }).length;
                const checkedAll = permissionTable.$('.permission-checkbox:checked', {
                    page: 'all'
                }).length;
                $('#checkAll').prop('checked', totalAll > 0 && totalAll === checkedAll);
            }

            // ----- Apply currentPermissions setiap draw (untuk centang sesuai DB) -----
            permissionTable.on('draw.dt', function() {
                if (currentPermissions.length > 0) {
                    // hanya perlu set yang tampil (halaman current); DataTables menyimpan state untuk halaman lain via event berikutnya
                    $('#permissionTable input.permission-checkbox').each(function() {
                        $(this).prop('checked', currentPermissions.includes(this.value));
                    });
                }
                // update switch per modul yang terlihat & switch global
                $('#permissionTable .check-module').each(function() {
                    syncModuleToggleState($(this).data('module'));
                });
                syncGlobalToggleState();
            });

            // ===== Delegated events (bekerja di semua halaman) =====

            // Global Check All
            $('#permissionTable').on('change', '#checkAll', function() {
                const checked = this.checked;
                // centang semua permission di SEMUA halaman
                permissionTable.$('.permission-checkbox', {
                    page: 'all'
                }).prop('checked', checked);
                // set semua toggle modul yang sedang terlihat agar konsisten
                $('#permissionTable .check-module').prop('checked', checked);
            });

            // Check All per modul
            $('#permissionTable').on('change', '.check-module', function() {
                const module = $(this).data('module');
                const checked = this.checked;
                // centang semua permission modul tsb di SEMUA halaman
                permissionTable.$(`.permission-${module}`, {
                    page: 'all'
                }).prop('checked', checked);
                // set global toggle
                syncGlobalToggleState();
            });

            // Per permission
            $('#permissionTable').on('change', '.permission-checkbox', function() {
                const moduleClass = this.className.split(' ').find(c => c.startsWith('permission-'));
                if (!moduleClass) return;
                const module = moduleClass.replace('permission-', '');
                syncModuleToggleState(module);
                syncGlobalToggleState();
            });

            // ====== Open modal Add ======
            $('#btn-add-role').on('click', function() {
                $('#modalRole').modal('show');
                $('#formRole')[0].reset();
                currentPermissions = []; // reset state DB
                // kosongkan semua centang di semua halaman
                permissionTable.$('.permission-checkbox', {
                    page: 'all'
                }).prop('checked', false);
                // reset toggle
                $('#checkAll').prop('checked', false);
                $('#permissionTable .check-module').prop('checked', false);
                $('#role_id').val('');
                $('.modal-title').text('Add Role');
            });

            // ====== Open modal Edit ======
            $(document).on('click', '.btn-edit', function() {
                const id = $(this).data('id');
                $.get(`/roles/${id}`, function(data) {
                    $('#modalRole').modal('show');
                    $('#role_id').val(data.id);
                    $('#role_name').val(data.name);

                    currentPermissions = data.permissions || [];

                    // set semua permission sesuai DB di SEMUA halaman
                    permissionTable.$('.permission-checkbox', {
                        page: 'all'
                    }).each(function() {
                        $(this).prop('checked', currentPermissions.includes(this.value));
                    });

                    // sinkronkan toggle setelah set
                    $('#permissionTable .check-module').each(function() {
                        syncModuleToggleState($(this).data('module'));
                    });
                    syncGlobalToggleState();

                    $('.modal-title').text('Edit Role');
                }).fail(() => {
                    Swal.fire('Error', 'Gagal memuat data role.', 'error');
                });
            });

            // ===== Submit =====
            $('#formRole').on('submit', function(e) {
                e.preventDefault();
                const id = $('#role_id').val();
                const url = id ? `/roles/${id}` : `{{ route('roles.store') }}`;
                const method = id ? 'PUT' : 'POST';

                // ambil SEMUA checkbox tercentang dari SEMUA halaman
                const selectedPermissions = permissionTable.$('input.permission-checkbox:checked', {
                        page: 'all'
                    })
                    .map(function() {
                        return $(this).val();
                    }).get();

                // kalau edit & tidak ada perubahan, jangan kirim
                const before = (currentPermissions || []).slice().sort().join(',');
                const after = selectedPermissions.slice().sort().join(',');
                if (id && before === after) {
                    $('#modalRole').modal('hide');
                    Swal.fire('Info', 'Tidak ada perubahan yang disimpan.', 'info');
                    return;
                }

                $.ajax({
                    url,
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        _method: method,
                        name: $('#role_name').val(),
                        permissions: selectedPermissions
                    },
                    success: function(res) {
                        $('#modalRole').modal('hide');
                        Swal.fire('Success', res.message, 'success');
                        loadRoles(); // milikmu untuk table daftar role
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
        });
    </script>
    
@endpush
