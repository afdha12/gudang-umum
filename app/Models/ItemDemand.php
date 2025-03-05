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
}
