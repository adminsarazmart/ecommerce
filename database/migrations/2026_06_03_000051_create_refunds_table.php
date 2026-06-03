<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('refunds', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('customer_id');
            $table->json('items')->nullable();
            $table->decimal('amount', 15, 2)->default(0);
            $table->text('reason')->nullable();
            $table->string('status')->default('pending');
            $table->string('refunded_to')->nullable();
            $table->unsignedBigInteger('processed_by')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('processed_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('refunds');
    }
};
