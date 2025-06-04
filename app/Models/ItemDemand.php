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
    public function canEditAmountByLevel($level)
    {
        // $level: 1=Manager, 2=COO, 3=Admin

        if ($level == 1) {
            // Manager bisa edit jika COO & Admin belum setuju
            return is_null($this->coo_approval) && is_null($this->status);
        }
        if ($level == 2) {
            // COO bisa edit jika Admin belum setuju
            return is_null($this->status);
        }
        if ($level == 3) {
            // Admin bisa edit jika status masih null (belum approve/reject admin)
            return is_null($this->status);
        }
        return false;
    }

    public function isRejected()
    {
        return $this->status === 0
            || $this->manager_approval === 0
            || $this->coo_approval === 0;
    }
}
