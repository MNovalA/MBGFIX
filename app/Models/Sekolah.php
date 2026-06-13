<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sekolah extends Model
{
    protected $table = 'sekolahs';
    protected $primaryKey = 'id_sekolah';
    protected $fillable = ['npsn', 'nama_sekolah', 'alamat_sekolah', 'jenjang', 'jumlah_siswa'];
}