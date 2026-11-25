<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MenuController extends Controller
{
    public function index(): View
    {
        $menus = Menu::query()->orderBy('kategori')->orderBy('nama_menu')->paginate(12);

        return view('admin.menus.index', compact('menus'));
    }

    public function create(): View
    {
        return view('admin.menus.create');
    }

    public function store(Request $request): RedirectResponse
    {
        Menu::create($this->validatedData($request));

        return redirect()->route('admin.menus.index')->with('success', 'Menu berhasil ditambahkan.');
    }

    public function edit(Menu $menu): View
    {
        return view('admin.menus.edit', compact('menu'));
    }

    public function update(Request $request, Menu $menu): RedirectResponse
    {
        $menu->update($this->validatedData($request, $menu));

        return redirect()->route('admin.menus.index')->with('success', 'Menu berhasil diperbarui.');
    }

    public function destroy(Menu $menu): RedirectResponse
    {
        $menu->delete();

        return redirect()->route('admin.menus.index')->with('success', 'Menu berhasil dihapus.');
    }

    private function validatedData(Request $request, ?Menu $menu = null): array
    {
        return $request->validate([
            'nama_menu' => ['required', 'string', 'max:100', Rule::unique('menus', 'nama_menu')->ignore($menu?->id)],
            'kategori' => ['required', Rule::in(['Kopi', 'Non-Kopi', 'Makanan'])],
            'harga' => ['required', 'integer', 'min:1000', 'max:10000000'],
            'deskripsi' => ['nullable', 'string', 'max:1000'],
            'gambar' => ['nullable', 'url', 'max:2048'],
            'is_featured' => ['nullable', 'boolean'],
        ]) + ['is_featured' => $request->boolean('is_featured')];
    }
}
