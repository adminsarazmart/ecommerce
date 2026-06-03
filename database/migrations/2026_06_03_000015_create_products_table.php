<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('vendor_id')->nullable();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->unique();
            $table->text('description')->nullable();
            $table->text('short_description')->nullable();
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->unsignedBigInteger('category_id');
            $table->decimal('unit_price', 10, 2)->default(0);
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->decimal('cost_price', 10, 2)->nullable();
            $table->decimal('wholesale_price', 10, 2)->nullable();
            $table->integer('min_wholesale_qty')->default(1);
            $table->decimal('tax', 8, 2)->default(0);
            $table->string('tax_type')->default('exclusive');
            $table->decimal('weight', 10, 2)->nullable();
            $table->decimal('height', 10, 2)->nullable();
            $table->decimal('width', 10, 2)->nullable();
            $table->decimal('length', 10, 2)->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->json('tags')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_trending')->default(false);
            $table->boolean('is_new')->default(true);
            $table->boolean('allow_backorder')->default(false);
            $table->integer('min_qty')->default(1);
            $table->integer('max_qty')->nullable();
            $table->float('seo_score', 8, 2)->default(0);
            $table->float('total_ratings', 8, 2)->default(0);
            $table->integer('total_reviews')->default(0);
            $table->integer('total_sales')->default(0);
            $table->integer('total_wishlist')->default(0);
            $table->boolean('is_virtual')->default(false);
            $table->string('download_url')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('set null');
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('set null');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
