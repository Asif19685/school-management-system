<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DisabilitySeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $disabilities = [
            [
                'name' => 'None',
                'description' => 'No disability',
                'status' => true
            ],
            [
                'name' => 'Physical Disability',
                'description' => 'Physical impairment affecting mobility',
                'status' => true
            ],
            [
                'name' => 'Visual Impairment',
                'description' => 'Partial or complete vision loss',
                'status' => true
            ],
            [
                'name' => 'Hearing Impairment',
                'description' => 'Partial or complete hearing loss',
                'status' => true
            ],
            [
                'name' => 'Speech Disability',
                'description' => 'Difficulty in speaking or communication',
                'status' => true
            ],
            [
                'name' => 'Learning Disability',
                'description' => 'Dyslexia, dyscalculia, etc.',
                'status' => true
            ],
            [
                'name' => 'Intellectual Disability',
                'description' => 'Cognitive developmental challenges',
                'status' => true
            ],
            [
                'name' => 'Autism Spectrum',
                'description' => 'Autism or related conditions',
                'status' => true
            ],
            [
                'name' => 'Multiple Disabilities',
                'description' => 'Two or more disabilities',
                'status' => true
            ],
        ];

        foreach ($disabilities as $disability) {
            // Check if already exists to avoid duplicates
            $exists = DB::table('disabilities')
                ->where('name', $disability['name'])
                ->exists();

            if (!$exists) {
                DB::table('disabilities')->insert([
                    'name' => $disability['name'],
                    'description' => $disability['description'],
                    'status' => $disability['status'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        $this->command->info('✔ DisabilitySeeder: ' . count($disabilities) . ' disabilities inserted.');
    }
}
