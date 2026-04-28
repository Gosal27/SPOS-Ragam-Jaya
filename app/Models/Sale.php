<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $table = 'sales';
    protected $primaryKey = 'idSales';

    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = ['user_id', 'subtotal', 'tanggal_transaksi'];

    public function details()
    {
        return $this->hasMany(SaleDetail::class, 'idSales', 'idSales');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}


