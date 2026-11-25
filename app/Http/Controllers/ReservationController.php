<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ReservationController extends Controller
{
    public function index(): View
    {
        $reservations = Reservation::query()->latest('tanggal_reservasi')->latest('waktu')->paginate(15);

        return view('admin.reservations.index', compact('reservations'));
    }

    public function update(Request $request, Reservation $reservation): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['menunggu', 'dikonfirmasi', 'selesai', 'dibatalkan'])],
        ]);

        $reservation->update($validated);

        return redirect()->route('admin.reservations.index')->with('success', 'Status reservasi berhasil diperbarui.');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150'],
            'telepon' => ['required', 'string', 'max:30'],
            'tanggal_reservasi' => ['required', 'date', 'after_or_equal:today'],
            'waktu' => [
                'required',
                'date_format:H:i',
                Rule::in(array_map(static fn (int $hour): string => sprintf('%02d:00', $hour), range(8, 21))),
            ],
            'jumlah_orang' => ['required', 'integer', 'between:1,20'],
            'catatan' => ['nullable', 'string', 'max:1000'],
        ]);

        Reservation::create($validated);

        return redirect()->to(route('home').'#reservasi')
            ->with('success', 'Permintaan reservasi terkirim. Tim kami akan segera menghubungi Anda untuk konfirmasi.');
    }
}
