<?php

namespace App\Models\Mobile;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // use HasFactory;

    protected $guarded = ['id'];
    protected $with =[ 'gallery'];

    public function cart()
    {
        return $this->belongsTo(Cart::class, 'id_product');
    }
    
    public function gallery()
    {
        return $this->hasMany(Gallery::class, 'id_product');
    }
}
