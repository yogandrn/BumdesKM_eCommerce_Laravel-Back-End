<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;
    
    protected $table = 'transaction_details';

    protected $guarded = ['id'];

    public function transaction()
    {
        return $this->belongsTo(TransactionOut::class);
    }
}
