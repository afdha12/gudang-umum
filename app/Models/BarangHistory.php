<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BarangHistory extends Model
{
    use HasFactory;

    protected $table = 'barang_history'; // Nama tabel

    protected $fillable = [
        'stationery_id',
        'jenis',
        'jumlah',
        'tanggal',
    ];

    // Relasi ke tabel stationery
    public function stationery()
    {
        return $this->belongsTo(Stationery::class);
    }
}
