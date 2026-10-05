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

            // very useful for filtering + sorting by date on a single product
            $table->index(['product_id', 'created_at']);

            // useful when frequently filtering by type + date
            $table->index(['type', 'created_at']);
        });
    }

        public function down(): void
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->dropIndex(['product_id']);
            $table->dropIndex(['type']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['product_id', 'created_at']);
            $table->dropIndex(['type', 'created_at']);
        });
    }
};
