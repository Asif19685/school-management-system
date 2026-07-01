<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $modules = config('sms.modules', []);

         if (!Schema::hasTable('students')) {
            unset($modules['students']);
        }

        $dashboardStats = [
            'students' => DB::table('students')->count(),
            'teachers' => DB::table('teachers')->count(),
            'courses' => DB::table('courses')->count(),
            'pending_fees' => (float) DB::table('fees')->where('status', 'pending')->sum('amount'),
            'visitors_today' => DB::table('visitors')->whereDate('created_at', today())->count(),
            'attendance_today' => DB::table('attendance')
                ->whereDate('attendance_date', today())
                ->where('status', 'present')
                ->count(),
            'notifications_unread' => DB::table('notifications')->where('is_read', false)->count(),
        ];

        return view('dashboard', compact('modules', 'dashboardStats'));
    }

    public function module(string $module): View
    {
        $modules = config('sms.modules', []);
        abort_unless(array_key_exists($module, $modules), 404);

        return view('modules.show', [
            'moduleKey' => $module,
            'moduleTitle' => $modules[$module],
            'modules' => $modules,
        ]);
    }
}
