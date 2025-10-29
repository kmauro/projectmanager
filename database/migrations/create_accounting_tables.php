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
        Schema::create('account_types', function (Blueprint $table) {
            $table->id();
            $table->string('account_type');
        });

        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('account_name');
            $table->foreignId('account_type_id')->constrained()->onDelete('cascade');
            $table->string('description')->nullable();
            $table->float('balance')->default(0);
            $table->timestamps();
        });

        Schema::create('expense_types', function (Blueprint $table) {
            $table->id();
            $table->string('expense_type');
        });

        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('expense_name');
            $table->foreignId('account_id')->constrained()->onDelete('cascade');
            $table->foreignId('expense_type_id')->constrained()->onDelete('cascade');
            $table->float('amount');
            $table->timestamps();
        });

        Schema::create('sales_types', function (Blueprint $table) {
            $table->id();
            $table->string('sales_type');
        });

        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('sale_name');
            $table->foreignId('account_id')->constrained()->onDelete('cascade');
            $table->foreignId('sales_type_id')->constrained()->onDelete('cascade');
            $table->float('amount');
            $table->timestamps();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_types');
        Schema::dropIfExists('accounts');
        Schema::dropIfExists('expense_types');
        Schema::dropIfExists('expenses');
        Schema::dropIfExists('sales_types');
        Schema::dropIfExists('sales');
    }
};
