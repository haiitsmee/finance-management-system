<?php

namespace App\Observers;

use App\Models\ActivityLog;
use App\Models\CSRDistribution;
use Auth;
use Request;

class CSRDistributionObserver
{
    /**
     * Handle the CSRDistribution "created" event.
     */
    public function created(CSRDistribution $cSRDistribution): void
    {
        ActivityLog::create([
               'user_id' => Auth::user()->id,
               'action' => 'create',
               'activity' => 'Berhasil menyalurkan csr dengan tujuan ' . $cSRDistribution->purpose . ' sebesar ' . formatCurrency($cSRDistribution->amount),
               'ip_address' => Request::ip()
           ]);
    }

    /**
     * Handle the CSRDistribution "updated" event.
     */
    public function updated(CSRDistribution $cSRDistribution): void
    {
        //
    }

    /**
     * Handle the CSRDistribution "deleted" event.
     */
    public function deleted(CSRDistribution $cSRDistribution): void
    {
        //
    }

    /**
     * Handle the CSRDistribution "restored" event.
     */
    public function restored(CSRDistribution $cSRDistribution): void
    {
        //
    }

    /**
     * Handle the CSRDistribution "force deleted" event.
     */
    public function forceDeleted(CSRDistribution $cSRDistribution): void
    {
        //
    }
}
