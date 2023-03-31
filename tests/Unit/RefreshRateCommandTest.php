<?php

namespace Dbaeka\BuckhillCurrencyConverter\Tests\Unit;

use Dbaeka\BuckhillCurrencyConverter\Services\Client;
use Dbaeka\BuckhillCurrencyConverter\Tests\TestCase;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Testing\PendingCommand;

class RefreshRateCommandTest extends TestCase
{
    public function testCommandSuccessful(): void
    {
        Cache::shouldReceive('put');
        $this->mock(Client::class)->shouldReceive('getRates')->andReturn([
            'USD' => 10
        ]);
        /** @var PendingCommand $command */
        $command = $this->artisan('exchange:update-rates');
        $command->assertOk();
    }

    public function testCommandFail(): void
    {
        Cache::shouldReceive('put');
        $this->mock(Client::class)->shouldReceive('getRates')
            ->andThrow(new Exception());
        /** @var PendingCommand $command */
        $command = $this->artisan('exchange:update-rates');
        $command->assertFailed();
    }
}
