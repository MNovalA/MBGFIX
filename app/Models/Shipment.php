<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    protected $table = 'shipments';
    protected $primaryKey = 'id_shipment';
    protected $fillable = ['id_sekolah', 'id_dapur', 'id_menu', 'jumlah_porsi', 'status_kirim', 'waktu_sampai'];

    public function sekolah() { return $this->belongsTo(Sekolah::class, 'id_sekolah', 'id_sekolah'); }
    public function dapur() { return $this->belongsTo(Dapur::class, 'id_dapur', 'id_dapur'); }
    public function menu() { return $this->belongsTo(Menu::class, 'id_menu', 'id_menu'); }
}