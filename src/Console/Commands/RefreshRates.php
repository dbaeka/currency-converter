<?php

namespace Dbaeka\BuckhillCurrencyConverter\Console\Commands;

use Dbaeka\BuckhillCurrencyConverter\Services\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Throwable;

class RefreshRates extends Command
{
    protected $signature = 'exchange:update-rates';

    protected $description = 'Update exchange rates from the European exchange service';

    public function handle(): int
    {
        $service = app(Client::class);
        try {
            $rates = $service->getRates();

            Cache::put('exchange_rates', $rates, now()->secondsUntilEndOfDay());

            $this->info('Exchange rates updated successfully!');
            return 0;
        } catch (Throwable $e) {
            $this->error($e->getMessage());
        }
        return -1;
    }
}
