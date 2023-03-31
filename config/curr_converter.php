<?php

return [
    'url' => env('CURRENCY_CONVERTER_URL', 'https://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml'),
    'timeout' => env('CURRENCY_CONVERTER_TIMEOUT', 10),
    'retry_times' => env('CURRENCY_CONVERTER_RETRY_TIMES', null),
    'retry_milliseconds' => env('CURRENCY_CONVERTER_RETRY_MILLISECONDS', null),
];
