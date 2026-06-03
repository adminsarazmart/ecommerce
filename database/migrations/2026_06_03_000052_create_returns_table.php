<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('returns', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('customer_id');
            $table->json('items')->nullable();
            $table->text('reason')->nullable();
            $table->string('status')->default('pending');
            $table->string('return_type')->default('refund');
            $table->unsignedBigInteger('exchange_product_id')->nullable();
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('exchange_product_id')->references('id')->on('products')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('returns');
    }
};
