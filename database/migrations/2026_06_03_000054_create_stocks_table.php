<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('variant_id')->nullable();
            $table->unsignedBigInteger('warehouse_id');
            $table->integer('quantity')->default(0);
            $table->integer('reserved_qty')->default(0);
            $table->integer('low_stock_threshold')->default(10);
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('variant_id')->references('id')->on('product_variants')->onDelete('cascade');
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');

            $table->unique(['product_id', 'variant_id', 'warehouse_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
