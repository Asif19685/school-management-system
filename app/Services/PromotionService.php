<?php

namespace App\Services;

use App\Models\Student;
use App\Models\StudentPromotion;
use App\Models\StudentAdmission;
use Illuminate\Support\Facades\DB;

class PromotionService
{
    /**
     * Promote a single student
     */
    public function promoteStudent(
        $studentId,
        $fromClassId,
        $toClassId,
        $fromSectionId = null,
        $toSectionId = null,
        $academicYear = null,
        $status = 'Promoted',
        $remarks = null,
        $createdBy = null
    ) {
        return DB::transaction(function () use (
            $studentId,
            $fromClassId,
            $toClassId,
            $fromSectionId,
            $toSectionId,
            $academicYear,
            $status,
            $remarks,
            $createdBy
        ) {
            $student = Student::findOrFail($studentId);
            
            // Set default values
            $academicYear = $academicYear ?? $this->getCurrentAcademicYear();
            $createdBy = $createdBy ?? auth()->id() ?? 1;
            
            // 1. Update student_admissions
            $admission = StudentAdmission::where('student_id', $studentId)->first();
            
            if ($admission) {
                $admission->update([
                    'class_id' => $toClassId,
                    'section_id' => $toSectionId,
                    'academic_year' => $academicYear,
                ]);
            }
            
            // 2. Create promotion history record
            $promotion = StudentPromotion::create([
                'student_id' => $studentId,
                'from_class_id' => $fromClassId,
                'to_class_id' => $toClassId,
                'from_section_id' => $fromSectionId,
                'to_section_id' => $toSectionId,
                'academic_year' => $academicYear,
                'promotion_date' => now()->toDateString(),
                'status' => $status,
                'remarks' => $remarks,
                'created_by' => $createdBy,
            ]);
            
            return $promotion;
        });
    }

    /**
     * Bulk promote students
     */
    public function bulkPromoteStudents(
        array $studentIds,
        $fromClassId,
        $toClassId,
        $fromSectionId = null,
        $toSectionId = null,
        $academicYear = null,
        $status = 'Promoted',
        $remarks = null,
        $createdBy = null
    ) {
        $results = [];
        
        DB::transaction(function () use (
            $studentIds,
            $fromClassId,
            $toClassId,
            $fromSectionId,
            $toSectionId,
            $academicYear,
            $status,
            $remarks,
            $createdBy,
            &$results
        ) {
            foreach ($studentIds as $studentId) {
                try {
                    $result = $this->promoteStudent(
                        $studentId,
                        $fromClassId,
                        $toClassId,
                        $fromSectionId,
                        $toSectionId,
                        $academicYear,
                        $status,
                        $remarks,
                        $createdBy
                    );
                    
                    $results['success'][] = $studentId;
                } catch (\Exception $e) {
                    $results['failed'][$studentId] = $e->getMessage();
                }
            }
        });
        
        return $results;
    }

    /**
     * Get student promotion history
     */
    public function getStudentHistory($studentId)
    {
        $student = Student::with([
            'admission.schoolClass',
            'admission.appliedClass',
            'admission.section',
            'promotions.fromClass',
            'promotions.toClass',
            'promotions.fromSection',
            'promotions.toSection',
            'promotions.createdBy'
        ])->findOrFail($studentId);
        
        return [
            'student' => $student,
            'current_class' => $student->admission?->schoolClass,
            'current_section' => $student->admission?->section,
            'admission_details' => $student->admission,
            'promotion_history' => $student->promotions,
        ];
    }

    /**
     * Get current academic year
     */
    private function getCurrentAcademicYear()
    {
        $year = now()->year;
        $nextYear = $year + 1;
        
        // If current month is Jan-Jun, academic year is previous-year-current-year
        if (now()->month <= 6) {
            return ($year - 1) . '-' . $year;
        }
        
        // If current month is Jul-Dec, academic year is current-year-next-year
        return $year . '-' . $nextYear;
    }

    /**
     * Get students by class for promotion
     */
    public function getStudentsForPromotion($classId, $sectionId = null)
    {
        $query = StudentAdmission::with(['student.studentImage', 'schoolClass', 'section'])
            ->where('class_id', $classId)
            ->where('status', 'approved');
            
        if ($sectionId && $sectionId !== 'all') {
            $query->where('section_id', $sectionId);
        }
        
        return $query->get();
    }
}
