<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * @property string $nama
 * @property string $satuan
 * @property float $harga
 * @property int $stok
 */
class Produk extends Model
{
    use HasFactory;
    protected $fillable = [
        "nama","satuan","harga","stok","user_id"
    ];
}

