<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'telepon' => ['required', 'string', 'max:30'],
            'jenis_pesanan' => ['required', Rule::in(['makan_di_tempat', 'dibungkus'])],
            'catatan' => ['nullable', 'string', 'max:1000'],
            'items' => ['required', 'array', 'min:1'],
            'items.*' => ['required', 'integer', 'between:0,20'],
        ]);

        $quantities = array_filter($validated['items'], static fn (int $quantity): bool => $quantity > 0);
        if ($quantities === []) {
            throw ValidationException::withMessages([
                'items' => 'Pilih setidaknya satu menu dengan jumlah lebih dari nol.',
            ]);
        }

        $menus = Menu::query()->whereIn('id', array_keys($quantities))->get()->keyBy('id');
        if ($menus->count() !== count($quantities)) {
            throw ValidationException::withMessages([
                'items' => 'Salah satu menu tidak tersedia. Muat ulang halaman dan pilih menu yang tersedia.',
            ]);
        }

        DB::transaction(function () use ($validated, $quantities, $menus): void {
            $total = 0;
            foreach ($quantities as $menuId => $quantity) {
                $total += $menus->get($menuId)->harga * $quantity;
            }

            $order = Order::create([
                'nama' => $validated['nama'],
                'telepon' => $validated['telepon'],
                'jenis_pesanan' => $validated['jenis_pesanan'],
                'catatan' => $validated['catatan'] ?? null,
                'total' => $total,
            ]);

            foreach ($quantities as $menuId => $quantity) {
                $menu = $menus->get($menuId);
                $order->items()->create([
                    'menu_id' => $menu->id,
                    'nama_menu' => $menu->nama_menu,
                    'harga_satuan' => $menu->harga,
                    'jumlah' => $quantity,
                    'subtotal' => $menu->harga * $quantity,
                ]);
            }
        });

        return redirect()->to(route('home').'#menu')
            ->with('success', 'Pesanan berhasil dikirim. Silakan tunggu konfirmasi dari tim kami.');
    }

    public function index(): View
    {
        $orders = Order::query()->with('items')->latest()->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    public function update(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['menunggu', 'diproses', 'selesai', 'dibatalkan'])],
        ]);

        $order->update($validated);

        return redirect()->route('admin.orders.index')->with('success', 'Status pesanan berhasil diperbarui.');
    }
}
