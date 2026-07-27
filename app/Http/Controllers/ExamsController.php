<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Student;
use App\Models\SubjectResult;
use App\Models\FinalResult;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class ExamsController extends Controller
{
    /**
     * Display a listing of exams.
     */
    public function index()
    {
        $classes = SchoolClass::all();
        return view('modules.exams.index', compact('classes'));
    }

    /**
     * Get data for datatable
     */
    public function getExamsData(Request $request)
    {
        $query = Exam::with('schoolClass')->latest();

        return Datatables::of($query)
            ->addIndexColumn()
            ->addColumn('class', function($row) {
                return $row->schoolClass ? $row->schoolClass->class_name : '<span class="badge bg-secondary">All Classes</span>';
            })
            ->addColumn('exam_type_badge', function($row) {
                $colors = [
                    'monthly_test' => 'info',
                    'mid_term'     => 'warning',
                    'final_term'   => 'danger',
                ];
                $color = $colors[$row->exam_type] ?? 'secondary';
                $label = ucwords(str_replace('_', ' ', $row->exam_type));
                return '<span class="badge bg-' . $color . '">' . $label . '</span>';
            })
            ->addColumn('action', function($row) {
                $date = $row->exam_date ? $row->exam_date->format('Y-m-d') : '';
                $editBtn         = '<button class="btn btn-sm btn-info edit-btn me-1" data-id="'.$row->id.'" data-name="'.e($row->exam_name).'" data-type="'.$row->exam_type.'" data-year="'.$row->academic_year.'" data-class="'.$row->class_id.'" data-date="'.$date.'" title="Edit"><i class="fas fa-edit"></i> Edit</button>';
                $delBtn          = '<button class="btn btn-sm btn-danger delete-btn me-1" data-id="'.$row->id.'" title="Delete"><i class="fas fa-trash"></i> Delete</button>';
                $subjectMarksBtn = '<a href="'.route('exams.subject-marks', ['exam_id' => $row->id]).'" class="btn btn-sm btn-warning me-1" title="Enter Subject Marks"><i class="fas fa-pencil-alt"></i> Marks</a>';
                $finalResultsBtn = '<a href="'.route('exams.final-results', ['exam_id' => $row->id]).'" class="btn btn-sm btn-success" title="Final Results"><i class="fas fa-award"></i> Results</a>';
                return $editBtn . $delBtn . $subjectMarksBtn . $finalResultsBtn;
            })
            ->rawColumns(['class', 'exam_type_badge', 'action'])
            ->make(true);
    }

    /**
     * Store a newly created exam
     */
    public function store(Request $request)
    {
        $request->validate([
            'exam_name' => 'required|string|max:255',
            'exam_type' => 'required|string|max:20',
            'academic_year' => 'required|integer',
            'class_id' => 'nullable|exists:classes,id',
            'exam_date' => 'nullable|date',
        ]);

        Exam::create($request->all());

        return response()->json(['success' => true, 'message' => 'Exam created successfully.']);
    }

    /**
     * Update the specified exam
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'exam_name' => 'required|string|max:255',
            'exam_type' => 'required|string|max:20',
            'academic_year' => 'required|integer',
            'class_id' => 'nullable|exists:classes,id',
            'exam_date' => 'nullable|date',
        ]);

        $exam = Exam::findOrFail($id);
        $exam->update($request->all());

        return response()->json(['success' => true, 'message' => 'Exam updated successfully.']);
    }

    /**
     * Remove the specified exam
     */
    public function destroy($id)
    {
        $exam = Exam::findOrFail($id);
        $exam->delete();

        return response()->json(['success' => true, 'message' => 'Exam deleted successfully.']);
    }

    // ==========================================
    // SUBJECT-WISE MARKS ENTRY
    // ==========================================

    public function subjectMarks(Request $request)
    {
        $exams = Exam::orderBy('academic_year', 'desc')->orderBy('id', 'desc')->get();
        $classes = SchoolClass::orderBy('id')->get();
        $selectedExamId = $request->query('exam_id');
        $selectedClassId = null;
        $subjects = collect();

        return view('modules.exams.subject-marks', compact('exams', 'classes', 'subjects', 'selectedExamId', 'selectedClassId'));
    }

    public function fetchSubjectMarks(Request $request)
    {
        $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
        ]);

        $students = Student::whereHas('admission', function ($query) use ($request) {
            $query->where('class_id', $request->class_id)
                  ->where('status', 'approved');
        })->orderBy('first_name')->get();

        $results = SubjectResult::where('exam_id', $request->exam_id)
            ->where('class_id', $request->class_id)
            ->where('subject_id', $request->subject_id)
            ->get()
            ->keyBy('student_id');

        $html = view('modules.exams.partials.subject-marks-table', compact('students', 'results'))->render();

        return response()->json(['success' => true, 'html' => $html]);
    }

    public function saveSubjectMarks(Request $request)
    {
        $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'marks' => 'required|array',
            'marks.*.student_id' => 'required|exists:students,id',
            'marks.*.total_marks' => 'required|integer|min:0',
            'marks.*.obtained_marks' => 'required|integer|min:0',
            'marks.*.status' => 'required|in:pass,fail',
        ]);

        foreach ($request->marks as $markData) {
            SubjectResult::updateOrCreate(
                [
                    'exam_id' => $request->exam_id,
                    'class_id' => $request->class_id,
                    'subject_id' => $request->subject_id,
                    'student_id' => $markData['student_id']
                ],
                [
                    'total_marks' => $markData['total_marks'],
                    'obtained_marks' => $markData['obtained_marks'],
                    'grade' => $markData['grade'] ?? null,
                    'status' => $markData['status'],
                    'remarks' => $markData['remarks'] ?? null,
                ]
            );
        }

        return response()->json(['success' => true, 'message' => 'Marks saved successfully.']);
    }

    // ==========================================
    // FINAL RESULTS ENTRY
    // ==========================================

    public function finalResults(Request $request)
    {
        $exams = Exam::orderBy('academic_year', 'desc')->orderBy('id', 'desc')->get();
        $classes = SchoolClass::orderBy('id')->get();
        $selectedExamId = $request->query('exam_id');
        $selectedClassId = null;

        return view('modules.exams.final-results', compact('exams', 'classes', 'selectedExamId', 'selectedClassId'));
    }

    /**
     * AJAX: Return subjects for a given class
     */
    public function getSubjectsByClass($classId)
    {
        $subjects = Subject::where('class_id', $classId)->get(['id', 'subject_name']);
        return response()->json($subjects);
    }

    public function fetchFinalResults(Request $request)
    {
        $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'class_id' => 'required|exists:classes,id',
        ]);

        $students = Student::whereHas('admission', function ($query) use ($request) {
            $query->where('class_id', $request->class_id)
                  ->where('status', 'approved');
        })->orderBy('first_name')->get();

        $results = FinalResult::where('exam_id', $request->exam_id)
            ->where('class_id', $request->class_id)
            ->get()
            ->keyBy('student_id');

        $subjects = Subject::where('class_id', $request->class_id)->orderBy('subject_name')->get();

        $subjectResults = SubjectResult::where('exam_id', $request->exam_id)
            ->where('class_id', $request->class_id)
            ->get()
            ->groupBy('student_id');

        $html = view('modules.exams.partials.final-results-table', compact('students', 'results', 'subjects', 'subjectResults'))->render();

        return response()->json(['success' => true, 'html' => $html]);
    }

    public function saveFinalResults(Request $request)
    {
        $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'class_id' => 'required|exists:classes,id',
            'results' => 'required|array',
            'results.*.student_id' => 'required|exists:students,id',
            'results.*.grand_total_marks' => 'required|integer|min:0',
            'results.*.grand_obtained_marks' => 'required|integer|min:0',
            'results.*.percentage' => 'required|numeric|min:0|max:100',
            'results.*.final_status' => 'required|in:pass,fail',
        ]);

        foreach ($request->results as $resultData) {
            FinalResult::updateOrCreate(
                [
                    'exam_id' => $request->exam_id,
                    'class_id' => $request->class_id,
                    'student_id' => $resultData['student_id']
                ],
                [
                    'grand_total_marks' => $resultData['grand_total_marks'],
                    'grand_obtained_marks' => $resultData['grand_obtained_marks'],
                    'percentage' => $resultData['percentage'],
                    'final_grade' => $resultData['final_grade'] ?? null,
                    'final_status' => $resultData['final_status'],
                    'position' => $resultData['position'] ?? null,
                    'remarks' => $resultData['remarks'] ?? null,
                ]
            );
        }

        return response()->json(['success' => true, 'message' => 'Final results saved successfully.']);
    }

    // ==========================================
    // RESULT CARDS - FIXED
    // ==========================================

    public function resultCards(Request $request)
    {
        $exams = Exam::orderBy('academic_year', 'desc')->orderBy('id', 'desc')->get();
        $classes = SchoolClass::orderBy('id')->get();
        $selectedExamId = $request->query('exam_id');

        return view('modules.exams.result-cards', compact('exams', 'classes', 'selectedExamId'));
    }

    /**
     * Fetch students with their final results for result cards
     */
// public function fetchResultCards(Request $request)
// {
//     try {
//         $request->validate([
//             'exam_id' => 'required|exists:exams,id',
//             'class_id' => 'required|exists:classes,id',
//         ]);

//         $examId = $request->exam_id;
//         $classId = $request->class_id;

//         // ✅ 'finalResult' relation use karein (jo Student model mein hai)
//         $students = Student::whereHas('admission', function ($query) use ($classId) {
//             $query->where('class_id', $classId)
//                   ->where('status', 'approved');
//         })
//         ->with(['finalResult' => function ($query) use ($examId, $classId) {
//             $query->where('exam_id', $examId)
//                   ->where('class_id', $classId);
//         }])
//         ->with(['admission.schoolClass', 'guardian'])
//         ->orderBy('first_name')
//         ->get();

//         // Debug log
//         Log::info('Result Cards Fetch:', [
//             'exam_id' => $examId,
//             'class_id' => $classId,
//             'student_count' => $students->count()
//         ]);

//         $html = view('modules.exams.partials.result-cards-table', compact('students', 'examId', 'classId'))->render();

//         return response()->json([
//             'success' => true,
//             'html' => $html,
//             'count' => $students->count()
//         ]);

//     } catch (\Exception $e) {
//         Log::error('Error in fetchResultCards: ' . $e->getMessage());
//         return response()->json([
//             'success' => false,
//             'message' => 'Error loading students: ' . $e->getMessage()
//         ], 500);
//     }
// }
public function fetchResultCards(Request $request)
{
    try {
        $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'class_id' => 'required|exists:classes,id',
        ]);

        $examId = $request->exam_id;
        $classId = $request->class_id;

        // ✅ Sirf un students ko fetch karein jin ke results hain
        $students = Student::whereHas('admission', function ($query) use ($classId) {
            $query->where('class_id', $classId)
                  ->where('status', 'approved');
        })
        ->whereHas('finalResult', function ($query) use ($examId, $classId) {
            $query->where('exam_id', $examId)
                  ->where('class_id', $classId);
        })
        ->with(['finalResult' => function ($query) use ($examId, $classId) {
            $query->where('exam_id', $examId)
                  ->where('class_id', $classId);
        }])
        ->with(['admission.schoolClass', 'guardian'])
        ->orderBy('first_name')
        ->get();

        $html = view('modules.exams.partials.result-cards-table', compact('students', 'examId', 'classId'))->render();

        return response()->json([
            'success' => true,
            'html' => $html,
            'count' => $students->count()
        ]);

    } catch (\Exception $e) {
        Log::error('Error in fetchResultCards: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error loading students: ' . $e->getMessage()
        ], 500);
    }
}
    /**
     * Print individual result card
     */
    public function printResultCard($exam_id, $student_id)
    {
        // Get exam with class
        $exam = Exam::with('schoolClass')->findOrFail($exam_id);

        // Get student with admission, schoolClass, and guardian
        $student = Student::with(['admission.schoolClass', 'guardian'])->findOrFail($student_id);

        // Get the class_id from student's admission
        $class_id = $student->admission->schoolClass->id ?? null;

        if (!$class_id) {
            return back()->with('error', 'Student is not assigned to any class.');
        }

        // Get subject results with subject - filter by class_id as well
        $subjectResults = SubjectResult::with('subject')
            ->where('exam_id', $exam_id)
            ->where('student_id', $student_id)
            ->where('class_id', $class_id)
            ->get();

        // Get final result - filter by class_id as well
        $finalResult = FinalResult::where('exam_id', $exam_id)
            ->where('student_id', $student_id)
            ->where('class_id', $class_id)
            ->first();

        // Auto-calculate from subject results if final result not yet saved
        $calculatedTotal    = $subjectResults->sum('total_marks');
        $calculatedObtained = $subjectResults->sum('obtained_marks');
        $calculatedPct      = $calculatedTotal > 0
            ? number_format(($calculatedObtained / $calculatedTotal) * 100, 2)
            : '0.00';

        $className = $student->admission->schoolClass->class_name ?? 'N/A';

        // Debug: Check if data exists
        Log::info('Subject Results Count: ' . $subjectResults->count());
        Log::info('Final Result: ' . ($finalResult ? 'Found' : 'Not Found'));

        return view('modules.exams.result-card-print', compact(
            'exam', 'student', 'subjectResults', 'finalResult', 'className',
            'calculatedTotal', 'calculatedObtained', 'calculatedPct'
        ));
    }
}
