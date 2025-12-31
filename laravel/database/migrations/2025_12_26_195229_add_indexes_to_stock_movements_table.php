<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->index('product_id');
            $table->index('type');
            $table->index('created_at');

            // très utile pour filtres + tri par date sur un produit
            $table->index(['product_id', 'created_at']);

            // utile si tu filtres souvent type + date
            $table->index(['type', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->dropIndex(['stock_movements_product_id_index']);
            $table->dropIndex(['stock_movements_type_index']);
            $table->dropIndex(['stock_movements_created_at_index']);
            $table->dropIndex(['stock_movements_product_id_created_at_index']);
            $table->dropIndex(['stock_movements_type_created_at_index']);
        });
    }
};
