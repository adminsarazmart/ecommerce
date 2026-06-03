<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_gateway_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('gateway');
            $table->string('payment_id')->nullable()->index();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->decimal('amount', 15, 2)->default(0);
            $table->string('currency', 10)->default('BDT');
            $table->string('status')->default('pending');
            $table->json('response')->nullable();
            $table->json('request_data')->nullable();
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('set null');
            $table->index('gateway');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_gateway_transactions');
    }
};
