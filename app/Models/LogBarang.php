<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogBarang extends Model
{
    use HasFactory;

    protected $connection = 'pgsql';

    protected $fillable =[
        'id_barang',
        'kode_barang',
        'nama_barang',
        'qty_awal',
        'qty_masuk',
        'qty_keluar',
        'qty_akhir',
        'aktifitas',
        'satuan',
        'user_id',
        'username',
    ];
}
