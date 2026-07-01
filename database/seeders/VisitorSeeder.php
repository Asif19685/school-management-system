<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VisitorSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $visitors = [
            ['name' => 'Arshad Mehmood',   'phone' => '03451234501', 'purpose' => 'Student Enquiry',   'meeting_with' => 'Receptionist',  'visitor_type' => 'Parent'],
            ['name' => 'Nasreen Bibi',      'phone' => '03451234502', 'purpose' => 'Fee Complaint',     'meeting_with' => 'Accounts Dept', 'visitor_type' => 'Parent'],
            ['name' => 'Shahzad Ali',       'phone' => '03451234503', 'purpose' => 'Book Delivery',     'meeting_with' => 'Library',       'visitor_type' => 'Vendor'],
            ['name' => 'Tahir Javed',       'phone' => '03451234504', 'purpose' => 'Government Audit',  'meeting_with' => 'Principal',     'visitor_type' => 'Official'],
            ['name' => 'Mehwish Naz',       'phone' => '03451234505', 'purpose' => 'TC Collection',     'meeting_with' => 'Admin Office',  'visitor_type' => 'Parent'],
            ['name' => 'Fawad Hussain',     'phone' => '03451234506', 'purpose' => 'Job Application',   'meeting_with' => 'HR Dept',       'visitor_type' => 'Applicant'],
            ['name' => 'Rahat Begum',       'phone' => '03451234507', 'purpose' => 'Parent Meeting',    'meeting_with' => 'Class Teacher', 'visitor_type' => 'Parent'],
            ['name' => 'Sajid Karim',       'phone' => '03451234508', 'purpose' => 'Stationary Supply', 'meeting_with' => 'Admin Office',  'visitor_type' => 'Vendor'],
            ['name' => 'Lubna Farhat',      'phone' => '03451234509', 'purpose' => 'Result Inquiry',    'meeting_with' => 'Exam Dept',     'visitor_type' => 'Parent'],
            ['name' => 'Waqar Ahmed',       'phone' => '03451234510', 'purpose' => 'Donation',          'meeting_with' => 'Principal',     'visitor_type' => 'Donor'],
        ];

        foreach ($visitors as $visitor) {
            $timeIn  = now()->subHours(rand(1, 8));
            $timeOut = (rand(0, 1)) ? $timeIn->copy()->addHours(rand(1, 3)) : null;

            DB::table('visitors')->insert([
                'name'         => $visitor['name'],
                'phone'        => $visitor['phone'],
                'purpose'      => $visitor['purpose'],
                'meeting_with' => $visitor['meeting_with'],
                'time_in'      => $timeIn,
                'time_out'     => $timeOut,
                'visitor_type' => $visitor['visitor_type'],
                'created_at'   => $now,
                'updated_at'   => $now,
            ]);
        }

        $this->command->info('✔ VisitorSeeder: ' . count($visitors) . ' visitors inserted.');
    }
}
