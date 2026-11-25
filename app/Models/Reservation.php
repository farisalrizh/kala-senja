<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'nama',
        'email',
        'telepon',
        'tanggal_reservasi',
        'waktu',
        'jumlah_orang',
        'catatan',
        'status',
    ];

    protected $casts = [
        'tanggal_reservasi' => 'date',
    ];
}
