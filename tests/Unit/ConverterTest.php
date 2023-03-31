<?php

namespace Dbaeka\BuckhillCurrencyConverter\Tests\Unit;

use Dbaeka\BuckhillCurrencyConverter\BuckhillCurrencyConverter;
use Dbaeka\BuckhillCurrencyConverter\Services\Client;
use Dbaeka\BuckhillCurrencyConverter\Tests\TestCase;
use Illuminate\Support\Facades\Cache;

class ConverterTest extends TestCase
{
    public function testConvertsSuccessful(): void
    {
        Cache::shouldReceive('get')->with('exchange_rates')->andReturnNull();
        Cache::shouldReceive('put');
        $this->mock(Client::class)->shouldReceive('getRates')->andReturn([
            'USD' => 10
        ]);
        $converter = app(BuckhillCurrencyConverter::class);
        $result = $converter->convert(10, 'USD');
        $this->assertNotEmpty($result);
        $this->assertEquals(100, $result);

        Cache::shouldReceive('get')->with('exchange_rates')->andReturn([
            'USD' => 10
        ]);
        $result = $converter->convert(10, 'USD');
        $this->assertNotEmpty($result);
        $this->assertEquals(100, $result);
    }

    public function testConvertsFailWrongCurrency(): void
    {
        Cache::shouldReceive('get')->with('exchange_rates')->andReturnNull();
        Cache::shouldReceive('put');
        $this->mock(Client::class)->shouldReceive('getRates')->andReturn([
            'USD' => 10
        ]);
        $converter = app(BuckhillCurrencyConverter::class);
        $result = $converter->convert(10, 'EUR');
        $this->assertEmpty($result);

        Cache::shouldReceive('get')->with('exchange_rates')->andReturn([
            'USD' => 10
        ]);
        $result = $converter->convert(10, 'EUR');
        $this->assertEmpty($result);
    }
}
