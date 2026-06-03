<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reseller_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('reseller_id');
            $table->decimal('amount', 15, 2)->default(0);
            $table->string('type')->default('commission');
            $table->text('description')->nullable();
            $table->string('reference_type')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->decimal('balance_before', 15, 2)->default(0);
            $table->decimal('balance_after', 15, 2)->default(0);
            $table->string('status')->default('completed');
            $table->timestamp('created_at')->nullable();

            $table->foreign('reseller_id')->references('id')->on('resellers')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reseller_transactions');
    }
};
