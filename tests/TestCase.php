<?php

namespace Dbaeka\BuckhillCurrencyConverter\Tests;

use Dbaeka\BuckhillCurrencyConverter\CurrencyConverterServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        // additional setup
    }

    protected function getPackageProviders($app): array
    {
        return [
            CurrencyConverterServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // perform environment setup
    }
}
