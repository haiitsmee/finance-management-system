<?php

namespace App\Observers;

use App\Models\ActivityLog;
use App\Models\CSRIncome;
use Auth;
use Request;

class CSRIncomeObserver
{
    /**
     * Handle the CSRIncome "created" event.
     */
    public function created(CSRIncome $csrIncome): void
    {
        if ($csrIncome->type == 'automatic') {
            ActivityLog::create([
               'user_id' => Auth::user()->id,
               'action' => 'create',
               'activity' => $csrIncome->description,
               'ip_address' => Request::ip()
           ]);
        } else {
            ActivityLog::create([
               'user_id' => Auth::user()->id,
               'action' => 'create',
               'activity' => 'Berhasil menambahkan csr dari ' . $csrIncome->source . ' sebesar ' . formatCurrency($csrIncome->amount),
               'ip_address' => Request::ip()
           ]);
        }
    }

    /**
     * Handle the CSRIncome "updated" event.
     */
    public function updated(CSRIncome $cSRIncome): void
    {
        //
    }

    /**
     * Handle the CSRIncome "deleted" event.
     */
    public function deleted(CSRIncome $cSRIncome): void
    {
        //
    }

    /**
     * Handle the CSRIncome "restored" event.
     */
    public function restored(CSRIncome $cSRIncome): void
    {
        //
    }

    /**
     * Handle the CSRIncome "force deleted" event.
     */
    public function forceDeleted(CSRIncome $cSRIncome): void
    {
        //
    }
}
