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
        'coo_approval',
        'status',
        'rejected_by'
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
        if (is_null($this->manager_approval)) {
            return 'Menunggu persetujuan Manager';
        }
        if ($this->manager_approval === 0) {
            return 'Ditolak Manager';
        }

        if (is_null($this->coo_approval)) {
            return 'Menunggu persetujuan Wadirum';
        }
        if ($this->coo_approval === 0) {
            return 'Ditolak Wadirum';
        }

        if (is_null($this->status)) {
            return 'Menunggu persetujuan Gudang';
        }
        if ($this->status === 0) {
            return 'Ditolak Gudang';
        }

        return 'Disetujui semua pihak';
    }
    public function canEditAmount()
    {
        return is_null($this->status)
            && is_null($this->manager_approval)
            && is_null($this->coo_approval);
    }

    public function isRejected()
    {
        return $this->status === 0
            || $this->manager_approval === 0
            || $this->coo_approval === 0;
    }
}
