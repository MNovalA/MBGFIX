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
        Schema::create('sekolahs', function (Blueprint $table) {
            $table->id('id_sekolah');
            $table->string('npsn')->unique();
            $table->string('nama_sekolah');
            $table->text('alamat_sekolah')->nullable();
            $table->enum('jenjang', ['SD', 'SMP', 'SMA']);
            $table->integer('jumlah_siswa')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sekolahs');
    }
};
