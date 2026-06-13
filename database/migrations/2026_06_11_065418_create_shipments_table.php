<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up() {
        Schema::create('shipments', function (Blueprint $table) {
            $table->id('id_shipment');
            $table->foreignId('id_sekolah')->constrained('sekolahs', 'id_sekolah');
            $table->foreignId('id_dapur')->constrained('dapurs', 'id_dapur');
            $table->foreignId('id_menu')->constrained('menus', 'id_menu');
            $table->integer('jumlah_porsi')->default(0);
            $table->enum('status_kirim', ['Persiapan', 'Memasak', 'Perjalanan', 'Diterima', 'Gagal'])->default('Persiapan');
            $table->dateTime('waktu_sampai')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
