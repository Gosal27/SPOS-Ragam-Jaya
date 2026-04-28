<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    Schema::table('sales', function (Blueprint $table) {
        $table->date('tanggal_transaksi')->nullable()->after('subtotal');
    });

    Schema::table('sale_details', function (Blueprint $table) {
        $table->date('tanggal_transaksi')->nullable()->after('subtotal');
    });
}

public function down(): void
{
    Schema::table('sales', function (Blueprint $table) {
        $table->dropColumn('tanggal_transaksi');
    });

    Schema::table('sale_details', function (Blueprint $table) {
        $table->dropColumn('tanggal_transaksi');
    });
}

};
