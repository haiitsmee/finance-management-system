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
        Schema::create('csr_incomes', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // 'automatic' atau 'manual'
            $table->foreignId('business_id')->nullable()->constrained();
            $table->string('source')->nullable();
            $table->decimal('amount', 15, 2);
            $table->date('date');
            $table->boolean('is_withdrawn')->default(false);
            $table->string('document_path')->nullable(); // Hanya untuk manual
            $table->text('description')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
