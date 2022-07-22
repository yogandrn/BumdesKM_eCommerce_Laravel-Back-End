<?php

namespace App\Models\Mobile;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    // use HasFactory;

    protected $table ='galleries';

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
