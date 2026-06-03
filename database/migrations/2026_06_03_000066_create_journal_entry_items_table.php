<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('journal_entry_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('entry_id');
            $table->unsignedBigInteger('account_id');
            $table->string('type')->default('debit');
            $table->decimal('amount', 15, 2)->default(0);
            $table->text('description')->nullable();
            $table->timestamp('created_at')->nullable();

            $table->foreign('entry_id')->references('id')->on('journal_entries')->onDelete('cascade');
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('journal_entry_items');
    }
};
