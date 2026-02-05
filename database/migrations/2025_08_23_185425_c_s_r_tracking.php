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
        Schema::create('csr_distributions', function (Blueprint $table) {
            $table->id();
            $table->string('activity');
            $table->string('purpose');
            $table->decimal('amount', 15, 2);
            $table->date('date');
            $table->string('document_path')->nullable(); 
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
