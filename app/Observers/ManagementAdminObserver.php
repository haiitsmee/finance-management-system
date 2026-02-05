<?php

namespace App\Observers;

use App\Models\ActivityLog;
use App\Models\User;
use Auth;
use Request;

class ManagementAdminObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'action' => 'create',
            'activity' => 'Admin ' . $user->username . ' berhasil dibuat.',
            'ip_address' => Request::ip()
        ]);
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'action' => 'update',
            'activity' => 'Admin ' . $user->username . ' berhasil diperbaharui.',
            'ip_address' => Request::ip()
        ]);
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'action' => 'delete',
            'activity' => 'Admin ' . $user->username . ' berhasil dihapus.',
            'ip_address' => Request::ip()
        ]);
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
