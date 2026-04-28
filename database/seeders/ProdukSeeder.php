<?php

namespace Database\Seeders;

use App\Models\Produk;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Produk::create([
            "nama"=> "KERTAS A4 75 Gr",
            "satuan"=> "Rim",
            "Harga"=> "48000",
            "Stok"=> "75",
        ]);
    }
}
