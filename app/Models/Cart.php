<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Product;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'carts';
    protected $guarded = ['id'];
    protected $with = [ 'product'];

    public function user()
    {
        # code...
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        # code...
        return $this->belongsTo(Product::class, 'id_product','id');
    }

}
