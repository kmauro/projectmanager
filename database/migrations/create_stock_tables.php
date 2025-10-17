<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {   
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('cuit')->unique();
            $table->string('phone')->nullable();
            $table->string('email');
            $table->string('address');
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('category');
        });

        Schema::create('subcategories', function (Blueprint $table) {
            $table->id();
            $table->string('subcategory');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('subcategory_id')->constrained()->onDelete('cascade');
            $table->float('price');
            $table->float('cost');
            $table->integer('stock');
            $table->integer('min_stock');
        });

        Schema::create('suppliers_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('subcategories');
        Schema::dropIfExists('products');
        Schema::dropIfExists('suppliers_products');
        
    }
};
