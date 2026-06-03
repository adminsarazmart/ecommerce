<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('account_id');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->decimal('amount', 15, 2)->default(0);
            $table->text('description')->nullable();
            $table->string('reference_number')->nullable();
            $table->date('date');
            $table->string('payment_method')->nullable();
            $table->string('status')->default('paid');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->string('receipt')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
