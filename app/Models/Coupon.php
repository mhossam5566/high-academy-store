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
        'type',
        'brand_id'
    ];

    protected $appends = [
        'image_path'
    ];

    public function vouchers()
    {
        return $this->HasMany(Voucher::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function getImagePathAttribute()
    {
        if ($this->image == null) {
            return null;
        } else {
            return url('storage/' . $this->image);
        }
    }

}
