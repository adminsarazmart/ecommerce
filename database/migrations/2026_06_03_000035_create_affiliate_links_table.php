<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('affiliate_links', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('reseller_id');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('link')->unique();
            $table->integer('visits')->default(0);
            $table->integer('orders')->default(0);
            $table->decimal('commissions', 15, 2)->default(0);
            $table->timestamp('created_at')->nullable();

            $table->foreign('reseller_id')->references('id')->on('resellers')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('affiliate_links');
    }
};
