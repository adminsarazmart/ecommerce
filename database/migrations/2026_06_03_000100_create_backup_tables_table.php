<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('backup_tables', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('file_path');
            $table->unsignedBigInteger('size')->default(0);
            $table->string('type')->nullable();
            $table->string('status')->default('pending');
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('backup_tables');
    }
};
