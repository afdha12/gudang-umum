<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stationery extends Model
{
    // Tentukan nama tabel jika tidak mengikuti konvensi penamaan Laravel
    protected $table = 'stationeries';

    // Tentukan atribut yang dapat diisi secara massal
    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'harga_barang',
        'jenis_barang',
        'satuan',
        'masuk',
        'keluar',
        'stok',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($stationery) {
            $lastItem = self::latest('id')->first(); // Ambil kode terakhir
            $nextKode = self::generateNextKode($lastItem ? $lastItem->kode_barang : null);

            $stationery->kode_barang = $nextKode;
        });
    }

    public static function generateNextKode($lastKode)
    {
        if (!$lastKode) {
            return 'AA-001'; // Kode pertama jika tabel masih kosong
        }

        [$prefix, $number] = explode('-', $lastKode);
        $nextNumber = (int) $number + 1;

        if ($nextNumber > 999) {
            $prefix = self::nextPrefix($prefix); // Naik ke huruf berikutnya (AA -> AB)
            $nextNumber = 1;
        }

        return $prefix . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }

    private static function nextPrefix($prefix)
    {
        $letters = str_split($prefix);
        if ($letters[1] === 'Z') {
            $letters[1] = 'A';
            $letters[0] = chr(ord($letters[0]) + 1);
        } else {
            $letters[1] = chr(ord($letters[1]) + 1);
        }
        return implode('', $letters);
    }

    public function getFormattedHargaAttribute()
    {
        return 'Rp ' . number_format($this->harga_barang, 0, ',', '.');
    }

}
