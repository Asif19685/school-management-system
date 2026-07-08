<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\SchoolClass;
use App\Models\StudentAdmission;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class AttendanceController extends Controller
{
    public function index(): View
    {
        $classes = SchoolClass::orderBy('class_name')->get();
        return view('modules.attendance.index', compact('classes'));
    }

    /**
     * Server-Side DataTable: Daily attendance report
     * Filters: date (default today), class_id
     */
    public function getAttendanceData(Request $request)
    {
        $date         = $request->input('date', today()->toDateString());
        $classId      = $request->input('class_id');
        $statusFilter = $request->input('status_filter', 'all');

        // All approved admissions with student and today's attendance
        $query = StudentAdmission::with([
            'student.studentImage',
            'student.attendances' => fn($q) => $q->whereDate('attendance_date', $date),
            'schoolClass',
            'section',
        ])
        ->where('status', 'approved')
        ->select('student_admissions.*');

        if ($classId) {
            $query->where('class_id', $classId);
        }

        // Filter by attendance status (card click)
        if ($statusFilter !== 'all') {
            if ($statusFilter === 'not_marked') {
                $markedStudentIds = \App\Models\Attendance::whereDate('attendance_date', $date)->pluck('student_id');
                $query->whereHas('student', fn($q) => $q->whereNotIn('id', $markedStudentIds));
            } else {
                $query->whereHas('student.attendances', fn($q) => $q->whereDate('attendance_date', $date)->where('status', $statusFilter));
            }
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('photo', function ($row) {
                if ($row->student && $row->student->studentImage) {
                    $url = asset($row->student->studentImage->image_path);
                } else {
                    $url = asset('images/default-avatar.png');
                }
                return '<img src="' . $url . '" class="rounded-circle" width="38" height="38" style="object-fit:cover;">';
            })
            ->addColumn('student_name', fn($row) => $row->student
                ? trim(($row->student->first_name ?? '') . ' ' . ($row->student->last_name ?? ''))
                : 'N/A'
            )
            ->addColumn('class',   fn($row) => $row->schoolClass->class_name ?? 'N/A')
            ->addColumn('section', fn($row) => $row->section->section_name   ?? 'N/A')
            ->addColumn('roll_no', fn($row) => $row->roll_no ?? 'N/A')
            ->addColumn('status', function ($row) use ($date) {
                $studentId = $row->student->id ?? null;
                if (!$studentId) {
                    return '<span class="text-muted small">N/A</span>';
                }
                $record = $row->student->attendances->first() ?? null;
                if (!$record) {
                    return '<div class="d-flex align-items-center justify-content-center gap-2">'
                        . '<span class="badge bg-secondary px-3 py-2"><i class="bi bi-dash-circle me-1"></i>Not Marked</span>'
                        . '<button class="btn btn-sm btn-link p-0 text-muted edit-attendance-btn" data-student-id="' . $studentId . '" data-current="none" data-date="' . $date . '" title="Mark Attendance"><i class="bi bi-pencil-square"></i></button>'
                        . '</div>';
                }
                
                $status = $record->status;
                $badge = match($status) {
                    'present' => '<span class="badge bg-success px-3 py-2"><i class="bi bi-check-circle me-1"></i>Present</span>',
                    'absent'  => '<span class="badge bg-danger px-3 py-2"><i class="bi bi-x-circle me-1"></i>Absent</span>',
                    'leave'   => '<span class="badge bg-warning text-dark px-3 py-2"><i class="bi bi-calendar2-x me-1"></i>Leave</span>',
                    default   => '<span class="badge bg-secondary px-3 py-2">' . ucfirst($status) . '</span>',
                };
                
                return '<div class="d-flex align-items-center justify-content-center gap-2">'
                    . $badge
                    . '<button class="btn btn-sm btn-link p-0 text-muted edit-attendance-btn" data-student-id="' . $studentId . '" data-current="' . $status . '" data-date="' . $date . '" title="Change Attendance"><i class="bi bi-pencil-square"></i></button>'
                    . '</div>';
            })
            ->rawColumns(['photo', 'status'])
            ->make(true);
    }

    /**
     * JSON summary counts for a given date + optional class
     */
    public function getDailySummary(Request $request)
    {
        $date    = $request->input('date', today()->toDateString());
        $classId = $request->input('class_id');

        $admissionsQuery = StudentAdmission::with('student')
            ->where('status', 'approved');

        if ($classId) {
            $admissionsQuery->where('class_id', $classId);
        }

        $admissions  = $admissionsQuery->get();
        $studentIds  = $admissions->pluck('student.id')->filter();
        $total       = $studentIds->count();

        $records = Attendance::whereIn('student_id', $studentIds)
            ->whereDate('attendance_date', $date)
            ->get();

        $present    = $records->where('status', 'present')->count();
        $absent     = $records->where('status', 'absent')->count();
        $leave      = $records->where('status', 'leave')->count();
        $notMarked  = $total - $records->count();

        return response()->json(compact('total', 'present', 'absent', 'leave', 'notMarked'));
    }

    /**
     * Mark or update student attendance
     */
    public function markAttendance(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'student_id' => 'required|exists:students,id',
            'status'     => 'required|in:present,absent,leave',
            'date'       => 'nullable|date_format:Y-m-d',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        $status = $request->input('status');
        $date   = $request->input('date') ?: today()->toDateString();

        Attendance::updateOrCreate(
            [
                'student_id'      => $request->student_id,
                'attendance_date' => $date,
            ],
            ['status' => $status]
        );

        $messages = [
            'present' => 'Student marked as Present.',
            'absent'  => 'Student marked as Absent.',
            'leave'   => 'Student marked as on Leave.',
        ];

        return response()->json([
            'success' => true,
            'message' => $messages[$status] ?? 'Attendance updated.',
            'status'  => $status,
        ]);
    }

    public function create() {}
    public function store(Request $request) {}
    public function show($id) {}
    public function edit($id) {}
    public function update(Request $request, $id) {}
    public function destroy($id) {}
}
