<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('withdrawal_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('vendor_id');
            $table->decimal('amount', 15, 2)->default(0);
            $table->decimal('fee', 10, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->string('payment_method')->nullable();
            $table->json('account_details')->nullable();
            $table->string('status')->default('pending');
            $table->text('admin_note')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('created_at')->nullable();

            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('withdrawal_requests');
    }
};
