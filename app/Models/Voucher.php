<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable= [
        'coupon_id',
        'code',
        'image',
        'is_used',
        'user_id'
        ];

    public function coupon(){
        return $this->belongsTo(Coupon::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
     public function getImagePathAttribute()
{
   if($this->image == null){
       return null;
   }else{
       return url('storage/'.$this->image);
   }
}
}
