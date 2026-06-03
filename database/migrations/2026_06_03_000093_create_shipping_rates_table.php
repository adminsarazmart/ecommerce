<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipping_rates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('zone_id');
            $table->string('name');
            $table->decimal('min_weight', 10, 2)->nullable();
            $table->decimal('max_weight', 10, 2)->nullable();
            $table->decimal('min_total', 10, 2)->nullable();
            $table->decimal('max_total', 10, 2)->nullable();
            $table->decimal('rate', 10, 2)->default(0);
            $table->decimal('additional_rate', 10, 2)->default(0);
            $table->string('estimated_days')->nullable();
            $table->timestamp('created_at')->nullable();

            $table->foreign('zone_id')->references('id')->on('shipping_zones')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipping_rates');
    }
};
