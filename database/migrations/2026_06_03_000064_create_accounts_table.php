<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('code')->unique();
            $table->string('type')->default('asset');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->decimal('balance', 15, 2)->default(0);
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('accounts')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
