<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('stock_movements', function (Blueprint $table) {
        $table->foreignId('user_id')->nullable()->after('product_id');
        $table->integer('stock_after')->default(0)->after('quantity');
    });
}

public function down(): void
{
    Schema::table('stock_movements', function (Blueprint $table) {
        $table->dropColumn(['user_id', 'stock_after']);
    });
}

};
