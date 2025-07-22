@extends('layouts.app')
@section('title', 'Generate Permissions')
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"><i class="ri-settings-line"></i> @yield('title')</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            @can('permissions.create')
                                
                            <form id="generate-permission-form">
                                @csrf
                                <div class="mb-3">
                                    <label for="modules">Nama Modul (pisahkan dengan koma):</label>
                                    <input type="text" id="modules" name="modules" class="form-control"
                                    placeholder="Contoh: user,role,menu,article">
                                </div>
                                <button type="submit" id="generate-permission-btn"
                                class="btn btn-primary">Generate</button>
                            </form>
                            
                            <div id="result" class="mt-3"></div>
                            @endcan
                        </div>
                    </div>
                    <div class="row">
                        <div class="col table-responsive">
                            <table class="table table-bordered table-striped" id="permission-table">
                                <thead>
                                    <tr>
                                        <th style="width: 20px">#</th>
                                        <th>Nama Permission</th>
                                        <th style="width: 5px" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                {{-- <tbody></tbody> --}}
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            fetchData();

            $('#generate-permission-form').submit(function(e) {
                e.preventDefault();

                const modules = $('#modules').val().trim();

                if (!modules) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Field kosong',
                        text: 'Silakan isi nama modul terlebih dahulu.',
                        toast: true,
                        position: 'top-end',
                        timer: 3000
                    });
                    return;
                }

                $.ajax({
                    url: "{{ route('permissions.generate') }}",
                    method: 'POST',
                    data: {
                        _token: $('input[name=_token]').val(),
                        modules: modules
                    },
                    success: function(res) {
                        // Tampilkan hasil created
                        let html = '<ul>';
                        res.created.forEach(function(perm) {
                            html += `<li>${perm}</li>`;
                        });
                        html += '</ul>';
                        $('#result').html(html);
                        $('#permission-table').DataTable().ajax.reload();


                        // Tangani permission duplikat
                        if (res.duplicate.length > 0) {
                            Swal.fire({
                                icon: 'info',
                                title: 'Permission duplikat',
                                html: `
                                Permission berikut sudah ada:<br>
                                <b>${res.duplicate.join('<br>')}</b>
                                <hr>
                                <button class="btn btn-danger mt-2" id="delete-duplicate">Hapus Permission Duplikat</button>
                            `,
                                showConfirmButton: false
                            });

                            // Listener hapus permission duplikat
                            $(document).off('click', '#delete-duplicate').on('click',
                                '#delete-duplicate',
                                function() {
                                    $.ajax({
                                        url: "{{ route('permissions.generate') }}",
                                        method: 'POST',
                                        data: {
                                            _token: $('input[name=_token]').val(),
                                            modules: modules,
                                            hapus_duplikat: true
                                        },
                                        success: function(delRes) {
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Permission duplikat berhasil dihapus',
                                                html: delRes.duplicate
                                                    .length > 0 ?
                                                    delRes.duplicate
                                                    .join('<br>') :
                                                    'Tidak ada permission yang dihapus',
                                                toast: true,
                                                position: 'top-end',
                                                timer: 3000
                                            });
                                            $('#permission-table').DataTable()
                                                .ajax.reload();

                                        },
                                        error: function() {
                                            Swal.fire('Gagal',
                                                'Gagal menghapus permission duplikat',
                                                'error');
                                        }
                                    });
                                });

                        } else {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: res.message,
                                toast: true,
                                position: 'top-end',
                                timer: 3000
                            });
                            $('#permission-table').DataTable().ajax.reload();
                        }
                    },
                    error: function() {
                        Swal.fire('Gagal', 'Permission gagal digenerate', 'error');
                    }
                });
            });
        });

        function fetchData() {
            $('#permission-table').DataTable({
                serverSide: true,
                processing: true,
                ajax: {
                    url: "{{ route('permissions.index') }}",
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
                        name: 'name',
                        render: function(data, type, row) {
                            // Render input inline edit
                            return `
                        <input type="text" 
                            class="form-control edit-permission" 
                            value="${htmlEscape(data)}" 
                            data-id="${row.id}">
                    `;
                        }
                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
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

        // Escape untuk karakter HTML agar tidak error
        function htmlEscape(str) {
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;');
        }

        // Inline Edit Permission
        // Event: Simpan saat blur
        $(document).on('change', '.edit-permission', function() {
            const input = $(this);
            const id = input.data('id');
            const name = input.val();

            $.ajax({
                url: `/permission/${id}`,
                method: 'PUT',
                data: {
                    _token: '{{ csrf_token() }}',
                    name: name,
                },
                success: function(res) {
                    Swal.fire({
                        // title: res.message,
                        title: 'Success!',
                        text: res.message,
                        toast: true,
                        icon: 'success',
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000
                    });
                    table.ajax.reload(null, false);
                },
                error: function(err) {
                    const msg = err.responseJSON?.message || 'Terjadi kesalahan';
                    Swal.fire('Gagal', msg, 'error');
                }
            });
        });

        // Event opsional: tekan Enter untuk simpan
        $(document).on('keypress', '.edit-permission', function(e) {
            if (e.which === 13) {
                $(this).blur(); // trigger blur = simpan
            }
        });

        // Hapus Permission
        $(document).on('click', '.hapus-permission', function() {
            const id = $(this).data('id');

            Swal.fire({
                title: 'Yakin menghapus?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/permission/${id}`,
                        method: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(res) {
                            $('#permission-table').DataTable().ajax.reload();
                            Swal.fire({
                                // title: res.message,
                                title: 'Success!',
                                text: res.message,
                                toast: true,
                                icon: 'success',
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 2000
                            });
                        },
                        error: function() {
                            Swal.fire('Gagal', 'Gagal menghapus permission', 'error');
                        }
                    });
                }
            });
        });
    </script>
@endpush
