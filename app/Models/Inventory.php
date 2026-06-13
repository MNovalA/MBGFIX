<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $table = 'inventories';
    protected $primaryKey = 'id_inventory';
    protected $fillable = ['id_dapur', 'nama_bahan', 'stok', 'satuan'];

    public function dapur() { return $this->belongsTo(Dapur::class, 'id_dapur', 'id_dapur'); }
}
