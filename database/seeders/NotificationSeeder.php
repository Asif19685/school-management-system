<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $now         = now();
        $adminUserId = DB::table('users')->where('email', 'admin@sms.local')->value('id');

        $notifications = [
            ['title' => 'Fee Reminder',           'message' => 'Monthly fee deadline is approaching. Please collect pending fees.', 'channel' => 'system', 'is_read' => false],
            ['title' => 'Attendance Alert',        'message' => 'Low attendance reported in Class 5-B today.',                       'channel' => 'system', 'is_read' => false],
            ['title' => 'Exam Schedule Published', 'message' => 'Mid Term exam schedule has been published. Please review.',         'channel' => 'system', 'is_read' => true],
            ['title' => 'New Student Admission',   'message' => '3 new student admissions are pending approval.',                    'channel' => 'system', 'is_read' => false],
            ['title' => 'Library Book Due',        'message' => '5 issued books are overdue and need to be returned.',              'channel' => 'system', 'is_read' => true],
            ['title' => 'Staff Meeting',           'message' => 'Monthly staff meeting scheduled for Friday 3:00 PM.',               'channel' => 'email',  'is_read' => false],
            ['title' => 'Annual Day Reminder',     'message' => 'Annual prize distribution day is scheduled next month.',            'channel' => 'sms',    'is_read' => false],
            ['title' => 'System Update',           'message' => 'School Management System has been updated to the latest version.',  'channel' => 'system', 'is_read' => true],
        ];

        foreach ($notifications as $notification) {
            DB::table('notifications')->insert([
                'user_id'    => $adminUserId,
                'title'      => $notification['title'],
                'message'    => $notification['message'],
                'channel'    => $notification['channel'],
                'is_read'    => $notification['is_read'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $this->command->info('✔ NotificationSeeder: ' . count($notifications) . ' notifications inserted.');
    }
}
