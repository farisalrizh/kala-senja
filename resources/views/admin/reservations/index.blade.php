@extends('layouts.app')

@section('title', 'Reservasi — Kala Senja')

@section('content')
<main class="admin-page">
    <div class="admin-panel">
        <div class="admin-heading">
            <div>
                <span class="eyebrow"><span class="eyebrow-line"></span> Area pengelola</span>
                <h1>Daftar <em>reservasi.</em></h1>
                <p>Lihat permintaan meja dan perbarui status konfirmasinya.</p>
            </div>
        </div>
        @if (session('success'))<div class="alert alert-success" role="status">{{ session('success') }}</div>@endif
        <div class="table-wrap">
            <table class="admin-table reservation-table">
                <thead><tr><th>Tamu</th><th>Jadwal</th><th>Jumlah</th><th>Catatan</th><th>Status</th></tr></thead>
                <tbody>
                    @forelse ($reservations as $reservation)
                        <tr>
                            <td><strong>{{ $reservation->nama }}</strong><small><a href="mailto:{{ $reservation->email }}">{{ $reservation->email }}</a><br><a href="tel:{{ $reservation->telepon }}">{{ $reservation->telepon }}</a></small></td>
                            <td>{{ $reservation->tanggal_reservasi->format('d M Y') }}<small>{{ substr($reservation->waktu, 0, 5) }}</small></td>
                            <td>{{ $reservation->jumlah_orang }} orang</td>
                            <td>{{ $reservation->catatan ?: '—' }}</td>
                            <td>
                                <form action="{{ route('admin.reservations.update', $reservation) }}" method="POST" class="status-form">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" aria-label="Status reservasi {{ $reservation->nama }}">
                                        @foreach (['menunggu' => 'Menunggu', 'dikonfirmasi' => 'Dikonfirmasi', 'selesai' => 'Selesai', 'dibatalkan' => 'Dibatalkan'] as $value => $label)
                                            <option value="{{ $value }}" @selected($reservation->status === $value)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit">Simpan</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="table-empty">Belum ada permintaan reservasi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination-wrap">{{ $reservations->links() }}</div>
    </div>
</main>
@endsection
