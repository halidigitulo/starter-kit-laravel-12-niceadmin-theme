@extends('layouts.app')
@section('title', 'Company Profile')
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"><i class="ri-settings-line"></i> @yield('title')</h4>
                </div>
                <div class="card-body">
                    <form id="form_profile">
                        @csrf
                        <div class="row">
                            <div class="col-md-3">
                                <div class="card-body text-center">
                                    <h5 class="text-muted mt-3">Logo</h5>
                                    @if ($profile->logo)
                                        <img id="preview-logo" src="{{ asset('uploads/' . $profile->logo) }}"
                                            alt="{{ $profile->nama }}" class="rounded img-fluid mb-3"
                                            style="height: 100px; object-fit:cover;">
                                    @else
                                        <p class="text-danger text-center">Tidak ada Logo</p>
                                    @endif
                                    <!-- File input for logo -->
                                    <input type="file" id="logo-upload" class="form-control" name="logo"
                                        onchange="previewLogo(event)">

                                    <h5 class="text-muted mt-3">Icon</h5>
                                    @if ($profile->icon)
                                        <img id="preview-logo" src="{{ asset('uploads/' . $profile->icon) }}"
                                            alt="{{ $profile->nama }}" class="rounded img-fluid mb-3"
                                            style="height: 100px; object-fit:cover;">
                                    @else
                                        <p class="text-danger text-center">Tidak ada Icon</p>
                                    @endif
                                    <!-- File input for icon -->
                                    <input type="file" id="icon-upload" class="form-control" name="icon"
                                        onchange="previewIcon(event)">

                                    <h5 class="text-muted mt-3">Cover</h5>
                                    @if ($profile->cover)
                                        <img id="preview-cover" src="{{ asset('uploads/' . $profile->cover) }}"
                                            alt="{{ $profile->nama }}" class="rounded img-fluid mb-3"
                                            style="height: 100px; object-fit:cover;">
                                    @else
                                        <p class="text-danger text-center">Tidak ada cover</p>
                                    @endif
                                    <!-- File input for cover -->
                                    <input type="file" id="cover-upload" class="form-control" name="cover"
                                        onchange="previewCover(event)">
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="nama" class="form-label">Nama</label>
                                                <input type="text" class="form-control" name="nama" id="nama"
                                                    placeholder="Nama" value="{{ old('nama', $profile->nama) }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="tagline" class="form-label">Tagline</label>
                                                <input type="text" class="form-control" name="tagline" id="tagline"
                                                    placeholder="tagline" value="{{ old('tagline', $profile->tagline) }}">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="direktur" class="form-label">Direktur</label>
                                                <input type="text" class="form-control" name="direktur" id="direktur"
                                                    value="{{ old('direktur', $profile->direktur) }}"
                                                    placeholder="Direktur">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="alamat" class="form-label">Alamat</label>
                                                <input type="text" class="form-control" name="alamat" id="alamat"
                                                    value="{{ old('alamat', $profile->alamat) }}" placeholder="Alamat">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="maps" class="form-label">Maps</label>
                                                <textarea name="maps" id="" cols="30" rows="3" class="form-control" placeholder="Maps">{{ old('maps', $profile->maps) }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="telp" class="form-label">No. Telp</label>
                                                <input type="text" class="form-control" name="telp" id="telp"
                                                    value="{{ old('telp', $profile->telp) }}" placeholder="No. Telp">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="email" class="form-label">Email</label>
                                                <input type="text" class="form-control" name="email" id="email"
                                                    value="{{ old('email', $profile->email) }}" placeholder="Email">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="website" class="form-label">Website</label>
                                                <input type="text" class="form-control" name="website" id="website"
                                                    value="{{ old('website', $profile->website) }}"
                                                    placeholder="Website">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="video_url" class="form-label">URL Video</label>
                                                <input type="text" class="form-control" name="video_url"
                                                    id="video_url" value="{{ old('video_url', $profile->video_url) }}"
                                                    placeholder="URL Video Youtube">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="instagram" class="form-label">Instagram</label>
                                                <input type="text" class="form-control" name="instagram"
                                                    id="instagram" value="{{ old('instagram', $profile->instagram) }}"
                                                    placeholder="URL Instagram">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="facebook" class="form-label">Facebook</label>
                                                <input type="text" class="form-control" name="facebook"
                                                    id="facebook" value="{{ old('facebook', $profile->facebook) }}"
                                                    placeholder="URL Facebook">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="youtube" class="form-label">Youtube</label>
                                                <input type="text" class="form-control" name="youtube" id="youtube"
                                                    value="{{ old('youtube', $profile->youtube) }}"
                                                    placeholder="URL Youtube">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="tiktok" class="form-label">Tiktok</label>
                                                <input type="text" class="form-control" name="tiktok" id="tiktok"
                                                    value="{{ old('tiktok', $profile->tiktok) }}"
                                                    placeholder="URL Tiktok">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <label for="isi" class="form-label">Profile Perusahaan</label>
                                            {{-- <textarea id="isi" rows="15" name="isi">{{ $profile->isi }}</textarea> --}}
                                            <textarea name="isi" id="isi">{{ $profile->isi }}</textarea>
                                        </div>
                                        @can('profile.update')
                                            <div class="mt-3">
                                                <button class="btn btn-primary" type="submit"><i
                                                        class="ri-send-plane-line"></i>
                                                    Submit</button>
                                            </div>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    @include('plugins.summernote')
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        function previewLogo(event) {
            const preview = document.getElementById('preview-logo');
            preview.src = URL.createObjectURL(event.target.files[0]);
            preview.onload = () => URL.revokeObjectURL(preview.src); // Free up memory
        }

        function previewIcon(event) {
            const preview = document.getElementById('preview-icon');
            preview.src = URL.createObjectURL(event.target.files[0]);
            preview.onload = () => URL.revokeObjectURL(preview.src); // Free up memory
        }

        function previewCover(event) {
            const previewCover = document.getElementById('preview-cover');
            previewCover.src = URL.createObjectURL(event.target.files[0]);
            previewCover.onload = () => URL.revokeObjectURL(previewCover.src); // Free up memory
        }

        $(document).ready(function() {
            $('#form_profile').on('submit', function(e) {
                e.preventDefault();

                // jika menggunakan tinymce, Ambil isi dari tinymce dan simpan ke input tersembunyi jika perlu
                // if (tinymce.get('isi')) {
                //     $('#isi').val(tinymce.get('isi').getContent());
                // }

                // Ambil isi dari Summernote dan simpan ke input tersembunyi jika perlu
                let isiContent = $('#isi').summernote('code');
                $('#isi').val(isiContent);

                // Use FormData for file uploads
                let formData = new FormData(this);

                $.ajax({
                    type: "POST",
                    url: "{{ route('profile.update') }}",
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        Swal.fire({
                            position: "top-end",
                            icon: 'success',
                            title: 'Sukses',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1000,
                            toast: true,
                            background: '#28a745',
                            color: '#fff'
                        });

                        // Update logo preview if provided in the response
                        if (response.profile) {
                            if (response.profile.logo) {
                                $('#preview-logo').attr('src', '/uploads/' + response.profile
                                    .logo);
                            }

                            // Update text fields
                            $('#nama').val(response.profile.nama);
                            $('#tagline').val(response.profile.tagline);

                            // Update TinyMCE content for #isi
                            // if (tinymce.get('isi')) {
                            //     tinymce.get('isi').setContent(response.profile.isi);
                            // }

                            // Update isi konten Summernote
                            $('#isi').summernote('code', response.profile.isi || '');

                            // Alternatively, display the content in a separate div
                            // $('#display-isi').html(response.profile.isi);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            let errorMessage = '';
                            $.each(errors, function(key, value) {
                                errorMessage += value + '<br>';
                            });
                            Swal.fire({
                                title: 'Error!',
                                html: errorMessage,
                                icon: 'error',
                                position: 'top-end',
                                width: '400px',
                                showConfirmButton: false,
                                timer: 3000,
                                toast: true,
                                background: '#dc3545',
                                color: '#fff'
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Something went wrong. Please try again.',
                                icon: 'error',
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                toast: true,
                                background: '#dc3545',
                                color: '#fff'
                            });
                        }
                    }
                });
            });
        });
    </script>
@endpush
