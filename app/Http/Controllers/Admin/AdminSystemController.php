<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SystemSetting;
use App\Models\AdminActivityLog;

class AdminSystemController extends Controller
{
    public function settings()
    {
        $settings = SystemSetting::all()->groupBy('group');
        return view('admin.settings.index', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $inputs = $request->except('_token');
        
        foreach ($inputs as $key => $value) {
            SystemSetting::where('key', $key)->update(['value' => $value]);
        }

        return back()->with('success', 'System settings updated successfully.');
    }

    public function activityLogs()
    {
        $logs = AdminActivityLog::with('admin')->latest()->paginate(50);
        return view('admin.settings.logs', compact('logs'));
    }
}
