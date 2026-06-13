<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuRecipe extends Model
{
    protected $table = 'menu_recipes';
    protected $primaryKey = 'id_recipe';
    protected $fillable = ['id_menu', 'id_inventory', 'jumlah_kebutuhan'];

    public function menu() { return $this->belongsTo(Menu::class, 'id_menu', 'id_menu'); }
    public function inventory() { return $this->belongsTo(Inventory::class, 'id_inventory', 'id_inventory'); }
}