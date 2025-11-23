@extends('admin.layouts.mainLayout')

@section('title', 'Konten Statis')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin/kontenStatis.css') }}">

<div class="konten-container">
    <div class="tabs-header">
        <button class="tab-btn active" data-tab="about">About Us</button>
        <button class="tab-btn" data-tab="icon">Icon</button>
        <button class="tab-btn" data-tab="slide">Slideshow</button>
    </div>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <div class="tab-content">
        <section id="about" class="tab-pane active">
            <h2>Konten About Us</h2>
            <form method="POST" action="{{ url('/admin/konten/about') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-row">
                    <div class="col">
                        <label>Background Banner</label>
                        @if(!empty($about->background_gambar)) <img src="{{ asset($about->background_gambar) }}" class="preview"> @endif
                        <input type="file" name="background_gambar">
                    </div>
                </div>

                <div class="section-block">
                    <h4>Tentang Kami</h4>
                    @if(!empty($about->tentang_gambar)) <img src="{{ asset($about->tentang_gambar) }}" class="preview small"> @endif
                    <input type="file" name="tentang_gambar">
                    <label>Deskripsi</label>
                    <textarea name="tentang_isi">{{ old('tentang_isi', $about->tentang_isi ?? '') }}</textarea>
                </div>

                <div class="section-block">
                    <h4>Visi - Misi</h4>
                    @if(!empty($about->visi_gambar)) <img src="{{ asset($about->visi_gambar) }}" class="preview small"> @endif
                    <input type="file" name="visi_gambar">
                    <label>Deskripsi</label>
                    <textarea name="visi_isi">{{ old('visi_isi', $about->visi_isi ?? '') }}</textarea>
                </div>

                <div class="section-block">
                    <h4>Nilai - Nilai</h4>
                    @if(!empty($about->nilai_gambar)) <img src="{{ asset($about->nilai_gambar) }}" class="preview small"> @endif
                    <input type="file" name="nilai_gambar">
                    <label>Deskripsi</label>
                    <textarea name="nilai_isi">{{ old('nilai_isi', $about->nilai_isi ?? '') }}</textarea>
                </div>

                <button class="btn-save">Simpan About</button>
            </form>
        </section>

        <section id="icon" class="tab-pane">
            <h2>Konten Icon</h2>
            <form method="POST" action="{{ url('/admin/konten/icon') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-row">
                    <div class="col">
                        <label>Logo</label>
                        @php $logo = $icons->firstWhere('nama', 'logo'); @endphp
                        @if(!empty($logo) && $logo->gambar) <img src="{{ asset($logo->gambar) }}" class="preview small"> @endif
                        <input type="file" name="logo_gambar">
                    </div>
                </div>

                <div class="form-row">
                    <label>Class Icon Keranjang</label>
                    <input type="text" name="class_keranjang" value="{{ optional($icons->firstWhere('nama','class_keranjang'))->link ?? 'fa-solid fa-cart-shopping' }}">
                </div>
                <div class="form-row">
                    <label>Class Icon Favorit</label>
                    <input type="text" name="class_favorit" value="{{ optional($icons->firstWhere('nama','class_favorit'))->link ?? 'fa-regular fa-heart' }}">
                </div>
                <div class="form-row">
                    <label>Class Icon Rating</label>
                    <input type="text" name="class_rating" value="{{ optional($icons->firstWhere('nama','class_rating'))->link ?? 'fa-solid fa-star' }}">
                </div>

                <button class="btn-save">Simpan Icon</button>
            </form>
        </section>

        <section id="slide" class="tab-pane">
            <h2>Konten Slideshow</h2>
            <form method="POST" action="{{ url('/admin/konten/slideshow') }}" enctype="multipart/form-data">
                @csrf
                <div class="two-col">
                    <div>
                        <label>Nama Banner 1</label>
                        <input type="text" name="banner1_name" value="{{ optional($slides->where('urutan',1)->first())->judul ?? '' }}">
                        <label>Gambar Banner 1</label>
                        <input type="file" name="banner1_gambar">
                    </div>
                    <div>
                        <label>Nama Banner 2</label>
                        <input type="text" name="banner2_name" value="{{ optional($slides->where('urutan',2)->first())->judul ?? '' }}">
                        <label>Gambar Banner 2</label>
                        <input type="file" name="banner2_gambar">
                    </div>
                </div>

                <div class="two-col">
                    <div>
                        <label>Nama Banner 3</label>
                        <input type="text" name="banner3_name" value="{{ optional($slides->where('urutan',3)->first())->judul ?? '' }}">
                    </div>
                    <div>
                        <label>Gambar Banner 3</label>
                        <input type="file" name="banner3_gambar">
                    </div>
                </div>

                <button class="btn-save">Simpan Slideshow</button>
            </form>
        </section>
    </div>
</div>

<script>
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
            btn.classList.add('active');
            document.getElementById(btn.dataset.tab).classList.add('active');
        });
    });
</script>
@endsection
