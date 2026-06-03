<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('slug');
            $table->string('type');
            $table->json('parameters')->nullable();
            $table->unsignedBigInteger('generated_by')->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_type')->nullable();
            $table->boolean('is_scheduled')->default(false);
            $table->string('schedule_cron')->nullable();
            $table->timestamp('last_generated_at')->nullable();
            $table->timestamp('created_at')->nullable();

            $table->foreign('generated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
