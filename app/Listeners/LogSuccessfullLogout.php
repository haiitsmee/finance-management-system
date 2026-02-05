<?php

namespace App\Listeners;

use App\Models\ActivityLog;
use Auth;
use Illuminate\Auth\Events\Logout;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Request;

class LogSuccessfullLogout
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Logout $event): void
    {
        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'action' => 'logout',
            'activity' => Auth::user()->username . ' berhasil melakukan logout.',
            'ip_address' => Request::ip()
        ]);
    }
}
