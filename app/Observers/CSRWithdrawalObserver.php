<?php

namespace App\Observers;

use App\Models\ActivityLog;
use App\Models\CSRWithdrawal;
use Auth;
use Request;

class CSRWithdrawalObserver
{
    /**
     * Handle the CSRWithdrawal "created" event.
     */
    public function created(CSRWithdrawal $CSRWithdrawal): void
    {
        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'action' => 'create',
            'activity' => 'CSR berhasil disalurkan sebesar ' . formatCurrency($CSRWithdrawal->amount),
            'ip_address' => Request::ip()
        ]);
    }

    /**
     * Handle the CSRWithdrawal "updated" event.
     */
    public function updated(CSRWithdrawal $CSRWithdrawal): void
    {
        //
    }

    /**
     * Handle the CSRWithdrawal "deleted" event.
     */
    public function deleted(CSRWithdrawal $CSRWithdrawal): void
    {
        //
    }

    /**
     * Handle the CSRWithdrawal "restored" event.
     */
    public function restored(CSRWithdrawal $CSRWithdrawal): void
    {
        //
    }

    /**
     * Handle the CSRWithdrawal "force deleted" event.
     */
    public function forceDeleted(CSRWithdrawal $CSRWithdrawal): void
    {
        //
    }
}
