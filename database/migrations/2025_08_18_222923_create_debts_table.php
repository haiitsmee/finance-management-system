<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('debts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_id');
            $table->string('category');
            $table->string('supplier');
            $table->integer('amount');
            $table->date('due_date');
            $table->date('date');
            $table->boolean('is_paid')->default(false);
            $table->text('description')->nullable();
            $table->string('document_path')->nullable();
            $table->timestamps();


            $table->foreign('business_id')->references('id')->on('businesses');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('debts');
    }
};
