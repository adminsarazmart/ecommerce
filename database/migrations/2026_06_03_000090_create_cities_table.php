<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('state_id');
            $table->string('name');

            $table->foreign('state_id')->references('id')->on('states')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};
