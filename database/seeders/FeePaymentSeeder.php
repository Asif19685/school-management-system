<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FeePaymentSeeder extends Seeder
{
    public function run(): void
    {
        $now        = now();
        $methods    = ['Cash', 'Bank Transfer', 'Cheque', 'Online'];
        $feeIds     = DB::table('fees')->where('status', '!=', 'pending')->pluck('id')->all();
        $count      = 0;

        foreach ($feeIds as $idx => $feeId) {
            $fee = DB::table('fees')->where('id', $feeId)->first();
            DB::table('fee_payments')->insert([
                'fee_id'         => $feeId,
                'paid_amount'    => $fee->amount,
                'payment_date'   => now()->subDays(rand(0, 30))->toDateString(),
                'payment_method' => $methods[array_rand($methods)],
                'receipt_no'     => 'RCPT-' . str_pad((string)($idx + 1), 5, '0', STR_PAD_LEFT),
                'created_at'     => $now,
                'updated_at'     => $now,
            ]);
            $count++;
        }

        $this->command->info("✔ FeePaymentSeeder: {$count} payment records inserted.");
    }
}
