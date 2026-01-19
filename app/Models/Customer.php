<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
    ];

    public function debts()
    {
        return $this->hasMany(Debt::class);
    }

    public function receipts()
    {
        return $this->hasMany(Receipt::class);
    }

    public function balance()
    {
        return $this->hasOne(CustomerBalance::class);
    }

    public function getFormattedPhoneAttribute()
    {
        if (strlen($this->phone) === 11) {
            return preg_replace('/(\d{4})(\d{3})(\d{4})/', '$1 $2 $3', $this->phone);
        }

        return $this->phone;
    }
}
