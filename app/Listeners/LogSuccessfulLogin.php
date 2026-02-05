<?php

namespace App\Listeners;

use App\Models\ActivityLog;
use Auth;
use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Request;

class LogSuccessfulLogin
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
    public function handle(Login $event): void
    {
        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'action' => 'login',
            'activity' => Auth::user()->username . ' berhasil melakukan login.',
            'ip_address' => Request::ip(),
        ]);
    }
}
