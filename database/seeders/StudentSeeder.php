<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $guardianIds = DB::table('guardians')->pluck('id')->all();
        $disabilityIds = DB::table('disabilities')->pluck('id')->all();

        // Agar koi data nahi hai to seed na karein
        if (empty($guardianIds)) {
            $this->command->warn('⚠ No guardians found. Run GuardianSeeder first.');
            return;
        }

        if (empty($disabilityIds)) {
            $this->command->warn('⚠ No disabilities found. Run DisabilitySeeder first.');
            return;
        }

        $students = [
            [
                'registration_no' => 'REG-2024-0001',
                'first_name' => 'Faisal',
                'last_name' => 'Nawaz',
                'gender' => 'male',
                'dob' => '2014-05-15',
                'religion' => 'Islam',
                'b_form_no' => '1234-5678901-2',
                'disability_id' => $disabilityIds[0],
                'additional_disability' => null,
                'disability_certificate_no' => null,
                'previous_school_details' => 'The City School, Karachi',
                'previous_class' => '4th Grade',
                'cause_of_leaving_school' => 'Family relocation',
                'guardian_id' => $guardianIds[0],
            ],
            [
                'registration_no' => 'REG-2024-0002',
                'first_name' => 'Madiha',
                'last_name' => 'Zulfiqar',
                'gender' => 'female',
                'dob' => '2015-08-20',
                'religion' => 'Islam',
                'b_form_no' => '2345-6789012-3',
                'disability_id' => $disabilityIds[0],
                'additional_disability' => null,
                'disability_certificate_no' => null,
                'previous_school_details' => 'Lahore Grammar School, Lahore',
                'previous_class' => '3rd Grade',
                'cause_of_leaving_school' => 'Better opportunities',
                'guardian_id' => $guardianIds[1],
            ],
            [
                'registration_no' => 'REG-2024-0003',
                'first_name' => 'Shoaib',
                'last_name' => 'Anwar',
                'gender' => 'male',
                'dob' => '2013-11-10',
                'religion' => 'Islam',
                'b_form_no' => '3456-7890123-4',
                'disability_id' => $disabilityIds[0],
                'additional_disability' => null,
                'disability_certificate_no' => null,
                'previous_school_details' => 'Beaconhouse School System',
                'previous_class' => '5th Grade',
                'cause_of_leaving_school' => 'Parents transferred',
                'guardian_id' => $guardianIds[2],
            ],
            [
                'registration_no' => 'REG-2024-0004',
                'first_name' => 'Noor',
                'last_name' => 'Fatima',
                'gender' => 'female',
                'dob' => '2016-02-28',
                'religion' => 'Islam',
                'b_form_no' => '4567-8901234-5',
                'disability_id' => $disabilityIds[0],
                'additional_disability' => null,
                'disability_certificate_no' => null,
                'previous_school_details' => 'Roots Millennium School',
                'previous_class' => '2nd Grade',
                'cause_of_leaving_school' => 'Seeking better education',
                'guardian_id' => $guardianIds[3],
            ],
            [
                'registration_no' => 'REG-2024-0005',
                'first_name' => 'Hammad',
                'last_name' => 'Akhtar',
                'gender' => 'male',
                'dob' => '2012-07-14',
                'religion' => 'Islam',
                'b_form_no' => '5678-9012345-6',
                'disability_id' => $disabilityIds[1],
                'additional_disability' => 'Mild mobility issues',
                'disability_certificate_no' => 'DIS-001-2024',
                'previous_school_details' => 'Army Public School',
                'previous_class' => '6th Grade',
                'cause_of_leaving_school' => 'Changed city',
                'guardian_id' => $guardianIds[4],
            ],
            [
                'registration_no' => 'REG-2024-0006',
                'first_name' => 'Ayesha',
                'last_name' => 'Khan',
                'gender' => 'female',
                'dob' => '2014-12-01',
                'religion' => 'Islam',
                'b_form_no' => '6789-0123456-7',
                'disability_id' => $disabilityIds[0],
                'additional_disability' => null,
                'disability_certificate_no' => null,
                'previous_school_details' => 'The Educators',
                'previous_class' => '4th Grade',
                'cause_of_leaving_school' => null,
                'guardian_id' => $guardianIds[5],
            ],
            [
                'registration_no' => 'REG-2024-0007',
                'first_name' => 'Hamza',
                'last_name' => 'Ali',
                'gender' => 'male',
                'dob' => '2015-09-18',
                'religion' => 'Islam',
                'b_form_no' => '7890-1234567-8',
                'disability_id' => $disabilityIds[0],
                'additional_disability' => null,
                'disability_certificate_no' => null,
                'previous_school_details' => 'Pak Turk International School',
                'previous_class' => '3rd Grade',
                'cause_of_leaving_school' => 'Fee structure issue',
                'guardian_id' => $guardianIds[6],
            ],
            [
                'registration_no' => 'REG-2024-0008',
                'first_name' => 'Fatima',
                'last_name' => 'Zahra',
                'gender' => 'female',
                'dob' => '2013-04-25',
                'religion' => 'Islam',
                'b_form_no' => '8901-2345678-9',
                'disability_id' => $disabilityIds[0],
                'additional_disability' => null,
                'disability_certificate_no' => null,
                'previous_school_details' => 'Froebel Education Centre',
                'previous_class' => '5th Grade',
                'cause_of_leaving_school' => null,
                'guardian_id' => $guardianIds[7],
            ],
        ];

        foreach ($students as $student) {
            DB::table('students')->insert([
                ...$student,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $this->command->info('✔ StudentSeeder: ' . count($students) . ' students inserted.');
    }
}
