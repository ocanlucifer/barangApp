<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangKeluar extends Model
{
    use HasFactory;

    protected $fillable =[
        'id_barang',
        'qty_keluar',
        'tgl_keluar',
        'user_id',
    ];

    public function m_barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }

    public function m_user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
