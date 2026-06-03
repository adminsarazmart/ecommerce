<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dividend_distributions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('period_start');
            $table->date('period_end');
            $table->decimal('total_profit', 15, 2)->default(0);
            $table->decimal('total_expenses', 15, 2)->default(0);
            $table->decimal('net_profit', 15, 2)->default(0);
            $table->decimal('per_shareholder_amount', 15, 2)->default(0);
            $table->integer('total_shareholders')->default(0);
            $table->string('status')->default('calculated');
            $table->timestamp('distributed_at')->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dividend_distributions');
    }
};
