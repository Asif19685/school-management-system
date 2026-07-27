<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SubjectsController extends Controller
{
    /** Subject list page */
    public function index(): \Illuminate\View\View
    {
        $classes = SchoolClass::orderBy('class_name')->get();
        return view('modules.subjects.index', compact('classes'));
    }

    /** AJAX: Sab subjects ya ek class ke subjects (Server-side DataTables) */
    public function getData(Request $request)
    {
        $query = Subject::with('schoolClass')->select('subjects.*');

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        return \Yajra\DataTables\Facades\DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('subject_name', function ($subject) {
                return '<span class="subject-badge"><i class="bi bi-journal-text"></i> ' . e($subject->subject_name) . '</span>';
            })
            ->addColumn('subject_code', function ($subject) {
                return '<code class="text-muted">' . e($subject->subject_code ?? '—') . '</code>';
            })
            ->addColumn('class_name', function ($subject) {
                return '<span class="class-badge">' . e($subject->schoolClass?->class_name ?? '—') . '</span>';
            })
            ->addColumn('created_at', function ($subject) {
                return '<span class="text-muted" style="font-size:0.82rem;">' . ($subject->created_at ? $subject->created_at->format('d M Y') : '') . '</span>';
            })
            ->addColumn('actions', function ($subject) {
                $code = $subject->subject_code ?? '';
                return '
                    <button class="action-btn btn-edit-sub me-1"
                        onclick="openEdit(' . $subject->id . ', \'' . addslashes($subject->subject_name) . '\', \'' . addslashes($code) . '\', ' . $subject->class_id . ', ' . $subject->total_marks . ', ' . $subject->pass_marks . ')">
                        <i class="bi bi-pencil"></i> Edit
                    </button>
                    <button class="action-btn btn-del-sub"
                        onclick="openDelete(' . $subject->id . ', \'' . addslashes($subject->subject_name) . '\')">
                        <i class="bi bi-trash3"></i> Delete
                    </button>
                ';
            })
            ->rawColumns(['subject_name', 'subject_code', 'class_name', 'created_at', 'actions'])
            ->make(true);
    }

    /** AJAX: Kisi class ke subjects fetch karo (Exams ke liye) */
    public function getByClass(int $classId): JsonResponse
    {
        $subjects = Subject::where('class_id', $classId)
            ->orderBy('subject_name')
            ->get(['id', 'subject_name', 'subject_code', 'total_marks', 'pass_marks']);

        return response()->json($subjects);
    }

    /** Naya subject save karo */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'subject_name' => 'required|string|max:100',
            'class_id'     => 'required|exists:classes,id',
            'subject_code' => 'nullable|string|max:20',
            'total_marks'  => 'required|integer|min:1|max:1000',
            'pass_marks'   => 'required|integer|min:1',
        ]);

        // Same class mein duplicate subject check
        $exists = Subject::where('class_id', $request->class_id)
            ->where('subject_name', $request->subject_name)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Yeh subject is class mein pehle se maujood hai!'
            ], 422);
        }

        $subject = Subject::create([
            'subject_name' => trim($request->subject_name),
            'class_id'     => $request->class_id,
            'subject_code' => $request->subject_code ? trim($request->subject_code) : null,
            'total_marks'  => $request->total_marks,
            'pass_marks'   => $request->pass_marks,
        ]);

        $subject->load('schoolClass');

        return response()->json([
            'success' => true,
            'message' => 'Subject successfully add ho gaya!',
            'subject' => [
                'id'           => $subject->id,
                'subject_name' => $subject->subject_name,
                'subject_code' => $subject->subject_code ?? '—',
                'class_name'   => $subject->schoolClass?->class_name ?? '—',
                'class_id'     => $subject->class_id,
                'total_marks'  => $subject->total_marks,
                'pass_marks'   => $subject->pass_marks,
                'created_at'   => $subject->created_at->format('d M Y'),
            ],
        ]);
    }

    /** Subject update karo */
    public function update(Request $request, int $id): JsonResponse
    {
        $subject = Subject::findOrFail($id);

        $request->validate([
            'subject_name' => 'required|string|max:100',
            'class_id'     => 'required|exists:classes,id',
            'subject_code' => 'nullable|string|max:20',
            'total_marks'  => 'required|integer|min:1|max:1000',
            'pass_marks'   => 'required|integer|min:1',
        ]);

        // Duplicate check (apne siwa)
        $exists = Subject::where('class_id', $request->class_id)
            ->where('subject_name', $request->subject_name)
            ->where('id', '!=', $id)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Yeh subject is class mein pehle se maujood hai!'
            ], 422);
        }

        $subject->update([
            'subject_name' => trim($request->subject_name),
            'class_id'     => $request->class_id,
            'subject_code' => $request->subject_code ? trim($request->subject_code) : null,
            'total_marks'  => $request->total_marks,
            'pass_marks'   => $request->pass_marks,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Subject successfully update ho gaya!',
        ]);
    }

    /** Subject delete karo */
    public function destroy(int $id): JsonResponse
    {
        $subject = Subject::findOrFail($id);
        $subject->delete();

        return response()->json([
            'success' => true,
            'message' => 'Subject delete ho gaya!',
        ]);
    }
}
