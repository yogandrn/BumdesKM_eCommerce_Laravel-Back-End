<?php

namespace App\Models\Mobile;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Mobile\Product;

class TransactionDetail extends Model
{
    // use HasFactory;
    
    protected $table = 'transaction_details';

    protected $guarded = ['id'];
    protected $with = ['product'];

    public function transaction()
    {
        return $this->belongsTo(TransactionOut::class);
    }
    
    public function product() {
        return $this->hasMany(Product::class, 'id', 'id_product');
    }
}
