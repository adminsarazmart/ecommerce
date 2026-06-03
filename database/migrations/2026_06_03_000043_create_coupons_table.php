<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->string('type')->default('fixed');
            $table->decimal('value', 10, 2)->default(0);
            $table->decimal('min_order_amount', 10, 2)->nullable();
            $table->decimal('max_discount', 10, 2)->nullable();
            $table->integer('usage_limit')->nullable();
            $table->integer('usage_per_user')->nullable();
            $table->integer('used_count')->default(0);
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('applies_to')->default('all');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('vendor_id')->nullable();
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
