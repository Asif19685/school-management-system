<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentGuardianSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $classIds = DB::table('classes')->pluck('id')->all();
        $sectionIds = DB::table('sections')->pluck('id')->all();

        $guardianIds = [];
        for ($i = 1; $i <= 8; $i++) {
            $guardianIds[] = DB::table('guardians')->insertGetId([
                'father_name' => "Father {$i}",
                'mother_name' => "Mother {$i}",
                'phone' => '030012345' . str_pad((string) $i, 2, '0', STR_PAD_LEFT),
                'occupation' => 'Private Job',
                'address' => "Street {$i}, City",
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        foreach (range(1, 12) as $i) {
            DB::table('students')->insert([
                'admission_no' => 'ADM-' . str_pad((string) $i, 4, '0', STR_PAD_LEFT),
                'first_name' => "Student{$i}",
                'last_name' => 'Khan',
                'gender' => $i % 2 === 0 ? 'female' : 'male',
                'dob' => now()->subYears(8 + ($i % 3))->toDateString(),
                'phone' => '031112345' . str_pad((string) $i, 2, '0', STR_PAD_LEFT),
                'email' => "student{$i}@sms.local",
                'address' => "Area {$i}, City",
                'class_id' => $classIds[array_rand($classIds)],
                'section_id' => $sectionIds[array_rand($sectionIds)],
                'guardian_id' => $guardianIds[array_rand($guardianIds)],
                'admission_date' => now()->subDays(rand(30, 180))->toDateString(),
                'status' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
