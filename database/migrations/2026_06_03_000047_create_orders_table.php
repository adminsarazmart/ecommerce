<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('order_number')->unique();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('vendor_id')->nullable();
            $table->unsignedBigInteger('coupon_id')->nullable();
            $table->decimal('coupon_discount', 10, 2)->default(0);
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('shipping_cost', 10, 2)->default(0);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('grand_total', 15, 2)->default(0);
            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->decimal('due_amount', 15, 2)->default(0);
            $table->string('currency')->default('USD');
            $table->decimal('exchange_rate', 10, 4)->default(1);
            $table->string('status')->default('pending');
            $table->string('payment_status')->default('unpaid');
            $table->string('shipping_status')->default('pending');
            $table->string('payment_method')->nullable();
            $table->string('shipping_method')->nullable();
            $table->json('shipping_address')->nullable();
            $table->json('billing_address')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_partial')->default(false);
            $table->json('partial_payments')->nullable();
            $table->boolean('is_seen')->default(false);
            $table->timestamp('placed_at')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('processing_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamp('refunded_at')->nullable();
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('set null');
            $table->foreign('coupon_id')->references('id')->on('coupons')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
