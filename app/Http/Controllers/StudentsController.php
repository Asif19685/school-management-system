<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\StudentAdmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class StudentsController extends Controller
{
    /**
     * Students list page – only approved students
     */
    public function index(): View
    {
        $classes = SchoolClass::all();
        return view('modules.students.index', compact('classes'));
    }

public function getStudentsData(Request $request)
{
    $query = StudentAdmission::with([
        'student.guardian',
        'student.studentImage',
        'schoolClass',
        'section',
        'appliedClass',
    ])
    ->where('status', 'approved')
    ->select('student_admissions.*');

    // Global search
    if ($request->has('search') && !empty($request->search['value'])) {
        $searchValue = $request->search['value'];

        $query->where(function ($q) use ($searchValue) {
            $q->where('admission_no', 'LIKE', "%{$searchValue}%")
              ->orWhere('admission_date', 'LIKE', "%{$searchValue}%")
              ->orWhereHas('student', function ($sq) use ($searchValue) {
                  $sq->where('first_name', 'LIKE', "%{$searchValue}%")
                     ->orWhere('last_name', 'LIKE', "%{$searchValue}%")
                     ->orWhere('b_form_no', 'LIKE', "%{$searchValue}%")
                     ->orWhere('gender', 'LIKE', "%{$searchValue}%");
              })
              ->orWhereHas('schoolClass', function ($sq) use ($searchValue) {
                  $sq->where('class_name', 'LIKE', "%{$searchValue}%");
              })
              ->orWhereHas('section', function ($sq) use ($searchValue) {
                  $sq->where('section_name', 'LIKE', "%{$searchValue}%");
              });
        });
    }

    return DataTables::of($query)
        ->addIndexColumn()

        // ── Filter Columns ─────────────────────────────────────────────
        ->filterColumn('admission_no', fn($q, $kw) => $q->where('admission_no', 'LIKE', "%{$kw}%"))
        ->filterColumn('student_name', function ($q, $kw) {
            $q->whereHas('student', fn($sq) =>
                $sq->where('first_name', 'LIKE', "%{$kw}%")
                   ->orWhere('last_name', 'LIKE', "%{$kw}%")
            );
        })
        ->filterColumn('gender', function ($q, $kw) {
            $q->whereHas('student', fn($sq) => $sq->where('gender', 'LIKE', "%{$kw}%"));
        })
        ->filterColumn('class', function ($q, $kw) {
            $q->whereHas('schoolClass', fn($sq) => $sq->where('class_name', 'LIKE', "%{$kw}%"));
        })
        ->filterColumn('section', function ($q, $kw) {
            $q->whereHas('section', fn($sq) => $sq->where('section_name', 'LIKE', "%{$kw}%"));
        })

        // ── Add Columns ────────────────────────────────────────────────
        ->addColumn('student_image', function ($row) {
            if ($row->student && $row->student->studentImage) {
                $url = asset($row->student->studentImage->image_path);
            } else {
                $url = asset('images/default-avatar.png');
            }
            return '<img src="' . $url . '" class="rounded-circle" width="40" height="40" style="object-fit:cover;">';
        })
        ->addColumn('student_name', function ($row) {
            return $row->student
                ? trim(($row->student->first_name ?? '') . ' ' . ($row->student->last_name ?? ''))
                : 'N/A';
        })
        ->addColumn('admission_no',  fn($row) => $row->admission_no ?? 'N/A')
        ->addColumn('b_form_no',     fn($row) => $row->student->b_form_no ?? 'N/A')
        ->addColumn('class',         fn($row) => $row->schoolClass->class_name ?? 'N/A')
        ->addColumn('section',       fn($row) => $row->section->section_name ?? 'N/A')
        ->addColumn('roll_no',       fn($row) => $row->roll_no ?? 'N/A')
        ->addColumn('guardian_name', fn($row) => $row->student->guardian->father_name ?? 'N/A')
        ->addColumn('guardian_phone',fn($row) => $row->student->guardian->phone ?? 'N/A')

        ->addColumn('gender', function ($row) {
            $gender = strtolower($row->student->gender ?? '');
            $map = [
                'male'   => '<span class="badge bg-info text-dark">Male</span>',
                'female' => '<span class="badge bg-warning text-dark">Female</span>',
                'other'  => '<span class="badge bg-secondary">Other</span>',
            ];
            return $map[$gender] ?? '<span class="badge bg-secondary">N/A</span>';
        })

        // ════════════════════════════════════════════════════════════════
        // ➤ ACTIONS COLUMN - View Button
        // ════════════════════════════════════════════════════════════════
        ->addColumn('actions', function ($row) {
            return '
                 <button class="btn btn-sm btn-outline-info view-student-btn-no-fee me-1"
                data-id="' . $row->id . '"
                title="View Profile">
            <i class="bi bi-eye"></i> View Profile
        </button>
            ';
        })

        ->rawColumns(['student_image', 'gender', 'actions'])
        ->make(true);
}
    // ── Unused stubs (keep for resource route compatibility) ─────────────
    public function create() {}
    public function store(Request $request) {}
    public function edit($id) {}
    public function update(Request $request, $id) {}
    public function destroy($id) {}
}
