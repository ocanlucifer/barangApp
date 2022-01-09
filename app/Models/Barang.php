<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $fillable =[
        'kode_barang',
        'nama_barang',
        'stock',
        'satuan',
        'status',
        'created_user_id',
        'updated_user_id'
    ];

    public function b_masuk()
    {
        return $this->hasMany('App\Models\BarangMasuk', 'id_barang', 'id');
    }

    public function b_keluar()
    {
        return $this->hasMany('App\Models\BarangKeluar', 'id_barang', 'id');
    }

    public function m_user_create()
    {
        return $this->belongsTo(User::class, 'created_user_id');
    }

    public function m_user_update()
    {
        return $this->belongsTo(User::class, 'updated_user_id');
    }
}
