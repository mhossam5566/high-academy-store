<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Offer extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Accessor for image URL
    public function getImagePathAttribute()
    {
        return $this->image ? url('storage/' . $this->image) : null;
    }
}
