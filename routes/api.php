<?php

use Dbaeka\BuckhillCurrencyConverter\Http\Controllers\CurrencyConverterController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/v1')->get('currency/convert', [CurrencyConverterController::class, 'convert'])
    ->name('convert.currency');
