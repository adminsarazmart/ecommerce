<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('membership_levels', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('slug')->unique();
            $table->decimal('min_spend', 15, 2)->default(0);
            $table->integer('points_required')->default(0);
            $table->decimal('discount_rate', 5, 2)->default(0);
            $table->json('benefits')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('membership_levels');
    }
};
