<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendor_payouts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('vendor_id');
            $table->decimal('amount', 15, 2)->default(0);
            $table->decimal('fee', 10, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->string('payment_method')->nullable();
            $table->json('account_details')->nullable();
            $table->string('status')->default('pending');
            $table->text('notes')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamps();

            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_payouts');
    }
};
