<?php

namespace App\Observers;

use App\Models\ActivityLog;
use App\Models\Debt;
use Auth;
use Request;

class DebtObserver
{
    /**
     * Handle the Debt "created" event.
     */
    public function created(Debt $debt): void
    {
        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'action' => 'create',
            'activity' => 'Hutang senilai ' . formatCurrency($debt->amount) . ' berhasil ditambahkan.',
            'ip_address' => Request::ip()
        ]);
    }

    /**
     * Handle the Debt "updated" event.
     */
    public function updated(Debt $debt): void
    {
        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'action' => 'update',
            'activity' => 'Hutang senilai ' . formatCurrency($debt->amount) . ' berhasil diperbaharui.',
            'ip_address' => Request::ip()
        ]);
    }

    /**
     * Handle the Debt "deleted" event.
     */
    public function deleted(Debt $debt): void
    {
        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'action' => 'delete',
            'activity' => 'Hutang senilai ' . formatCurrency($debt->amount) . ' berhasil dihapus.',
            'ip_address' => Request::ip()
        ]);
    }

    /**
     * Handle the Debt "restored" event.
     */
    public function restored(Debt $debt): void
    {
        //
    }

    /**
     * Handle the Debt "force deleted" event.
     */
    public function forceDeleted(Debt $debt): void
    {
        //
    }
}
