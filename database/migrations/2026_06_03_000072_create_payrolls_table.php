<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payrolls', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('employee_id');
            $table->date('pay_period_start');
            $table->date('pay_period_end');
            $table->decimal('basic_salary', 10, 2)->default(0);
            $table->json('allowances')->nullable();
            $table->json('deductions')->nullable();
            $table->decimal('overtime_pay', 10, 2)->default(0);
            $table->decimal('bonus', 10, 2)->default(0);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('net_pay', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->string('status')->default('draft');
            $table->timestamp('paid_at')->nullable();
            $table->string('payment_method')->nullable();
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
