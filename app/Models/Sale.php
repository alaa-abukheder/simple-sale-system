<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;
    protected $fillable = ['client_id', 'seller_id', 'total'];

    public function client()
    {
        return $this->belongsTo(User::class,"client_id","id");
    }
    public function seller()
    {
        return $this->belongsTo(User::class,"seller_id","id");
    }

    public function saleTransactions()
    {
        return $this->hasMany(SaleTransaction::class);
    }
}
