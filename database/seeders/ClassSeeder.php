<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $classes = [
            ['class_name' => 'Nursery',   'description' => 'Nursery level'],
            ['class_name' => 'KG',        'description' => 'Kindergarten'],
            ['class_name' => 'Class 1',   'description' => 'Grade 1'],
            ['class_name' => 'Class 2',   'description' => 'Grade 2'],
            ['class_name' => 'Class 3',   'description' => 'Grade 3'],
            ['class_name' => 'Class 4',   'description' => 'Grade 4'],
            ['class_name' => 'Class 5',   'description' => 'Grade 5'],
            ['class_name' => 'Class 6',   'description' => 'Grade 6'],
            ['class_name' => 'Class 7',   'description' => 'Grade 7'],
            ['class_name' => 'Class 8',   'description' => 'Grade 8'],
        ];

        foreach ($classes as $class) {
            DB::table('classes')->insert([
                'class_name'  => $class['class_name'],
                'description' => $class['description'],
                'created_at'  => $now,
                'updated_at'  => $now,
            ]);
        }

        $this->command->info('✔ ClassSeeder: ' . count($classes) . ' classes inserted.');
    }
}
