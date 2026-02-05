<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Blueprinteger;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_id');
            $table->unsignedBigInteger('transaction_category_id');
            $table->string('product');
            $table->integer('price');
            $table->integer('product_quantity');
            $table->integer('total');
            $table->dateTime('transaction_date');
            $table->text('description');
            $table->enum('status', ['lunas', 'bon']);
            $table->string('image');
            $table->timestamps();

            $table->foreign('business_id')->on('businesses')->references('id');
            $table->foreign('transaction_category_id')->on('transaction_categories')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
