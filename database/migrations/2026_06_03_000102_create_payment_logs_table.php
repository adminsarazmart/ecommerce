<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('gateway');
            $table->string('type');
            $table->string('endpoint')->nullable();
            $table->json('request_data')->nullable();
            $table->json('response')->nullable();
            $table->string('payment_id')->nullable()->index();
            $table->timestamps();

            $table->index('gateway');
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_logs');
    }
};
