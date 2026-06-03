<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('category_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('category_id');
            $table->string('locale');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('category_translations');
    }
};
