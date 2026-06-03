<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipping_zones', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->json('countries')->nullable();
            $table->json('states')->nullable();
            $table->json('zip_codes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipping_zones');
    }
};
