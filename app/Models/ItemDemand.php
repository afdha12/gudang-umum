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
        'rejected_by',
        'manager_approved_at',
        'coo_approved_at',
        'admin_approved_at',
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

    /**
     * Get rejection status dengan detail siapa yang reject
     */
    public function getRejectionInfo()
    {
        if ($this->status === 0) {
            return ['rejected' => true, 'by' => 'Admin/Gudang'];
        }
        if ($this->manager_approval === 0) {
            return ['rejected' => true, 'by' => 'Manager'];
        }
        if ($this->coo_approval === 0) {
            return ['rejected' => true, 'by' => 'Wadirum'];
        }

        return ['rejected' => false, 'by' => null];
    }

    public function canEditAmountByLevel($level)
    {
        // $level: 1=Manager, 2=COO, 3=Admin

        if ($level == 1) {
            // User bisa edit jika Manager, COO, dan Admin belum setuju
            return is_null($this->manager_approval) && is_null($this->coo_approval) && is_null($this->status);
        }
        if ($level == 2) {
            // Manager bisa edit jika COO & Admin belum setuju
            return is_null($this->coo_approval) && is_null($this->status);
        }
        if ($level == 3) {
            // COO bisa edit jika Admin belum setuju
            return is_null($this->status);
        }
        if ($level == 4) {
            // Admin bisa edit jika status masih null (belum approve/reject admin)
            return is_null($this->status);
        }
        return false;
    }

    public function isRejected()
    {
        // Debug log
        \Log::debug("Checking rejection status for item {$this->id}", [
            'manager_approval' => $this->manager_approval,
            'coo_approval' => $this->coo_approval,
            'status' => $this->status,
            'old_is_rejected' => request()->old("is_rejected.{$this->id}"),
            'old_status' => request()->old("status.{$this->id}")
        ]);

        // Cek status reject dari database
        $dbRejected = $this->status === 0 || 
                      $this->manager_approval === 0 || 
                      $this->coo_approval === 0;

        // Cek status reject dari form submission
        $formRejected = request()->old("is_rejected.{$this->id}") === '1' || 
                        request()->old("status.{$this->id}") === '0';

        return $dbRejected || $formRejected;
    }

    /**
     * Cek apakah item sudah disetujui penuh
     */
    public function isFullyApproved()
    {
        return $this->status === 1 &&
            $this->manager_approval === 1 &&
            $this->coo_approval === 1;
    }

    /**
     * Get status display untuk UI
     */
    public function getStatusDisplay()
    {
        // Cek rejection terlebih dahulu (prioritas tertinggi)
        if ($this->manager_approval === 0) {
            return [
                'class' => 'bg-red-600',
                'text' => 'Ditolak oleh Manager',
                'rejected' => true
            ];
        }

        if ($this->coo_approval === 0) {
            return [
                'class' => 'bg-red-600',
                'text' => 'Ditolak oleh Wadirum',
                'rejected' => true
            ];
        }

        if ($this->status === 0) {
            return [
                'class' => 'bg-red-600',
                'text' => 'Ditolak oleh Gudang',
                'rejected' => true
            ];
        }

        // Cek approval positif
        if ($this->status === 1) {
            return [
                'class' => 'bg-green-600',
                'text' => 'Disetujui Semua Pihak',
                'rejected' => false
            ];
        }

        if ($this->coo_approval === 1) {
            return [
                'class' => 'bg-blue-600',
                'text' => 'Disetujui Wadirum',
                'rejected' => false
            ];
        }

        if ($this->manager_approval === 1) {
            return [
                'class' => 'bg-yellow-600',
                'text' => 'Disetujui Manager',
                'rejected' => false
            ];
        }

        // Default: belum diproses
        return [
            'class' => 'bg-gray-600',
            'text' => 'Menunggu Persetujuan',
            'rejected' => false
        ];
    }

    /**
     * Cek apakah item bisa diproses oleh role tertentu
     */
    public function canBeProcessedBy($role)
    {
        // Jika sudah direject, tidak bisa diproses lagi
        if ($this->isRejected()) {
            return false;
        }

        // Jika sudah fully approved, tidak bisa diproses lagi
        if ($this->isFullyApproved()) {
            return false;
        }

        switch ($role) {
            case 'manager':
                // Manager bisa proses jika belum ada keputusan manager
                return is_null($this->manager_approval);

            case 'coo':
                // COO bisa proses jika manager sudah approve dan COO belum ada keputusan
                return $this->manager_approval === 1 && is_null($this->coo_approval);

            case 'admin':
                // Admin bisa proses jika manager dan COO sudah approve, admin belum ada keputusan
                return $this->manager_approval === 1 &&
                    $this->coo_approval === 1 &&
                    is_null($this->status);

            default:
                return false;
        }
    }

    /**
     * Scope untuk filter item yang tidak direject
     */
    public function scopeNotRejected($query)
    {
        return $query->where(function ($q) {
            $q->where(function ($subQ) {
                // Tidak direject sama sekali
                $subQ->where('status', '!=', 0)
                    ->orWhereNull('status');
            })->where(function ($subQ) {
                $subQ->where('manager_approval', '!=', 0)
                    ->orWhereNull('manager_approval');
            })->where(function ($subQ) {
                $subQ->where('coo_approval', '!=', 0)
                    ->orWhereNull('coo_approval');
            });
        });
    }

    /**
     * Get progress percentage untuk UI
     */
    public function getProgressPercentage()
    {
        if ($this->isRejected()) {
            return 0;
        }

        $progress = 0;
        if ($this->manager_approval === 1)
            $progress += 33.33;
        if ($this->coo_approval === 1)
            $progress += 33.33;
        if ($this->status === 1)
            $progress += 33.34;

        return $progress;
    }

    public function getApprovalStatus()
    {
        return [
            'manager_approval' => $this->manager_approval,
            'coo_approval' => $this->coo_approval,
            'status' => $this->status,
            'is_rejected' => $this->isRejected(),
            'rejected_by' => $this->rejected_by
        ];
    }
}
