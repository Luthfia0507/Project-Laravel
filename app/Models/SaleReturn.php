<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Sale;
use App\Models\User;
use App\Models\SaleReturnItem;

class SaleReturn extends Model
{
    protected $fillable = [
        'sale_id',
        'user_id',
        'return_number',
        'return_date',
        'resolution',
        'reason',
        'total_amount',
    ];

    protected $casts = [
        'return_date' => 'date',
        'total_amount' => 'decimal:2',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(SaleReturnItem::class);
    }
}
