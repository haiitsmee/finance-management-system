<?php

namespace App\Observers;

use App\Models\ActivityLog;
use App\Models\Transaction;
use Auth;
use Request;

class TransactionObserver
{
    /**
     * Handle the Transaction "created" event.
     */
    public function created(Transaction $transaction): void
    {
        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'action' => 'create',
            'activity' => ucwords($transaction->transactionCategory->tags) . ' berhasil ditambahkan.',
            'ip_address' => Request::ip()
        ]);
    }

    /**
     * Handle the Transaction "updated" event.
     */
    public function updated(Transaction $transaction): void
    {
        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'action' => 'update',
            'activity' => ucwords($transaction->transactionCategory->tags) . ' berhasil diperbaharui.',
            'ip_address' => Request::ip()
        ]);
    }

    /**
     * Handle the Transaction "deleted" event.
     */
    public function deleted(Transaction $transaction): void
    {
        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'action' => 'delete',
            'activity' => ucwords($transaction->transactionCategory->tags) . ' berhasil dihapus',
            'ip_address' => Request::ip()
        ]);
    }

    /**
     * Handle the Transaction "restored" event.
     */
    public function restored(Transaction $transaction): void
    {
        //
    }

    /**
     * Handle the Transaction "force deleted" event.
     */
    public function forceDeleted(Transaction $transaction): void
    {
        //
    }
}
