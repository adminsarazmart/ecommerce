<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attributes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('group_id');
            $table->string('name');
            $table->string('slug');
            $table->string('type')->default('text');
            $table->boolean('is_required')->default(false);
            $table->boolean('is_filterable')->default(false);
            $table->boolean('is_visible_on_product')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->foreign('group_id')->references('id')->on('attribute_groups')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attributes');
    }
};
