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
        Schema::create('menu_recipes', function (Blueprint $table) {
            $table->id('id_recipe');
            $table->foreignId('id_menu')->constrained('menus', 'id_menu');
            $table->foreignId('id_inventory')->constrained('inventories', 'id_inventory');
            $table->float('jumlah_kebutuhan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_recipes');
    }
};
