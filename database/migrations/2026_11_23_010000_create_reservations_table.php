<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->string('email', 150);
            $table->string('telepon', 30);
            $table->date('tanggal_reservasi');
            $table->time('waktu');
            $table->unsignedTinyInteger('jumlah_orang');
            $table->text('catatan')->nullable();
            $table->string('status')->default('menunggu');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
