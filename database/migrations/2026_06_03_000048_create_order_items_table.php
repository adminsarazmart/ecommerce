<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('variant_id')->nullable();
            $table->unsignedBigInteger('vendor_id');
            $table->string('product_name');
            $table->string('product_sku');
            $table->string('variant_name')->nullable();
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2)->default(0);
            $table->decimal('total_price', 15, 2)->default(0);
            $table->decimal('cost_price', 10, 2)->default(0);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('commission_rate', 5, 2)->default(0);
            $table->decimal('commission_amount', 10, 2)->default(0);
            $table->decimal('vendor_earnings', 15, 2)->default(0);
            $table->boolean('is_refunded')->default(false);
            $table->integer('refund_qty')->default(0);
            $table->timestamp('created_at')->nullable();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('variant_id')->references('id')->on('product_variants')->onDelete('set null');
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
