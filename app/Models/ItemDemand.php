<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemDemand extends Model
{
    protected $table = 'item_demands';

    protected $fillable = [
        'user_id',
        'stationery_id',
        'amount',
        'dos',
        'manager_approval',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function stationery()
    {
        return $this->belongsTo(Stationery::class, 'stationery_id');
    }

    public function getTotalHargaFormattedAttribute()
    {
        $harga = $this->stationery ? $this->stationery->harga_barang : 0;
        $total = $harga * $this->amount;

        return 'Rp ' . number_format($total, 0, ',', '.');
    }

    // App\Models\ItemDemand.php

    public function getProgressPersetujuanAttribute()
    {
        if ($this->manager_approval === 0) {
            return 'Menunggu persetujuan Manager';
        }

        if ($this->coo_approval === 0) {
            return 'Menunggu persetujuan Wadirum';
        }

        if ($this->status === 0) {
            return 'Menunggu persetujuan Gudang';
        }

        return 'Disetujui semua pihak';
    }

}
