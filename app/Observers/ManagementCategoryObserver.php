<?php

namespace App\Observers;

use App\Models\ActivityLog;
use App\Models\TransactionCategory;
use Auth;
use Request;

class ManagementCategoryObserver
{
    /**
     * Handle the TransactionCategory "created" event.
     */
    public function created(TransactionCategory $transactionCategory): void
    {
        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'action' => 'create',
            'activity' => 'Kategori transaksi ' . $transactionCategory->title . ' berhasil dibuat.',
            'ip_address' => Request::ip()
        ]);
    }

    /**
     * Handle the TransactionCategory "updated" event.
     */
    public function updated(TransactionCategory $transactionCategory): void
    {
        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'action' => 'update',
            'activity' => 'Kategori transaksi ' . $transactionCategory->title . ' berhasil diperbaharui.',
            'ip_address' => Request::ip()
        ]);
    }

    /**
     * Handle the TransactionCategory "deleted" event.
     */
    public function deleted(TransactionCategory $transactionCategory): void
    {
        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'action' => 'create',
            'activity' => 'Kategori transaksi ' . $transactionCategory->title . ' berhasil dihapus.',
            'ip_address' => Request::ip()
        ]);
    }

    /**
     * Handle the TransactionCategory "restored" event.
     */
    public function restored(TransactionCategory $transactionCategory): void
    {
        //
    }

    /**
     * Handle the TransactionCategory "force deleted" event.
     */
    public function forceDeleted(TransactionCategory $transactionCategory): void
    {
        //
    }
}
