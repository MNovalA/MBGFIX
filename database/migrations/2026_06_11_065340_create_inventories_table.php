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
        Schema::create('inventories', function (Blueprint $table) {
            $table->id('id_inventory');
            $table->foreignId('id_dapur')->constrained('dapurs', 'id_dapur');
            $table->string('nama_bahan');
            $table->decimal('stok', 10, 2)->default(0.00);
            $table->string('satuan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
