@extends('layouts.app')

@section('title', 'Kelola Menu — Kala Senja')

@section('content')
<main class="admin-page">
    <div class="admin-panel">
        <div class="admin-heading">
            <div>
                <span class="eyebrow"><span class="eyebrow-line"></span> Area pengelola</span>
                <h1>Daftar <em>menu.</em></h1>
                <p>Atur sajian yang tampil di halaman Kala Senja.</p>
            </div>
            <a class="button button-dark" href="{{ route('admin.menus.create') }}">Tambah menu <span aria-hidden="true">＋</span></a>
        </div>
        @if (session('success'))<div class="alert alert-success" role="status">{{ session('success') }}</div>@endif
        <div class="table-wrap">
            <table class="admin-table menu-table">
                <thead><tr><th>Nama menu</th><th>Kategori</th><th>Harga</th><th>Favorit</th><th>Aksi</th></tr></thead>
                <tbody>
                    @forelse ($menus as $menu)
                        <tr>
                            <td><strong>{{ $menu->nama_menu }}</strong><small>{{ $menu->deskripsi }}</small></td>
                            <td><span class="table-category">{{ $menu->kategori }}</span></td>
                            <td>Rp{{ number_format($menu->harga, 0, ',', '.') }}</td>
                            <td>{{ $menu->is_featured ? 'Ya' : '—' }}</td>
                            <td class="table-actions">
                                <a href="{{ route('admin.menus.edit', $menu) }}">Ubah</a>
                                <form action="{{ route('admin.menus.destroy', $menu) }}" method="POST" onsubmit="return confirm('Hapus menu ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="table-empty">Belum ada menu. Tambahkan menu pertama kamu.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination-wrap">{{ $menus->links() }}</div>
    </div>
</main>
@endsection
