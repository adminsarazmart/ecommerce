<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tax_rates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->decimal('rate', 5, 2)->default(0);
            $table->string('type')->default('percentage');
            $table->string('applies_to')->default('all');
            $table->unsignedBigInteger('country_id')->nullable();
            $table->unsignedBigInteger('state_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('created_at')->nullable();

            $table->foreign('country_id')->references('id')->on('countries')->onDelete('set null');
            $table->foreign('state_id')->references('id')->on('states')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tax_rates');
    }
};
