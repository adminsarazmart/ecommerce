<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendor_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('vendor_id');
            $table->decimal('amount', 15, 2)->default(0);
            $table->string('type')->default('order');
            $table->text('description')->nullable();
            $table->string('reference_type')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->decimal('balance_before', 15, 2)->default(0);
            $table->decimal('balance_after', 15, 2)->default(0);
            $table->string('status')->default('completed');
            $table->timestamp('created_at')->nullable();

            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_transactions');
    }
};
