<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->unique();
            $table->string('store_name');
            $table->string('slug')->unique();
            $table->text('store_description')->nullable();
            $table->string('store_logo')->nullable();
            $table->string('store_banner')->nullable();
            $table->string('store_email')->nullable();
            $table->string('store_phone')->nullable();
            $table->string('store_address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip')->nullable();
            $table->string('country')->nullable();
            $table->decimal('commission_rate', 5, 2)->default(0);
            $table->string('commission_type')->default('percentage');
            $table->string('verification_status')->default('pending');
            $table->string('kyc_status')->default('pending');
            $table->json('kyc_documents')->nullable();
            $table->string('tax_id')->nullable();
            $table->string('business_registration')->nullable();
            $table->string('website')->nullable();
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('instagram')->nullable();
            $table->string('youtube')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->unsignedBigInteger('subscription_plan_id')->nullable();
            $table->timestamp('subscription_ends_at')->nullable();
            $table->decimal('total_ratings', 5, 2)->default(0);
            $table->integer('total_products')->default(0);
            $table->integer('total_sales')->default(0);
            $table->decimal('revenue', 15, 2)->default(0);
            $table->date('join_date')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('subscription_plan_id')->references('id')->on('subscription_plans')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
