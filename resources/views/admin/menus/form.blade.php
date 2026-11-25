@extends('layouts.app')

@section('content')
<main class="admin-page">
    <div class="admin-panel admin-form-panel">
        <a class="text-link back-link" href="{{ route('admin.menus.index') }}">← Kembali ke daftar menu</a>
        <span class="eyebrow"><span class="eyebrow-line"></span> Menu Kala Senja</span>
        <h1>@yield('form-heading')</h1>
        <form action="@yield('form-action')" method="POST" class="admin-form">
            @csrf
            @yield('form-method')
            <label>Nama menu
                <input type="text" name="nama_menu" value="{{ old('nama_menu', isset($menu) ? $menu->nama_menu : '') }}" required maxlength="100">
                @error('nama_menu')<small class="field-error">{{ $message }}</small>@enderror
            </label>
            <div class="form-row">
                <label>Kategori
                    <select name="kategori" required>
                        <option value="">Pilih kategori</option>
                        @foreach (['Kopi', 'Non-Kopi', 'Makanan'] as $category)
                            <option value="{{ $category }}" @selected(old('kategori', isset($menu) ? $menu->kategori : '') === $category)>{{ $category }}</option>
                        @endforeach
                    </select>
                    @error('kategori')<small class="field-error">{{ $message }}</small>@enderror
                </label>
                <label>Harga (Rp)
                    <input type="number" name="harga" value="{{ old('harga', isset($menu) ? $menu->harga : '') }}" min="1000" max="10000000" step="1000" required>
                    @error('harga')<small class="field-error">{{ $message }}</small>@enderror
                </label>
            </div>
            <label>Deskripsi
                <textarea name="deskripsi" rows="3" maxlength="1000">{{ old('deskripsi', isset($menu) ? $menu->deskripsi : '') }}</textarea>
                @error('deskripsi')<small class="field-error">{{ $message }}</small>@enderror
            </label>
            <label>URL gambar <span class="optional">(opsional)</span>
                <input type="url" name="gambar" value="{{ old('gambar', isset($menu) ? $menu->gambar : '') }}" placeholder="https://...">
                @error('gambar')<small class="field-error">{{ $message }}</small>@enderror
            </label>
            <label class="checkbox-label">
                <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', isset($menu) && $menu->is_featured))>
                Tandai sebagai favorit
            </label>
            <button class="button button-dark form-submit" type="submit">@yield('submit-label') <span aria-hidden="true">↗</span></button>
        </form>
    </div>
</main>
@endsection
