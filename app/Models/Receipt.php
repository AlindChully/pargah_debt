<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Receipt extends Model
{
    protected $fillable = [
        'customer_id',
        'amount',
        'receipt_date',
        'notes'
    ];

    protected $casts = [
        'receipt_date' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function debts(): BelongsToMany
    {
        return $this->belongsToMany(Debt::class, 'receipt_debt')
            ->withPivot('paid_amount')
            ->withTimestamps();
    }
}
