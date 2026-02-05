<?php

namespace App\Observers;

use App\Models\ActivityLog;
use App\Models\CSRSetting;
use Auth;
use Request;

class CSRSettingObserver
{
    /**
     * Handle the CSRSetting "created" event.
     */
    public function created(CSRSetting $cSRSetting): void
    {
    }

    /**
     * Handle the CSRSetting "updated" event.
     */
    public function updated(CSRSetting $cSRSetting): void
    {
        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'action' => 'update',
            'activity' => 'Persenan CSR berhasil diganti menjadi ' . $cSRSetting->percentage . ' %',
            'ip_address' => Request::ip()
        ]);
    }

    /**
     * Handle the CSRSetting "deleted" event.
     */
    public function deleted(CSRSetting $cSRSetting): void
    {
        //
    }

    /**
     * Handle the CSRSetting "restored" event.
     */
    public function restored(CSRSetting $cSRSetting): void
    {
        //
    }

    /**
     * Handle the CSRSetting "force deleted" event.
     */
    public function forceDeleted(CSRSetting $cSRSetting): void
    {
        //
    }
}
