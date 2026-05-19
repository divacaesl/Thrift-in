<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SecurityController extends Controller
{
    public function serverMonitor()
    {
        return view('super_admin.server_monitor');
    }

    public function accessLogs()
    {
        return view('super_admin.access_logs');
    }
}
