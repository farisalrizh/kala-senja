<?php

namespace App\Http\Controllers;

use App\Models\Menu;

class HomeController extends Controller
{
    public function index()
    {
        $menus = Menu::query()
            ->orderBy('nama_menu')
            ->get();

        $menuCategories = Menu::query()
            ->select('kategori')
            ->distinct()
            ->orderBy('kategori')
            ->pluck('kategori');

        return view('home', compact('menus', 'menuCategories'));
    }
}
