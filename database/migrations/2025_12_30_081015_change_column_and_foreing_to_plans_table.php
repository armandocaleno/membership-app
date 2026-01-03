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
        Schema::table('plans', function (Blueprint $table) {
            $table->dropForeign('plans_product_id_foreign');
            $table->renameColumn('product_id', 'products');
            $table->longText('products')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->renameColumn('products', 'product_id');
            $table->unsignedBigInteger('product_id')->change();
            $table->foreign('product_id')->references('id')->on('products');
        });
    }
};
