<?php

namespace Tests\Feature;

use App\Models\Menu;
use App\Models\Order;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CafeWebsiteTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_displays_database_menus(): void
    {
        Menu::create([
            'nama_menu' => 'Senja Latte',
            'kategori' => 'Kopi',
            'harga' => 32000,
            'deskripsi' => 'Kopi susu gula aren.',
            'is_featured' => true,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Kala Senja')
            ->assertSee('Senja Latte')
            ->assertSee('Rp32.000')
            ->assertSee('Pesan sekarang')
            ->assertSee('Kode QRIS Kala Senja')
            ->assertSee('Kirim pesanan');
    }

    public function test_guest_can_create_a_reservation(): void
    {
        $response = $this->post('/reservasi', [
            'nama' => 'Nadia Putri',
            'email' => 'nadia@example.com',
            'telepon' => '+628123456789',
            'tanggal_reservasi' => now()->addDay()->toDateString(),
            'waktu' => '18:00',
            'jumlah_orang' => 2,
            'catatan' => 'Meja dekat jendela jika tersedia.',
        ]);

        $response->assertRedirect(route('home').'#reservasi');
        $this->assertDatabaseHas('reservations', [
            'nama' => 'Nadia Putri',
            'email' => 'nadia@example.com',
            'jumlah_orang' => 2,
            'status' => 'menunggu',
        ]);
    }

    public function test_guest_can_order_menu_items(): void
    {
        $menu = Menu::create([
            'nama_menu' => 'Senja Latte',
            'kategori' => 'Kopi',
            'harga' => 32000,
        ]);

        $this->post(route('orders.store'), [
            'nama' => 'Nadia Putri',
            'telepon' => '+628123456789',
            'jenis_pesanan' => 'dibungkus',
            'items' => [$menu->id => 2],
        ])->assertRedirect(route('home').'#menu')
            ->assertSessionHas('success');

        $order = Order::with('items')->firstOrFail();
        $this->assertSame(64000, $order->total);
        $this->assertDatabaseHas('orders', [
            'nama' => 'Nadia Putri',
            'jenis_pesanan' => 'dibungkus',
            'total' => 64000,
            'status' => 'menunggu',
        ]);
        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'menu_id' => $menu->id,
            'nama_menu' => 'Senja Latte',
            'jumlah' => 2,
            'subtotal' => 64000,
        ]);
    }

    public function test_order_requires_at_least_one_selected_menu(): void
    {
        $menu = Menu::create([
            'nama_menu' => 'Senja Latte',
            'kategori' => 'Kopi',
            'harga' => 32000,
        ]);

        $this->from('/')->post(route('orders.store'), [
            'nama' => 'Nadia Putri',
            'telepon' => '+628123456789',
            'jenis_pesanan' => 'makan_di_tempat',
            'items' => [$menu->id => 0],
        ])->assertSessionHasErrors('items');

        $this->assertDatabaseCount('orders', 0);
    }

    public function test_reservation_rejects_a_past_date(): void
    {
        $this->from('/')->post('/reservasi', [
            'nama' => 'Nadia Putri',
            'email' => 'nadia@example.com',
            'telepon' => '+628123456789',
            'tanggal_reservasi' => now()->subDay()->toDateString(),
            'waktu' => '18:00',
            'jumlah_orang' => 2,
        ])->assertSessionHasErrors('tanggal_reservasi');

        $this->assertDatabaseCount('reservations', 0);
    }

    public function test_reservation_rejects_a_time_outside_opening_hours(): void
    {
        $this->from('/')->post('/reservasi', [
            'nama' => 'Nadia Putri',
            'email' => 'nadia@example.com',
            'telepon' => '+628123456789',
            'tanggal_reservasi' => now()->addDay()->toDateString(),
            'waktu' => '23:00',
            'jumlah_orang' => 2,
        ])->assertSessionHasErrors('waktu');

        $this->assertDatabaseCount('reservations', 0);
    }

    public function test_admin_pages_require_authentication(): void
    {
        $this->get('/admin/menus')->assertRedirect(route('admin.login'));
        $this->get('/admin/reservations')->assertRedirect(route('admin.login'));
        $this->get('/admin/orders')->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_manage_menus_and_reservation_status(): void
    {
        $admin = User::create([
            'name' => 'Kala Admin',
            'email' => 'admin@example.com',
            'password' => 'safe-test-password',
        ]);
        $reservation = Reservation::create([
            'nama' => 'Nadia Putri',
            'email' => 'nadia@example.com',
            'telepon' => '+628123456789',
            'tanggal_reservasi' => now()->addDay()->toDateString(),
            'waktu' => '18:00',
            'jumlah_orang' => 2,
        ]);
        $menu = Menu::create([
            'nama_menu' => 'Kopi Pagi',
            'kategori' => 'Kopi',
            'harga' => 29000,
        ]);
        $order = Order::create([
            'nama' => 'Nadia Putri',
            'telepon' => '+628123456789',
            'jenis_pesanan' => 'dibungkus',
            'total' => 29000,
        ]);
        $order->items()->create([
            'menu_id' => $menu->id,
            'nama_menu' => $menu->nama_menu,
            'harga_satuan' => $menu->harga,
            'jumlah' => 1,
            'subtotal' => $menu->harga,
        ]);

        $this->actingAs($admin)
            ->get('/admin/menus')
            ->assertOk()
            ->assertSee('Daftar');

        $this->get(route('admin.menus.create'))->assertOk()->assertSee('Tambah menu baru');
        $this->get(route('admin.reservations.index'))
            ->assertOk()
            ->assertSee('perbarui status konfirmasinya');
        $this->get(route('admin.orders.index'))
            ->assertOk()
            ->assertSee('Daftar')
            ->assertSee('Kopi Pagi');

        $this->actingAs($admin)
            ->get(route('admin.menus.create'))
            ->assertOk();

        $this->post('/admin/menus', [
            'nama_menu' => 'Es Kopi Senja',
            'kategori' => 'Kopi',
            'harga' => 29000,
            'deskripsi' => 'Kopi dingin.',
            'is_featured' => '1',
        ])
            ->assertRedirect(route('admin.menus.index'));

        $this->assertDatabaseHas('menus', ['nama_menu' => 'Es Kopi Senja', 'is_featured' => 1]);

        $menu = Menu::where('nama_menu', 'Es Kopi Senja')->firstOrFail();
        $this->get(route('admin.menus.edit', $menu))->assertOk()->assertSee('Ubah menu');

        $this->put(route('admin.menus.update', $menu), [
            'nama_menu' => 'Es Kopi Kala',
            'kategori' => 'Kopi',
            'harga' => 30000,
            'deskripsi' => 'Kopi susu dingin.',
        ])->assertRedirect(route('admin.menus.index'));

        $this->assertDatabaseHas('menus', ['id' => $menu->id, 'nama_menu' => 'Es Kopi Kala']);

        $this->delete(route('admin.menus.destroy', $menu))->assertRedirect(route('admin.menus.index'));
        $this->assertDatabaseMissing('menus', ['id' => $menu->id]);

        $this->patch(route('admin.reservations.update', $reservation), ['status' => 'dikonfirmasi'])
            ->assertRedirect(route('admin.reservations.index'));

        $this->assertDatabaseHas('reservations', ['id' => $reservation->id, 'status' => 'dikonfirmasi']);

        $this->patch(route('admin.orders.update', $order), ['status' => 'diproses'])
            ->assertRedirect(route('admin.orders.index'));

        $this->assertDatabaseHas('orders', ['id' => $order->id, 'status' => 'diproses']);
    }

    public function test_admin_can_sign_in(): void
    {
        User::create([
            'name' => 'Kala Admin',
            'email' => 'admin@example.com',
            'password' => 'safe-test-password',
        ]);

        $this->get(route('admin.login'))
            ->assertOk()
            ->assertSee('Area khusus tim')
            ->assertDontSee('Keluar');

        $this->post(route('admin.login.store'), [
            'email' => 'admin@example.com',
            'password' => 'safe-test-password',
        ])->assertRedirect(route('admin.menus.index'));

        $this->assertAuthenticated();
    }
}
