<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pos_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('session_id');
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('register_id')->nullable();
            $table->string('pos_number')->unique();
            $table->string('type')->default('retail');
            $table->string('payment_method')->default('cash');
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('grand_total', 10, 2)->default(0);
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->decimal('change_amount', 10, 2)->default(0);
            $table->string('status')->default('completed');
            $table->unsignedBigInteger('created_by');
            $table->timestamp('created_at')->nullable();

            $table->foreign('session_id')->references('id')->on('pos_sessions')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('set null');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pos_orders');
    }
};
