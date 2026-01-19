<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Debt extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'amount',
        'quantity',
        'description',
        'notes',
        'debt_date',
        'is_paid',
    ];

    protected $casts = [
        'debt_date' => 'datetime',
        'is_paid'   => 'boolean',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function receipts()
    {
        return $this->belongsToMany(Receipt::class, 'receipt_debt')
            ->withPivot('paid_amount')
            ->withTimestamps();
    }
}
