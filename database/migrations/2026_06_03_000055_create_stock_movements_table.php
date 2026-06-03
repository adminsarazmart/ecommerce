<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('variant_id')->nullable();
            $table->unsignedBigInteger('warehouse_id');
            $table->unsignedBigInteger('from_warehouse_id')->nullable();
            $table->string('reference_type')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->integer('quantity');
            $table->string('type')->default('in');
            $table->integer('before_qty')->default(0);
            $table->integer('after_qty')->default(0);
            $table->text('description')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('created_at')->nullable();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('variant_id')->references('id')->on('product_variants')->onDelete('cascade');
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');
            $table->foreign('from_warehouse_id')->references('id')->on('warehouses')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
