<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Tables are truncated and seeded in dependency order:
     * 1. Independent tables first (classes, courses, users)
     * 2. Then tables with foreign keys (sections, subjects, guardians, students...)
     * 3. Finally operation tables (attendance, fees, exams, library, notifications...)
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate all tables in reverse dependency order
        $tables = [
            'issued_books',
            'library_books',
            'notifications',
            'visitors',
            'fee_payments',
            'fees',
            'exam_results',
            'exams',
            'attendance',
            'student_admissions',  // Note: plural as per your migration
            'students',
            'teachers',
            'guardians',
            'sections',
            'subjects',
            'courses',
            'classes',
            'disabilities',  // Added disabilities table
            'users',
        ];

        foreach ($tables as $table) {
            if (DB::table($table)->exists()) {
                DB::table($table)->truncate();
                $this->command->info("   Truncated: {$table}");
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('');
        $this->command->info('🗑  All tables truncated.');
        $this->command->info('');

        // Seed in correct dependency order
        $this->call([
            // 1. Basic data (no dependencies)
            CoreUserSeeder::class,
            DisabilitySeeder::class,  // Disabilities pehle because students need it

            // 2. Academic structure (classes → sections, courses → subjects)
            ClassSeeder::class,
            SectionSeeder::class,
            CourseSeeder::class,
            SubjectSeeder::class,

            // 3. People (guardians → students, teachers)
            GuardianSeeder::class,
            StudentSeeder::class,
            TeacherSeeder::class,

            // 4. Operations (depend on students, classes, exams, etc.)
            AttendanceSeeder::class,
            ExamSeeder::class,
            ExamResultSeeder::class,
            FeeSeeder::class,
            FeePaymentSeeder::class,
            VisitorSeeder::class,
            NotificationSeeder::class,

            // 5. Library
            LibraryBookSeeder::class,
            IssuedBookSeeder::class,

            // 6. New Admissions table
            StudentAdmissionSeeder::class,
        ]);

        $this->command->info('');
        $this->command->info('✅ All seeders completed successfully!');
    }
}
