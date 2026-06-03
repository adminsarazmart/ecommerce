<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendor_wallets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('vendor_id')->unique();
            $table->decimal('balance', 15, 2)->default(0);
            $table->decimal('pending_balance', 15, 2)->default(0);
            $table->decimal('total_earned', 15, 2)->default(0);
            $table->decimal('total_withdrawn', 15, 2)->default(0);
            $table->string('currency')->default('USD');
            $table->timestamps();

            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_wallets');
    }
};
