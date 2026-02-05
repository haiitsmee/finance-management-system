<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\DashboardBusinessController;
use App\Http\Controllers\ReportAllController;
use App\Http\Controllers\DashboardSuperAdmin;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TransactionCategoryController;
use App\Http\Controllers\TransactionExpenseController;
use App\Http\Controllers\TransactionIncomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UtangPiutangController;
use App\Http\Controllers\CSRController;
use App\Http\Controllers\TransactionExpenseMain;
use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return view('pages.authentication.login');
})->name('login');
Route::get('/login', function () {
    return view('pages.authentication.login');
})->name('login');

Route::post('/login', [AuthController::class, 'login'])->name('login.process');

Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::middleware(['auth', 'set.business'])->group(function () {
    Route::group(['prefix' => 'superadmin', 'as' => 'superadmin.'], function () {
        // Dashboard Superadmin
        Route::get('/dashboard', function () {
            return view('pages.superadmin.dashboard'); })->name('superadmin.dashboard');

        Route::get('/laporan-keuangan-all', [ReportAllController::class, 'index'])->name('laporan-keuangan-all.index');
        Route::get('/laporan-keuangan-all/detail', [ReportAllController::class, 'show'])->name('laporan-keuangan-all.show');
        Route::get('/laporan-keuangan-all/cetak-pdf', [ReportAllController::class, 'printPdf'])->name('laporan-keuangan-all.cetak-pdf');

        Route::resource('/dashboard', DashboardSuperAdmin::class)
        ->names([
            'index' => 'dashboard',
        ]);
        Route::post('/dashboard/handle-range', [DashboardSuperAdmin::class, 'handleRange'])
        ->name('dashboard.handleRange');
        
        // Manajemen Admin
        Route::resource('/manajemen-admin', UserController::class)->except('show');

        // Manajemen Divisi
        Route::resource('/manajemen-divisi', BusinessController::class);

        // Manajemen Kategori Transaksi
        Route::resource('/manajemen-kategori', TransactionCategoryController::class)->except(['show', 'create']);

        // Log Aktifitas
        Route::get('/log-aktifitas', [ActivityLogController::class, 'index'])->name('log-aktifitas.index');
        
        // CSR
        // routes/web.php
        Route::prefix('csr')->group(function () {
            Route::get('/', [CSRController::class, 'index'])->name('csr.index');
            Route::post('/setting/update', [CSRController::class, 'updatePercentage'])->name('csr-setting.update');
            Route::post('/income/manual', [CSRController::class, 'addManualIncome'])->name('csr.income.manual');
            Route::post('/withdraw/all', [CSRController::class, 'withdrawAll'])->name('csr.withdraw.all');
            Route::post('/distribution/add', [CSRController::class, 'addDistribution'])->name('csr.distribution.add');
            Route::get('/distribution/add', [CSRController::class, 'showCreateDistributionForm'])->name('csr.create-distribution.add');            
            Route::get('/csr-income/add', [CSRController::class, 'showCreateIncomeForm'])->name('csr.create-income.add');            
            Route::post('/csr-income/add', [CSRController::class, 'addManualIncome'])->name('csr.create-income.submit');            
            // Untuk cron job harian
            Route::post('/generate-daily', [CSRController::class, 'generateDailyCSR'])->name('csr.generate.daily');
            Route::get('/distribution/{id}/detail', [CSRController::class, 'showDistributionDetail'])
            ->name('csr.distribution.detail');
            Route::get('/distribution/{id}/print', [CSRController::class, 'printDistributionPdf'])
                ->name('csr.distribution.print');
        });

        //Catat Pengeluaran Pusat
        // Ganti route resource dengan route biasa
        Route::get('/catat-pengeluaran-pusat', [TransactionExpenseMain::class, 'index'])->name('catat-pengeluaran-pusat.index');
        Route::get('/catat-pengeluaran-pusat/create', [TransactionExpenseMain::class, 'create'])->name('catat-pengeluaran-pusat.create');
        Route::post('/catat-pengeluaran-pusat', [TransactionExpenseMain::class, 'store'])->name('catat-pengeluaran-pusat.store');
        Route::get('/catat-pengeluaran-pusat/{id}/edit', [TransactionExpenseMain::class, 'edit'])->name('catat-pengeluaran-pusat.edit');
        Route::put('/catat-pengeluaran-pusat/{id}', [TransactionExpenseMain::class, 'update'])->name('catat-pengeluaran-pusat.update');
        Route::delete('/catat-pengeluaran-pusat/{id}', [TransactionExpenseMain::class, 'destroy'])->name('catat-pengeluaran-pusat.destroy');
        Route::get('/catat-pengeluaran-pusat/{id}', [TransactionExpenseMain::class, 'show'])->name('catat-pengeluaran-pusat.show');    });

    Route::middleware(['authorize.business'])
        ->prefix('superadmin/{businesses:slug}')
        ->name('superadmin.')
        ->group(function () {
            Route::resource('/utangpiutang', UtangPiutangController::class)
                ->parameters(['utangpiutang' => 'id']);
            Route::get('/utangpiutang/{id}/cetak-pdf', [UtangPiutangController::class, 'printPdf'])->name('utangpiutang.cetak-pdf');

            // Dashboard Admin
            Route::resource('/dashboard', DashboardBusinessController::class);
            // Catat Pemasukan dan Catat Pengeluaranw
    
            Route::resource('/catat-pemasukan', TransactionIncomeController::class);
            Route::resource('/catat-pengeluaran', TransactionExpenseController::class);
            Route::get('/catat-pemasukan/{id}/cetak-pdf', [TransactionIncomeController::class, 'printPdf'])->name('catat-pemasukan.cetak-pdf');
            Route::get('/catat-pengeluaran/{id}/cetak-pdf', [TransactionExpenseController::class, 'printPdf'])->name('catat-pengeluaran.cetak-pdf');
            Route::patch('/utangpiutang/{id}/settle', [UtangPiutangController::class, 'settleDebt'])
                ->name('utangpiutang.settle');
            // Utang dan Piutang
    
            Route::resource('/laporan-keuangan', ReportController::class)
                ->names([
                    'index' => 'report.index',
                    'show' => 'report.show',
                ])
                ->parameters(['laporan-keuangan' => 'date']);

            Route::post('/dashboard', [DashboardBusinessController::class, 'handleRange'])
                ->name('handleRange');
            Route::get('/laporan-keuangan/{month}/cetak-pdf', [ReportController::class, 'printPdf'])->name('laporan-keuangan.cetak-pdf');
        });

    Route::middleware(['authorize.business'])
        ->prefix('admin/{businesses:slug}')
        ->name('admin.')
        ->group(function () {
            Route::resource('/utangpiutang', UtangPiutangController::class)
                ->parameters(['utangpiutang' => 'id']);
            Route::get('/utangpiutang/{id}/cetak-pdf', [UtangPiutangController::class, 'printPdf'])->name('utangpiutang.cetak-pdf');

            Route::get('/dashboard', [DashboardBusinessController::class, 'index'])->name('dashboard');

            Route::post('/dashboard', [DashboardBusinessController::class, 'handleRange'])
                ->name('handleRange');

            Route::resource('/catat-pemasukan', TransactionIncomeController::class);
            Route::resource('/catat-pengeluaran', TransactionExpenseController::class);
            Route::get('/catat-pemasukan/{id}/cetak-pdf', [TransactionIncomeController::class, 'printPdf'])->name('catat-pemasukan.cetak-pdf');
            Route::get('/catat-pengeluaran/{id}/cetak-pdf', [TransactionExpenseController::class, 'printPdf'])->name('catat-pengeluaran.cetak-pdf');
            Route::patch('/utangpiutang/{id}/settle', [UtangPiutangController::class, 'settleDebt'])
                ->name('utangpiutang.settle');

            Route::resource('/laporan-keuangan', ReportController::class)
                ->names([
                    'index' => 'report.index',
                    'show' => 'report.show',
                ])
                ->parameters(['laporan-keuangan' => 'date']);
            Route::get('/laporan-keuangan/{month}/cetak-pdf', [ReportController::class, 'printPdf'])->name('laporan-keuangan.cetak-pdf');
        });


});

