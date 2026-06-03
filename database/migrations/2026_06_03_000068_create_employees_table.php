<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->unique();
            $table->string('employee_code')->unique();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->string('position')->nullable();
            $table->date('hire_date')->nullable();
            $table->decimal('salary', 10, 2)->default(0);
            $table->string('salary_type')->default('monthly');
            $table->decimal('pay_rate', 10, 2)->nullable();
            $table->string('employment_status')->default('active');
            $table->string('emergency_contact')->nullable();
            $table->string('emergency_phone')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_account')->nullable();
            $table->string('bank_branch')->nullable();
            $table->json('documents')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
