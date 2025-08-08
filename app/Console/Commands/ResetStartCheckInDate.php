<?php

namespace App\Console\Commands;

use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ResetStartCheckInDate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:reset-start-check-in-date';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $currentDate = Setting::where('key', 'start_check_in_date')->first();
        $period = Setting::where('key', 'check_in_period')->first();
        if ($currentDate === null || $period === null) {
            $this->error('No current date config.');

            return;
        }

        $date = Carbon::createFromFormat('Y-m-d H:i:s', $currentDate->value);
        $date->startOfDay()->addDays((int) $period->value);
        if (!$date->isToday()) {
            $this->warn('not update');
            return;
        }
        Setting::where('key', 'start_check_in_date')->update([
            'value' => $date->toDateTimeString(),
        ]);
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        $this->info('Update start check-in date success');
    }
}
