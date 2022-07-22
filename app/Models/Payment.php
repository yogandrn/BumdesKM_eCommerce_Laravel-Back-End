<?php

namespace App\Models\Mobile;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    // use HasFactory;

    protected $fillable = ['id_transaction', 'payment', 'status'];
    protected $table = 'payments';
    protected $with = ['transaction'];

    public function transaction()
    {
        return $this->belongsTo(TransactionOut::class);
    }
}
