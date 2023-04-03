<?php

namespace Dbaeka\BuckhillCurrencyConverter;

use Dbaeka\BuckhillCurrencyConverter\Services\Client;
use Illuminate\Support\Facades\Cache;

class BuckhillCurrencyConverter
{
    public function convert(float $amount, string $currency): ?float
    {
        $rates = Cache::get('exchange_rates');
        if (empty($rates)) {
            $rates = app(Client::class)->getRates();
            Cache::put('exchange_rates', $rates, now()->secondsUntilEndOfDay());
        }
        $rate = data_get($rates, strtoupper($currency));
        return $rate ? round($amount * $rate, 2) : null;
    }
}
