<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id('idSales'); // Primary key
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('subtotal');
            $table->timestamps();
        });

        Schema::create('sale_details', function (Blueprint $table) {
            $table->id('idSaleDetails'); // Primary key
            $table->foreignId('idSales')->constrained('sales', 'idSales')->onDelete('cascade');
            $table->foreignId('idProduk')->constrained('produks', 'id')->onDelete('cascade');
            $table->integer('quantity');
            $table->integer('subtotal');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_details');
        Schema::dropIfExists('sales');
    }
};

