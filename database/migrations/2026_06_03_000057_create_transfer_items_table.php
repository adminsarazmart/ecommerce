<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transfer_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('transfer_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('variant_id')->nullable();
            $table->integer('quantity');
            $table->timestamp('created_at')->nullable();

            $table->foreign('transfer_id')->references('id')->on('warehouse_transfers')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('variant_id')->references('id')->on('product_variants')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transfer_items');
    }
};
