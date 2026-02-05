<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('debts', function (Blueprint $table) {
            if (Schema::hasColumn('debts', 'business_name')) {
                $table->dropColumn('business_name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('debts', function (Blueprint $table) {
            Schema::table('debts', function (Blueprint $table) {
                $table->string('business_name')->nullable();
            });
        });
    }
};
