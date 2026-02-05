<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Cache;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index()
    {
        try {
            $activityLogs = ActivityLog::with('user')
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return view('pages.superadmin.manajemen-logs', compact('activityLogs'));
        } catch (\Exception $ex) {
            notify()->error('Kesalahan pada server.', 'Log Aktifitas');
            return redirect()->back();
        }
    }
}
