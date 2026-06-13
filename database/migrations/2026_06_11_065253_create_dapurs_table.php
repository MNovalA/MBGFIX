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
        Schema::create('dapurs', function (Blueprint $table) {
            $table->id('id_dapur');
            $table->string('nama_dapur');
            $table->string('lokasi')->nullable();
            $table->integer('kapasitas_porsi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dapurs');
    }
};
