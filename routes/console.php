use App\Console\Commands\GenerateMonthlyFees;
use App\Console\Commands\MarkDailyAttendance;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule monthly fee generation for approved students on the 28th of every month
Schedule::command(GenerateMonthlyFees::class)->monthlyOn(28, '00:00');

// Auto-mark all approved students as Present every day at 8:00 AM (skip already marked)
Schedule::command(MarkDailyAttendance::class)->dailyAt('08:00');
