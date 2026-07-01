<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SectionSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        // Classes ki IDs database se nikal rahe hain
        $classIds = DB::table('classes')->pluck('id')->all();

        $sections = ['A', 'B', 'C'];
        $count = 0;

        foreach ($classIds as $classId) {
            // Har nayi class ke shuru hone par roll number dobara 1 se shuru hoga
            // Agar aap chahte hain ke Roll No section-wise alag ho (A ka 1, B ka 2, C ka 3):
            foreach ($sections as $index => $sectionName) {

                // $index 0 se shuru hota hai, isliye +1 kiya taake roll_no 1, 2, 3 bane
                $rollNo = $index + 1;

                DB::table('sections')->insert([
                    'class_id'     => $classId,
                    'section_name' => $sectionName,
                    'roll_no'      => $rollNo, // Yahan dynamic roll number ja raha hai
                    'created_at'   => $now,
                    'updated_at'   => $now,
                ]);
                $count++;
            }
        }

        $this->command->info("✔ SectionSeeder: {$count} sections inserted.");
    }
}
