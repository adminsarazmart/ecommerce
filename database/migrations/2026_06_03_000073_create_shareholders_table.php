<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shareholders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->unique();
            $table->string('shareholder_code')->unique();
            $table->decimal('share_percentage', 8, 6)->default(16.666700);
            $table->decimal('total_investment', 15, 2)->default(0);
            $table->integer('total_shares')->default(0);
            $table->date('join_date')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shareholders');
    }
};
