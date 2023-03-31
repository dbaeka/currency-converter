<?php

namespace Dbaeka\BuckhillCurrencyConverter\ScheduledJobs;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Log;
use Throwable;

class UpdateRatesJob
{
    public function __invoke(Schedule $schedule): void
    {
        try {
            $schedule->command('exchange:update-rates')->dailyAt('07:00');
        } catch (Throwable) {
            Log::error('Failed getting rates');
        }
    }
}
