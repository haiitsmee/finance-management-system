<?php

namespace App\Providers;

use App\Models\Business;
use App\Models\CSRDistribution;
use App\Models\CSRIncome;
use App\Models\CSRSetting;
use App\Models\CSRWithdrawal;
use App\Models\Debt;
use App\Models\Transaction;
use App\Models\TransactionCategory;
use App\Models\User;
use App\Observers\CSRDistributionObserver;
use App\Observers\CSRIncomeObserver;
use App\Observers\CSRSettingObserver;
use App\Observers\CSRWithdrawalObserver;
use App\Observers\DebtObserver;
use App\Observers\ManagementAdminObserver;
use App\Observers\ManagementBusinessObserver;
use App\Observers\ManagementCategoryObserver;
use App\Observers\TransactionObserver;
use Blade;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useTailwind();

        Blade::directive('currency', function ( $expression ) { return "Rp. <?php echo number_format($expression,0,',','.'); ?>"; });

        User::observe(ManagementAdminObserver::class);
        Business::observe(ManagementBusinessObserver::class);
        TransactionCategory::observe(ManagementCategoryObserver::class);
        Transaction::observe(TransactionObserver::class);
        Debt::observe(DebtObserver::class);
        CSRSetting::observe(CSRSettingObserver::class);
        CSRWithdrawal::observe(CSRWithdrawalObserver::class);
        CSRIncome::observe(CSRIncomeObserver::class);
        CSRDistribution::observe(CSRDistributionObserver::class);
    }
}
