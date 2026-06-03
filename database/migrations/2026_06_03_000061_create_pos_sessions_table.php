<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pos_sessions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('status')->default('open');
            $table->timestamp('opened_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->decimal('cash_start', 10, 2)->default(0);
            $table->decimal('cash_end', 10, 2)->nullable();
            $table->decimal('cash_sales', 10, 2)->default(0);
            $table->decimal('card_sales', 10, 2)->default(0);
            $table->decimal('total_sales', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamp('created_at')->nullable();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pos_sessions');
    }
};
