<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleDetail extends Model
{
    protected $table = 'sale_details';
    protected $primaryKey = 'idSaleDetails';

    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = ['idSales', 'idProduk', 'quantity', 'subtotal','tanggal_transaksi'];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'idProduk');
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'idSales', 'idSales');
    }
}



