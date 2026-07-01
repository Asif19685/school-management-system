<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GuardianSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $guardians = [
            [
                'father_name' => 'Ahmed Khan',
                'father_cnic' => '35201-1234567-8',
                'father_occupation' => 'Software Engineer',
                'mother_name' => 'Saima Ahmed',
                'mother_education' => 'Masters',
                'family_monthly_income' => '100000',
                'phone' => '03001234567',
                'emergency_contact' => '03009876543',
                'complete_address' => 'House #12, Street 5, Gulshan-e-Iqbal, Karachi',
                'postal_address' => 'P.O. Box 123, Karachi',
            ],
            [
                'father_name' => 'Mohammad Raza',
                'father_cnic' => '35202-2345678-9',
                'father_occupation' => 'Doctor',
                'mother_name' => 'Fatima Raza',
                'mother_education' => 'Bachelors',
                'family_monthly_income' => '150000',
                'phone' => '03112345678',
                'emergency_contact' => '03119876543',
                'complete_address' => 'Flat #8, Al-Noor Heights, DHA, Lahore',
                'postal_address' => 'DHA Phase 5, Lahore',
            ],
            [
                'father_name' => 'Usman Chaudhry',
                'father_cnic' => '35203-3456789-0',
                'father_occupation' => 'Businessman',
                'mother_name' => 'Ayesha Usman',
                'mother_education' => 'Intermediate',
                'family_monthly_income' => '200000',
                'phone' => '03451234567',
                'emergency_contact' => '03459876543',
                'complete_address' => 'Shop #45, Commercial Market, Rawalpindi',
                'postal_address' => 'Rawalpindi Cantt',
            ],
            [
                'father_name' => 'Bilal Akhtar',
                'father_cnic' => '35204-4567890-1',
                'father_occupation' => 'Teacher',
                'mother_name' => 'Zainab Bilal',
                'mother_education' => 'Masters',
                'family_monthly_income' => '80000',
                'phone' => '03331234567',
                'emergency_contact' => '03339876543',
                'complete_address' => 'Village Khayaban, P.O. Sadiqabad, Multan',
                'postal_address' => 'Sadiqabad, Multan',
            ],
            [
                'father_name' => 'Kamran Shahzad',
                'father_cnic' => '35205-5678901-2',
                'father_occupation' => 'Government Employee',
                'mother_name' => 'Farah Kamran',
                'mother_education' => 'Bachelors',
                'family_monthly_income' => '120000',
                'phone' => '03221234567',
                'emergency_contact' => '03229876543',
                'complete_address' => 'House #7, Officers Colony, Peshawar',
                'postal_address' => 'Peshawar Cantt',
            ],
            [
                'father_name' => 'Shoaib Malik',
                'father_cnic' => '35206-6789012-3',
                'father_occupation' => 'Lawyer',
                'mother_name' => 'Rubina Shoaib',
                'mother_education' => 'LLB',
                'family_monthly_income' => '180000',
                'phone' => '03461234567',
                'emergency_contact' => '03469876543',
                'complete_address' => 'Chamber #12, High Court Bar, Quetta',
                'postal_address' => 'Quetta City',
            ],
            [
                'father_name' => 'Naveed Anjum',
                'father_cnic' => '35207-7890123-4',
                'father_occupation' => 'Banker',
                'mother_name' => 'Sadia Naveed',
                'mother_education' => 'Masters',
                'family_monthly_income' => '130000',
                'phone' => '03124567890',
                'emergency_contact' => '03129876543',
                'complete_address' => 'Block C, Model Town, Faisalabad',
                'postal_address' => 'Model Town, Faisalabad',
            ],
            [
                'father_name' => 'Faisal Nawaz',
                'father_cnic' => '35208-8901234-5',
                'father_occupation' => 'Engineer',
                'mother_name' => 'Madiha Faisal',
                'mother_education' => 'Bachelors',
                'family_monthly_income' => '140000',
                'phone' => '03344556677',
                'emergency_contact' => '03349876543',
                'complete_address' => 'Street #3, Satellite Town, Gujranwala',
                'postal_address' => 'Satellite Town, Gujranwala',
            ],
        ];

        foreach ($guardians as $guardian) {
            DB::table('guardians')->insert([
                ...$guardian,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $this->command->info('✔ GuardianSeeder: ' . count($guardians) . ' guardians inserted.');
    }
}
