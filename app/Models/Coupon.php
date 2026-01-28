<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'price',
        'type'
    ];

    public function vouchers()
    {
        return $this->HasMany(Voucher::class);
    }

    public function getImagePathAttribute()
    {
        if ($this->image == null) {
            return null;
        } else {
            return storage_path($this->image);
        }
    }

}
