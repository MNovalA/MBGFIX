<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menus';
    protected $primaryKey = 'id_menu';
    protected $fillable = ['id_dapur', 'nama_paket', 'deskripsi'];

    // Relasi ke Dapur
    public function dapur() { 
        return $this->belongsTo(Dapur::class, 'id_dapur', 'id_dapur'); 
    }

    // SINKRONKAN DISINI: Ubah nama fungsi dari MenuRecipe menjadi recipes
    public function recipes() { 
        return $this->hasMany(MenuRecipe::class, 'id_menu', 'id_menu'); 
    }
}