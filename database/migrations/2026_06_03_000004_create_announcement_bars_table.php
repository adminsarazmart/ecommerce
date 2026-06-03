<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('announcement_bars', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('link')->nullable();
            $table->string('link_text')->nullable();
            $table->string('bg_color')->nullable();
            $table->string('text_color')->nullable();
            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();
            $table->boolean('active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcement_bars');
    }
};
