<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('employee_id');
            $table->date('date');
            $table->timestamp('clock_in')->nullable();
            $table->timestamp('clock_out')->nullable();
            $table->string('status')->default('present');
            $table->decimal('worked_hours', 5, 2)->default(0);
            $table->decimal('overtime_hours', 5, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamp('created_at')->nullable();

            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
