<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentAdmissionSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $studentIds = DB::table('students')->pluck('id')->all();
        $classIds = DB::table('classes')->pluck('id')->all();
        $sectionIds = DB::table('sections')->pluck('id')->all();

        if (empty($studentIds)) {
            $this->command->warn('⚠ No students found. Run StudentSeeder first.');
            return;
        }

        $admissions = [
            [
                'student_id' => $studentIds[0] ?? null,
                'school_name_required' => 'The City School, Karachi',
                'applied_class_id' => $classIds[0] ?? null,
                'admission_no' => 'ADM-2024-0001',
                'class_id' => $classIds[0] ?? null,
                'section_id' => $sectionIds[0] ?? null,

                'admission_date' => '2024-03-15',
                'status' => 'approved',
                'remarks' => null,
                'approved_by_officer' => 'Mr. John Doe',
                'approved_by_head' => 'Dr. Sarah Khan',
            ],
            [
                'student_id' => $studentIds[1] ?? null,
                'school_name_required' => 'Lahore Grammar School, Lahore',
                'applied_class_id' => $classIds[1] ?? null,
                'admission_no' => 'ADM-2024-0002',
                'class_id' => $classIds[1] ?? null,
                'section_id' => $sectionIds[1] ?? null,

                'admission_date' => '2024-03-20',
                'status' => 'approved',
                'remarks' => null,
                'approved_by_officer' => 'Mr. John Doe',
                'approved_by_head' => 'Dr. Sarah Khan',
            ],
            [
                'student_id' => $studentIds[2] ?? null,
                'school_name_required' => 'Beaconhouse School System',
                'applied_class_id' => $classIds[2] ?? null,
                'admission_no' => 'ADM-2024-0003',
                'class_id' => $classIds[2] ?? null,
                'section_id' => $sectionIds[2] ?? null,

                'admission_date' => '2024-03-25',
                'status' => 'pending',
                'remarks' => 'Waiting for document verification',
                'approved_by_officer' => null,
                'approved_by_head' => null,
            ],
            [
                'student_id' => $studentIds[3] ?? null,
                'school_name_required' => 'Roots Millennium School',
                'applied_class_id' => $classIds[0] ?? null,
                'admission_no' => 'ADM-2024-0004',
                'class_id' => $classIds[0] ?? null,
                'section_id' => $sectionIds[0] ?? null,

                'admission_date' => '2024-04-01',
                'status' => 'approved',
                'remarks' => null,
                'approved_by_officer' => 'Mr. John Doe',
                'approved_by_head' => 'Dr. Sarah Khan',
            ],
            [
                'student_id' => $studentIds[4] ?? null,
                'school_name_required' => 'Army Public School',
                'applied_class_id' => $classIds[1] ?? null,
                'admission_no' => 'ADM-2024-0005',
                'class_id' => $classIds[1] ?? null,
                'section_id' => $sectionIds[1] ?? null,

                'admission_date' => '2024-04-05',
                'status' => 'rejected',
                'remarks' => 'Incomplete documentation',
                'approved_by_officer' => 'Mr. John Doe',
                'approved_by_head' => null,
            ],
            [
                'student_id' => $studentIds[5] ?? null,
                'school_name_required' => 'The Educators',
                'applied_class_id' => $classIds[2] ?? null,
                'admission_no' => 'ADM-2024-0006',
                'class_id' => $classIds[2] ?? null,
                'section_id' => $sectionIds[2] ?? null,
                
                'admission_date' => '2024-04-10',
                'status' => 'pending',
                'remarks' => 'Under review',
                'approved_by_officer' => null,
                'approved_by_head' => null,
            ],
        ];

        foreach ($admissions as $admission) {
            // Skip if admission_no already exists
            if (DB::table('student_admissions')->where('admission_no', $admission['admission_no'])->exists()) {
                continue;
            }

            DB::table('student_admissions')->insert([
                ...$admission,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $this->command->info('✔ StudentAdmissionSeeder: ' . count($admissions) . ' admission records inserted.');
    }
}
