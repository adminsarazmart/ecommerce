<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shareholder_ledgers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('shareholder_id');
            $table->string('transaction_type')->default('investment');
            $table->decimal('amount', 15, 2)->default(0);
            $table->string('reference_type')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->text('description')->nullable();
            $table->decimal('balance_before', 15, 2)->default(0);
            $table->decimal('balance_after', 15, 2)->default(0);
            $table->timestamp('transaction_date')->nullable();
            $table->timestamp('created_at')->nullable();

            $table->foreign('shareholder_id')->references('id')->on('shareholders')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shareholder_ledgers');
    }
};
