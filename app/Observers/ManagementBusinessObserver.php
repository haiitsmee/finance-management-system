<?php

namespace App\Observers;

use App\Models\ActivityLog;
use App\Models\Business;
use Auth;
use Request;

class ManagementBusinessObserver
{
    /**
     * Handle the Business "created" event.
     */
    public function created(Business $business): void
    {
        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'action' => 'create',
            'activity' => 'Divisi ' . $business->name . ' berhasil dibuat.',
            'ip_address' => Request::ip()
        ]);
    }

    /**
     * Handle the Business "updated" event.
     */
    public function updated(Business $business): void
    {
        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'action' => 'update',
            'activity' => 'Divisi ' . $business->name . ' berhasil diperbaharui.',
            'ip_address' => Request::ip()
        ]);
    }

    /**
     * Handle the Business "deleted" event.
     */
    public function deleted(Business $business): void
    {
        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'action' => 'delete',
            'activity' => 'Divisi ' . $business->name . ' berhasil dihapus.',
            'ip_address' => Request::ip()
        ]);
    }

    /**
     * Handle the Business "restored" event.
     */
    public function restored(Business $business): void
    {
        //
    }

    /**
     * Handle the Business "force deleted" event.
     */
    public function forceDeleted(Business $business): void
    {
        //
    }
}
