<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $menus = [
            ['nama_menu' => 'Senja Latte', 'kategori' => 'Kopi', 'harga' => 32000, 'deskripsi' => 'Espresso, susu lembut, dan sentuhan gula aren buatan sendiri.', 'gambar' => 'https://images.unsplash.com/photo-1461023058943-07fcbe16d735?auto=format&fit=crop&w=900&q=85', 'is_featured' => true],
            ['nama_menu' => 'Kopi Kala', 'kategori' => 'Kopi', 'harga' => 28000, 'deskripsi' => 'Kopi susu khas kami dengan rasa yang seimbang dan berkesan.', 'gambar' => 'https://images.unsplash.com/photo-1517701604599-bb29b565090c?auto=format&fit=crop&w=900&q=85', 'is_featured' => true],
            ['nama_menu' => 'Filter Nusantara', 'kategori' => 'Kopi', 'harga' => 30000, 'deskripsi' => 'Seduhan manual dari biji pilihan petani lokal, berganti setiap musim.', 'gambar' => 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=crop&w=900&q=85', 'is_featured' => true],
            ['nama_menu' => 'Es Cokelat Senja', 'kategori' => 'Non-Kopi', 'harga' => 30000, 'deskripsi' => 'Cokelat premium yang pekat, creamy, dan menyenangkan.', 'gambar' => 'https://images.unsplash.com/photo-1542990253-0b8be9f4ecb1?auto=format&fit=crop&w=900&q=85', 'is_featured' => true],
            ['nama_menu' => 'Matcha Cloud', 'kategori' => 'Non-Kopi', 'harga' => 34000, 'deskripsi' => 'Matcha Jepang dengan susu segar dan lapisan foam yang lembut.', 'gambar' => 'https://images.unsplash.com/photo-1515823064-d6e0c04616a7?auto=format&fit=crop&w=900&q=85', 'is_featured' => false],
            ['nama_menu' => 'Roti Panggang Kaya', 'kategori' => 'Makanan', 'harga' => 26000, 'deskripsi' => 'Roti panggang renyah dengan selai kaya dan mentega gurih.', 'gambar' => 'https://images.unsplash.com/photo-1525351484163-7529414344d8?auto=format&fit=crop&w=900&q=85', 'is_featured' => true],
            ['nama_menu' => 'Pisang Goreng Madu', 'kategori' => 'Makanan', 'harga' => 28000, 'deskripsi' => 'Pisang pilihan berbalut madu, disajikan hangat dan renyah.', 'gambar' => 'https://images.unsplash.com/photo-1587132137056-bfbf0166836e?auto=format&fit=crop&w=900&q=85', 'is_featured' => false],
            ['nama_menu' => 'Kue Cokelat', 'kategori' => 'Makanan', 'harga' => 35000, 'deskripsi' => 'Kue cokelat lembut untuk menemani obrolan lebih lama.', 'gambar' => 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?auto=format&fit=crop&w=900&q=85', 'is_featured' => false],
        ];

        foreach ($menus as $menu) {
            Menu::updateOrCreate(['nama_menu' => $menu['nama_menu']], $menu);
        }

        if (config('admin.email') && config('admin.password')) {
            User::updateOrCreate(
                ['email' => config('admin.email')],
                [
                    'name' => config('admin.name'),
                    'password' => config('admin.password'),
                ]
            );
        }
    }
}
