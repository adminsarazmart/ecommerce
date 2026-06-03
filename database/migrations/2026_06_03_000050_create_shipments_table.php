<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('order_id');
            $table->string('tracking_number')->nullable();
            $table->string('carrier')->nullable();
            $table->string('status')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('estimated_delivery')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->json('address')->nullable();
            $table->timestamp('created_at')->nullable();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
