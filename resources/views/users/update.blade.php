@extends('layouts.app')
@section('title', 'User Profile')

@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title"><i class="ri-user-line"></i> @yield('title')</h4>
            </div>
            <div class="card-body">
                <form id="form_profile" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-3 text-center">
                            <h5 class="text-muted mb-3">Avatar</h5>
                            <img id="preview-avatar" src="{{ $user->avatar ? asset('uploads/users/' . $user->avatar) : 'https://via.placeholder.com/100' }}"
                                alt="{{ $user->name }}" class="rounded img-fluid mb-3" style="height: 100px;">
                            <input type="file" class="form-control" name="avatar" onchange="previewAvatar(event)">
                        </div>

                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="username">Username</label>
                                    <input type="text" class="form-control" value="{{ $user->username }}" readonly>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="name">Nama</label>
                                    <input type="text" class="form-control" name="name" value="{{ old('name', $user->name) }}" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="password">Password Baru (Opsional)</label>
                                    <input type="password" class="form-control" name="password" placeholder="Kosongkan jika tidak ingin ubah">
                                </div>

                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ri-save-line"></i> Simpan Perubahan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function previewAvatar(event) {
        const preview = document.getElementById('preview-avatar');
        preview.src = URL.createObjectURL(event.target.files[0]);
        preview.onload = () => URL.revokeObjectURL(preview.src);
    }

    $('#form_profile').on('submit', function (e) {
        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({
            url: "{{ route('users.updateProfile') }}",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: res => {
                Swal.fire({
                    toast: true,
                    icon: 'success',
                    position: 'top-end',
                    title: 'Success',
                text: res.message,
                    timerProgressBar: true,
                    showConfirmButton: false,
                    timer: 2000,
                    background: '#28a745',
                    color: '#fff'
                });

                if (res.profile.avatar) {
                    $('#preview-avatar').attr('src', res.profile.avatar);
                }
            },
            error: err => {
                if (err.status === 422) {
                    let msg = Object.values(err.responseJSON.errors).map(m => `<div>${m}</div>`).join('');
                    Swal.fire({
                        toast: true,
                        icon: 'error',
                        position: 'top-end',
                        html: msg,
                        showConfirmButton: false,
                        timer: 3000,
                        background: '#dc3545',
                        color: '#fff'
                    });
                } else {
                    Swal.fire('Error', 'Terjadi kesalahan pada server.', 'error');
                }
            }
        });
    });
</script>
@endpush
