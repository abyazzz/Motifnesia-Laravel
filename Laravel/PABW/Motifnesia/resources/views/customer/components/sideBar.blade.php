{{-- resources/views/components/sideBar.blade.php --}}

<aside>
    {{-- 1. Filter Kategori --}}
    <h3>Filter Kategori</h3>
    
    <div style="margin-bottom: 1rem;">
        <label for="gender" style="display: block; font-size: 0.9rem; color: #555;">Gender</label>
        <select id="gender" style="width: 100%; padding: 0.5rem; border-radius: 6px; border: 1px solid #ddd; margin-top: 0.5rem; font-size: 0.9rem;">
            <option>Semua</option>
            {{-- Tambahkan opsi gender di sini --}}
        </select>
    </div>

    <div style="margin-bottom: 1rem;">
        <label for="type" style="display: block; font-size: 0.9rem; color: #555;">Jenis Lengan</label>
        <select id="type" style="width: 100%; padding: 0.5rem; border-radius: 6px; border: 1px solid #ddd; margin-top: 0.5rem; font-size: 0.9rem;">
            <option>Semua</option>
            {{-- Tambahkan opsi jenis lengan di sini --}}
        </select>
    </div>

    {{-- Tombol Filter --}}
    <button style="background-color: #5c4033; color: #fff; cursor: pointer; margin-top: 1rem; transition: 0.3s; padding: 0.5rem; border-radius: 6px; border: none; width: 100%;">
        Filter
    </button>
    
    {{-- 2. Filter Harga --}}
    <h3 style="margin-top: 2rem; margin-bottom: 0.5rem;">Filter Harga</h3>
    <div class="price-range" style="font-size: 0.9rem; color: #333;">
        {{-- List Harga (Sesuai screenshot) --}}
        <p style="margin-bottom: 0.4rem;">200.000 - 400.000</p>
        <p style="margin-bottom: 0.4rem;">400.000 - 600.000</p>
        <p style="margin-bottom: 0.4rem;">600.000 - 800.000</p>
    </div>
</aside>