<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('customer_id');
            $table->string('label')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone')->nullable();
            $table->string('address_line1');
            $table->string('address_line2')->nullable();
            $table->string('city');
            $table->string('state');
            $table->string('zip');
            $table->string('country');
            $table->boolean('is_default')->default(false);
            $table->boolean('is_billing')->default(true);
            $table->boolean('is_shipping')->default(true);
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
