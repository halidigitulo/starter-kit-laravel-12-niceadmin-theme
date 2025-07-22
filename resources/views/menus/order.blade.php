<div class="row my-3">
    <div class="col-md-6">
        <ul id="menuList" class="list-group nested-sortable">
            @foreach ($menus as $menu)
                <li class="list-group-item" data-id="{{ $menu->id }}" data-name="{{ $menu->name }}">
                    <div class="d-flex justify-content-between">
                        <span><i class="ri-draggable"></i> {{ $menu->name }}</span>
                    </div>

                    @if ($menu->children->count())
                        <ul class="list-group nested-sortable mt-2 ms-4">
                            @foreach ($menu->children as $child)
                                <li class="list-group-item" data-id="{{ $child->id }}"
                                    data-name="{{ $child->name }}">
                                    <span><i class="ri-draggable"></i> {{ $child->name }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
        </ul>
        <h5 class="mt-3">Drag and drop to reorder</h5>
        <button class="btn btn-success mt-3" id="saveOrder">Simpan Urutan</button>
    </div>
    <div class="col-md-6">
        <h5>Preview Sidebar</h5>
        <ul id="sidebarPreview" class="list-group bg-light border p-3"></ul>
    </div>
</div>


@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

    <script>
        // Inisialisasi SortableJS nested
        document.querySelectorAll('.nested-sortable').forEach(function(el) {
            new Sortable(el, {
                group: 'nested',
                animation: 150,
                fallbackOnBody: true,
                swapThreshold: 0.65
            });
        });

        $('#saveOrder').click(function() {
            let order = [];

            // Fungsi rekursif ambil urutan dan parent_id
            function parseList($list, parentId = null) {
                $list.children('li').each(function(index) {
                    let id = $(this).data('id');
                    order.push({
                        id: id,
                        sort_order: index + 1,
                        parent_id: parentId
                    });

                    let $childUl = $(this).children('ul');
                    if ($childUl.length) {
                        parseList($childUl, id); // Panggil lagi dengan parent = id sekarang
                    }
                });
            }

            parseList($('#menuList'));

            $.ajax({
                url: "{{ route('menus.reorder') }}",
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    order: order
                },
                success: function(res) {
                    Swal.fire('Berhasil', res.message, 'success');
                },
                error: function() {
                    Swal.fire('Gagal', 'Gagal menyimpan urutan', 'error');
                }
            });
        });

        // Fungsi untuk membangun ul-li berdasarkan menuList
        function buildPreview($list, parentUl) {
            $list.children('li').each(function() {
                const name = $(this).data('name');
                const $childUl = $(this).children('ul');
                const li = $('<li>').text(name);

                if ($childUl.length) {
                    const nestedUl = $('<ul>').addClass('ms-5');
                    buildPreview($childUl, nestedUl);
                    li.append(nestedUl);
                }

                parentUl.append(li);
            });
        }

        // Panggil setiap kali drag selesai
        function updatePreview() {
            const previewUl = $('#sidebarPreview');
            previewUl.empty();
            buildPreview($('#menuList'), previewUl);
        }

        // Inisialisasi sortable dan update preview saat selesai drag
        document.querySelectorAll('.nested-sortable').forEach(function(el) {
            new Sortable(el, {
                group: 'nested',
                animation: 150,
                onEnd: function() {
                    updatePreview();
                }
            });
        });

        // Panggil saat halaman pertama kali load
        updatePreview();
    </script>
@endpush
