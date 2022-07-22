<?php

namespace App\Models\Mobile;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Mobile\User;
use App\Models\Mobile\Product;

class Cart extends Model
{
    // use HasFactory;

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
