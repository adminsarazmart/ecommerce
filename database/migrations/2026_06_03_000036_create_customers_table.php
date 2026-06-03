<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->unique();
            $table->integer('total_orders')->default(0);
            $table->decimal('total_spent', 15, 2)->default(0);
            $table->integer('loyalty_points')->default(0);
            $table->decimal('wallet_balance', 15, 2)->default(0);
            $table->string('membership_level')->default('bronze');
            $table->unsignedBigInteger('referred_by')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('referred_by')->references('id')->on('customers')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
