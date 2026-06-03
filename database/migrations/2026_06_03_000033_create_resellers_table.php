<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resellers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->unique();
            $table->string('referral_code')->unique();
            $table->unsignedBigInteger('parent_reseller_id')->nullable();
            $table->decimal('commission_rate', 5, 2)->default(0);
            $table->decimal('total_earnings', 15, 2)->default(0);
            $table->decimal('total_withdrawn', 15, 2)->default(0);
            $table->decimal('current_balance', 15, 2)->default(0);
            $table->string('status')->default('active');
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('parent_reseller_id')->references('id')->on('resellers')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resellers');
    }
};
