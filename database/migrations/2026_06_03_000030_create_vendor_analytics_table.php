<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendor_analytics', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('vendor_id');
            $table->date('date');
            $table->integer('views')->default(0);
            $table->integer('visits')->default(0);
            $table->integer('orders')->default(0);
            $table->decimal('revenue', 15, 2)->default(0);
            $table->decimal('commissions', 15, 2)->default(0);
            $table->decimal('conversion_rate', 5, 2)->default(0);
            $table->decimal('average_order_value', 10, 2)->default(0);
            $table->timestamp('created_at')->nullable();

            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_analytics');
    }
};
