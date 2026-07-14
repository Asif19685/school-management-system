<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Services\PromotionService;
use Illuminate\Support\Facades\Auth;

class PromotionController extends Controller
{
    protected $promotionService;

    public function __construct(PromotionService $promotionService)
    {
        $this->promotionService = $promotionService;
    }

    public function index()
    {
        $classes = SchoolClass::all();
        $sections = Section::all();
        return view('modules.promotions.index', compact('classes', 'sections'));
    }

    public function fetchStudents(Request $request)
    {
        try {
            $classId   = $request->input('class_id');
            $sectionId = $request->input('section_id');

            if (!$classId) {
                return response()->json(['data' => []]);
            }

            // Pass null instead of 'all' so service does not filter by section
            $sectionFilter = ($sectionId && $sectionId !== 'all') ? $sectionId : null;

            $admissions = $this->promotionService->getStudentsForPromotion($classId, $sectionFilter);

            $data = [];
            foreach ($admissions as $admission) {
                $student = $admission->student;
                if (!$student) continue;

                $image = $student->studentImage
                    ? asset('storage/' . $student->studentImage->image_path)
                    : asset('assets/images/default-avatar.png');

                // roll_no is stored in sections table not student_admissions
                $rollNo = $admission->section ? ($admission->section->roll_no ?? '-') : '-';

                $data[] = [
                    'id'              => $student->id,
                    'admission_no'    => $admission->admission_no ?? '-',
                    'roll_no'         => $rollNo,
                    'student_name'    => trim($student->first_name . ' ' . $student->last_name),
                    'image'           => $image,
                    'current_class'   => $admission->schoolClass ? $admission->schoolClass->class_name : '-',
                    'current_section' => $admission->section ? $admission->section->section_name : '-',
                ];
            }

            return response()->json(['data' => $data]);

        } catch (\Exception $e) {
            return response()->json(['data' => [], 'error' => $e->getMessage()], 500);
        }
    }

    public function promote(Request $request)
    {
        $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => 'integer',
            'from_class_id' => 'required|integer',
            'to_class_id' => 'required|integer',
            'academic_year' => 'required|string',
        ]);

        $userId = Auth::id() ?? 1; 

        $result = $this->promotionService->bulkPromoteStudents(
            $request->student_ids,
            $request->from_class_id,
            $request->to_class_id,
            $request->from_section_id,
            $request->to_section_id,
            $request->academic_year,
            'Promoted',
            null,
            $userId
        );

        if (empty($result['failed'])) {
            return response()->json([
                'success' => true,
                'message' => count($request->student_ids) . ' students have been successfully promoted!'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Some students failed to promote. Errors: ' . json_encode($result['failed'])
        ], 500);
    }
    
    /**
     * Show student history
     */
    public function history($studentId)
    {
        $history = $this->promotionService->getStudentHistory($studentId);
        return view('modules.promotions.history', compact('history'));
    }

    /**
     * Return sections for a given class (for dynamic dropdown)
     */
    public function getSectionsByClass($classId)
    {
        $sections = Section::where('class_id', $classId)->get(['id', 'section_name']);
        return response()->json($sections);
    }
}
