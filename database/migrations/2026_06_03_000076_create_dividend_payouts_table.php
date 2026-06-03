<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dividend_payouts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('distribution_id');
            $table->unsignedBigInteger('shareholder_id');
            $table->decimal('amount', 15, 2)->default(0);
            $table->decimal('percentage', 8, 6)->default(0);
            $table->string('status')->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->string('payment_method')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('created_at')->nullable();

            $table->foreign('distribution_id')->references('id')->on('dividend_distributions')->onDelete('cascade');
            $table->foreign('shareholder_id')->references('id')->on('shareholders')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dividend_payouts');
    }
};
