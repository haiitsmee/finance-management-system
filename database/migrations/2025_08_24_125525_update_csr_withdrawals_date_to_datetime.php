<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('csr_withdrawals', function (Blueprint $table) {
        // buat kolom baru nullable dulu
        $table->dateTime('new_date')->nullable()->after('date');
    });

    // isi kolom baru, handle kasus '0000-00-00'
    DB::table('csr_withdrawals')->update([
        'new_date' => DB::raw("NULLIF(CONCAT(date, ' 00:00:00'), '0000-00-00 00:00:00')")
    ]);

    // kalau semua sudah valid, ubah kolom agar not null
    Schema::table('csr_withdrawals', function (Blueprint $table) {
        $table->dateTime('new_date')->nullable(false)->change();
        $table->dropColumn('date');
        $table->renameColumn('new_date', 'date');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('datetime', function (Blueprint $table) {
            //
        });
    }
};
