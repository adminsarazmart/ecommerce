<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('supplier_id');
            $table->unsignedBigInteger('warehouse_id');
            $table->string('order_number')->unique();
            $table->string('status')->default('draft');
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('shipping', 10, 2)->default(0);
            $table->decimal('grand_total', 15, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamp('ordered_at')->nullable();
            $table->timestamp('expected_at')->nullable();
            $table->timestamp('received_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamps();

            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
