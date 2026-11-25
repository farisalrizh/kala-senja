@extends('layouts.app')

@section('title', 'Pesanan — Kala Senja')

@section('content')
<main class="admin-page">
    <div class="admin-panel">
        <div class="admin-heading">
            <div>
                <span class="eyebrow"><span class="eyebrow-line"></span> Area pengelola</span>
                <h1>Daftar <em>pesanan.</em></h1>
                <p>Lihat detail pesanan dan perbarui statusnya.</p>
            </div>
        </div>
        @if (session('success'))<div class="alert alert-success" role="status">{{ session('success') }}</div>@endif
        <div class="table-wrap">
            <table class="admin-table order-table">
                <thead><tr><th>Pelanggan</th><th>Pesanan</th><th>Penyajian</th><th>Total</th><th>Status</th></tr></thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr>
                            <td><strong>{{ $order->nama }}</strong><small><a href="tel:{{ $order->telepon }}">{{ $order->telepon }}</a>@if ($order->catatan)<br>{{ $order->catatan }}@endif</small></td>
                            <td>
                                @foreach ($order->items as $item)
                                    <small>{{ $item->nama_menu }} × {{ $item->jumlah }} — Rp{{ number_format($item->subtotal, 0, ',', '.') }}</small>
                                @endforeach
                            </td>
                            <td>{{ $order->jenis_pesanan === 'dibungkus' ? 'Dibungkus' : 'Makan di tempat' }}</td>
                            <td>Rp{{ number_format($order->total, 0, ',', '.') }}</td>
                            <td>
                                <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="status-form">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" aria-label="Status pesanan {{ $order->nama }}">
                                        @foreach (['menunggu' => 'Menunggu', 'diproses' => 'Diproses', 'selesai' => 'Selesai', 'dibatalkan' => 'Dibatalkan'] as $value => $label)
                                            <option value="{{ $value }}" @selected($order->status === $value)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit">Simpan</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="table-empty">Belum ada pesanan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination-wrap">{{ $orders->links() }}</div>
    </div>
</main>
@endsection
