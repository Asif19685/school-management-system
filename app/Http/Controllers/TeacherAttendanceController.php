<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\TeacherAttendance;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class TeacherAttendanceController extends Controller
{
    public function index(): View
    {
        return view('modules.teacher_attendance.index');
    }


public function getAttendanceData(Request $request)
{
    $date         = $request->input('date', today()->toDateString());
    $statusFilter = $request->input('status_filter', 'all');

    $query = Teacher::with([
        'attendances' => fn($q) => $q->whereDate('date', $date)
    ])
    ->select('teachers.*');

    // Filter by attendance status (card click)
    if ($statusFilter !== 'all') {
        if ($statusFilter === 'not_marked') {
            $markedTeacherIds = TeacherAttendance::whereDate('date', $date)->pluck('teacher_id');
            $query->whereNotIn('id', $markedTeacherIds);
        } else {
            $query->whereHas('attendances', fn($q) => $q->whereDate('date', $date)->where('status', $statusFilter));
        }
    }

    return DataTables::of($query)
        ->addIndexColumn()
        ->addColumn('photo', function ($row) {
            $name = urlencode($row->name);
            $url = "https://ui-avatars.com/api/?name={$name}&background=6366f1&color=fff";
            return '<img src="' . $url . '" class="rounded-circle" width="38" height="38" style="object-fit:cover;">';
        })
        ->addColumn('check_in_out', function ($row) {
            $record = $row->attendances->first();
            if (!$record || !$record->check_in) {
                return '<span class="text-muted small">–</span>';
            }
            $in = Carbon::parse($record->check_in)->format('h:i A');
            $out = $record->check_out ? Carbon::parse($record->check_out)->format('h:i A') : 'Not Checked Out';
            return '<span class="small fw-semibold">' . $in . ' - ' . $out . '</span>';
        })
        ->addColumn('status', function ($row) use ($date) {
            $record = $row->attendances->first();

            if (!$record) {
                return '<div class="d-flex align-items-center justify-content-center gap-2">'
                    . '<span class="badge bg-secondary px-3 py-2"><i class="bi bi-dash-circle me-1"></i>Not Marked</span>'
                    . '<button class="btn btn-sm btn-link p-0 text-muted edit-attendance-btn" '
                    . 'data-teacher-id="' . $row->id . '" '
                    . 'data-current="none" '
                    . 'data-date="' . $date . '" '
                    . 'data-check-in="" '
                    . 'data-check-out="" '
                    . 'title="Mark Attendance"><i class="bi bi-pencil-square"></i></button>'
                    . '</div>';
            }

            $status = $record->status;
            $checkInVal = $record->check_in ? Carbon::parse($record->check_in)->format('H:i') : '';
            $checkOutVal = $record->check_out ? Carbon::parse($record->check_out)->format('H:i') : '';

            $badge = match($status) {
                'Present'  => '<span class="badge bg-success px-3 py-2"><i class="bi bi-check-circle me-1"></i>Present</span>',
                'Absent'   => '<span class="badge bg-danger px-3 py-2"><i class="bi bi-x-circle me-1"></i>Absent</span>',
                'Leave'    => '<span class="badge bg-warning text-dark px-3 py-2"><i class="bi bi-calendar2-x me-1"></i>Leave</span>',
                'Half-Day' => '<span class="badge bg-info px-3 py-2"><i class="bi bi-clock me-1"></i>Half-Day</span>',
                default    => '<span class="badge bg-secondary px-3 py-2">' . $status . '</span>',
            };

            return '<div class="d-flex align-items-center justify-content-center gap-2">'
                . $badge
                . '<button class="btn btn-sm btn-link p-0 text-muted edit-attendance-btn" '
                . 'data-teacher-id="' . $row->id . '" '
                . 'data-current="' . $status . '" '
                . 'data-date="' . $date . '" '
                . 'data-check-in="' . $checkInVal . '" '
                . 'data-check-out="' . $checkOutVal . '" '
                . 'title="Change Attendance"><i class="bi bi-pencil-square"></i></button>'
                . '</div>';
        })
        // ➕ **ADD THIS NEW COLUMN FOR DATE**
        ->addColumn('attendance_date', function ($row) {
            $record = $row->attendances->first();
            if (!$record) {
                return '<span class="text-muted">—</span>';
            }

            // Format created_at from the attendance record
            return '<span class="small">' . Carbon::parse($record->created_at)->format('d M Y h:i A') . '</span>';
        })
        ->rawColumns(['photo', 'check_in_out', 'status', 'attendance_date']) // Add 'attendance_date' here
        ->make(true);
}
    /**
     * JSON summary counts for a given date
     */
    public function getDailySummary(Request $request)
    {
        $date = $request->input('date', today()->toDateString());

        $total = Teacher::count();
        $records = TeacherAttendance::whereDate('date', $date)->get();

        $present  = $records->where('status', 'Present')->count();
        $absent   = $records->where('status', 'Absent')->count();
        $leave    = $records->where('status', 'Leave')->count();
        $halfDay  = $records->where('status', 'Half-Day')->count();
        $notMarked = $total - $records->count();

        return response()->json(compact('total', 'present', 'absent', 'leave', 'halfDay', 'notMarked'));
    }

    /**
     * Mark or update teacher attendance
     */
    public function markAttendance(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'teacher_id' => 'required|exists:teachers,id',
            'status'     => 'required|in:Present,Absent,Leave,Half-Day',
            'date'       => 'nullable|date_format:Y-m-d',
            'check_in'   => 'nullable|date_format:H:i',
            'check_out'  => 'nullable|date_format:H:i',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        $status = $request->input('status');
        $date   = $request->input('date') ?: today()->toDateString();

        $checkIn = null;
        $checkOut = null;

        if (in_array($status, ['Present', 'Half-Day'])) {
            $checkIn = $request->input('check_in') ?: '08:00';
            $checkOut = $request->input('check_out') ?: null;
        }

        TeacherAttendance::updateOrCreate(
            [
                'teacher_id' => $request->teacher_id,
                'date'       => $date,
            ],
            [
                'status'    => $status,
                'check_in'  => $checkIn,
                'check_out' => $checkOut,
            ]
        );

        $messages = [
            'Present'  => 'Teacher marked as Present.',
            'Absent'   => 'Teacher marked as Absent.',
            'Leave'    => 'Teacher marked as on Leave.',
            'Half-Day' => 'Teacher marked as Half-Day.',
        ];

        return response()->json([
            'success' => true,
            'message' => $messages[$status] ?? 'Attendance updated.',
            'status'  => $status,
        ]);
    }
}
