@extends('admin.layouts.mainLayout')

@section('title', 'Konten Statis')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin/kontenStatis.css') }}">

<div class="konten-container">
    <div style="display:flex; align-items:center; justify-content:space-between;">
        <h2>Konten Slideshow</h2>
        <button id="btn-add" class="btn-save">Tambah</button>
    </div>

    <div style="margin-top:16px">
        <table class="table" style="width:100%; border-collapse: collapse;">
            <thead>
                <tr style="background:#f7f7f7;">
                    <th style="padding:8px; text-align:left;">Preview</th>
                    <th style="padding:8px; text-align:left;">Nama</th>
                    <th style="padding:8px; text-align:left; width:160px">Aksi</th>
                </tr>
            </thead>
            <tbody id="slides-tbody">
                @foreach($slides as $s)
                    <tr data-id="{{ $s->id }}">
                        <td style="padding:8px;"><img src="{{ asset($s->gambar) }}" style="max-width:160px; height:auto; border-radius:6px"></td>
                        <td style="padding:8px;">{{ $s->judul }}</td>
                        <td style="padding:8px;">
                            <button class="btn-edit" data-id="{{ $s->id }}">Edit</button>
                            <button class="btn-delete" data-id="{{ $s->id }}">Delete</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div id="slide-modal" style="display:none; position:fixed; left:0; top:0; right:0; bottom:0; background:rgba(0,0,0,0.5); align-items:center; justify-content:center;">
    <div style="background:white; padding:20px; width:520px; border-radius:8px;">
        <h3 id="modal-title">Tambah Slide</h3>
        <input type="hidden" id="modal-id" value="">
        <div style="margin:8px 0;">
            <label>Nama Gambar</label>
            <input type="text" id="modal-judul" style="width:100%; padding:8px;" />
        </div>
        <div style="margin:8px 0;">
            <label>File Gambar</label>
            <input type="file" id="modal-gambar" />
            <div id="modal-preview" style="margin-top:8px"></div>
        </div>
        <div style="display:flex; gap:8px; justify-content:flex-end; margin-top:12px;">
            <button id="modal-cancel" class="btn-save" style="background:#999">Batal</button>
            <button id="modal-save" class="btn-save">Tambah</button>
        </div>
    </div>
</div>

<script>
    const csrfToken = '{{ csrf_token() }}';

    function openModal(mode='create', data=null){
        document.getElementById('slide-modal').style.display = 'flex';
        document.getElementById('modal-id').value = data ? data.id : '';
        document.getElementById('modal-judul').value = data ? (data.judul ?? '') : '';
        document.getElementById('modal-title').innerText = mode === 'create' ? 'Tambah Slide' : 'Edit Slide';
        document.getElementById('modal-save').innerText = mode === 'create' ? 'Tambah' : 'Simpan';
        const preview = document.getElementById('modal-preview'); preview.innerHTML = '';
        if (data && data.gambar) {
            const img = document.createElement('img'); img.src = '{{ url('') }}/' + data.gambar; img.style.maxWidth='200px'; preview.appendChild(img);
        }
        // clear file input
        document.getElementById('modal-gambar').value = '';
        document.getElementById('modal-save').dataset.mode = mode;
    }

    function closeModal(){ document.getElementById('slide-modal').style.display = 'none'; }

    document.getElementById('btn-add').addEventListener('click', ()=> openModal('create'));
    document.getElementById('modal-cancel').addEventListener('click', (e)=>{ e.preventDefault(); closeModal(); });

    document.getElementById('modal-save').addEventListener('click', async (e)=>{
        e.preventDefault();
        const mode = e.target.dataset.mode || 'create';
        const id = document.getElementById('modal-id').value;
        const judul = document.getElementById('modal-judul').value;
        const fileInput = document.getElementById('modal-gambar');
        const fd = new FormData();
        fd.append('judul', judul);
        if (fileInput.files.length>0) fd.append('gambar', fileInput.files[0]);

        let url = '';
        if (mode === 'create') url = '{{ url('/admin/konten/slides/create') }}';
        else url = '{{ url('/admin/konten/slides') }}/' + id + '/update';

        const res = await fetch(url, { method:'POST', headers:{ 'X-CSRF-TOKEN': csrfToken }, body: fd });
        const json = await res.json();
        if (json.success) {
            if (mode === 'create') {
                // prepend row
                const s = json.slide;
                const tr = document.createElement('tr'); tr.dataset.id = s.id;
                tr.innerHTML = `<td style="padding:8px;"><img src="${location.origin}/${s.gambar}" style="max-width:160px; border-radius:6px"></td><td style="padding:8px;">${s.judul||''}</td><td style="padding:8px;"><button class="btn-edit" data-id="${s.id}">Edit</button> <button class="btn-delete" data-id="${s.id}">Delete</button></td>`;
                document.getElementById('slides-tbody').prepend(tr);
            } else {
                const s = json.slide;
                const tr = document.querySelector(`tr[data-id="${s.id}"]`);
                if (tr) {
                    tr.children[0].innerHTML = `<img src="${location.origin}/${s.gambar}" style="max-width:160px; border-radius:6px">`;
                    tr.children[1].innerText = s.judul || '';
                }
            }
            attachRowHandlers();
            closeModal();
        } else {
            alert('Gagal menyimpan');
        }
    });

    function attachRowHandlers(){
        document.querySelectorAll('.btn-edit').forEach(b => {
            b.onclick = async (e)=>{
                const id = b.dataset.id;
                // fetch slide detail from DOM row if available
                const tr = document.querySelector(`tr[data-id="${id}"]`);
                const img = tr.querySelector('img');
                const judul = tr.children[1].innerText;
                const data = { id: id, judul: judul, gambar: img ? img.getAttribute('src').replace(location.origin+'/', '') : null };
                openModal('edit', data);
            };
        });

        document.querySelectorAll('.btn-delete').forEach(b => {
            b.onclick = async (e)=>{
                if (!confirm('Hapus slide ini?')) return;
                const id = b.dataset.id;
                const res = await fetch('{{ url('/admin/konten/slides') }}/' + id + '/delete', { method:'POST', headers: { 'X-CSRF-TOKEN': csrfToken } });
                const json = await res.json();
                if (json.success) {
                    const tr = document.querySelector(`tr[data-id="${id}"]`);
                    if (tr) tr.remove();
                } else alert('Gagal menghapus');
            };
        });
    }

    attachRowHandlers();
</script>
@endsection
