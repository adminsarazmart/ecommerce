<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loyalty_points', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('customer_id');
            $table->integer('points');
            $table->string('type')->default('earned');
            $table->string('reference_type')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->text('description')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('created_at')->nullable();

            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loyalty_points');
    }
};
