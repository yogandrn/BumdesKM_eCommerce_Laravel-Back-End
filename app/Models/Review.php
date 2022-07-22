<?php

namespace App\Models\Mobile;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    // use HasFactory;

    protected $table = 'reviews';
    protected $fillable = ['id_transaction', 'id_user', 'comment'];
    protected $with = ['user' ];

    public function transaction()
    {
        return $this->belongsTo(TransactionOut::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}
