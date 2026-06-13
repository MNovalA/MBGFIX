<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dapur extends Model
{
    protected $table = 'dapurs';
    protected $primaryKey = 'id_dapur';
    protected $fillable = ['nama_dapur', 'lokasi', 'kapasitas_porsi'];

    public function inventories() { return $this->hasMany(Inventory::class, 'id_dapur', 'id_dapur'); }
    public function menus() { return $this->hasMany(Menu::class, 'id_dapur', 'id_dapur'); }
}