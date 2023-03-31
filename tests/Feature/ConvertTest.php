<?php

namespace Dbaeka\BuckhillCurrencyConverter\Tests\Feature;

use Dbaeka\BuckhillCurrencyConverter\Services\Client;
use Dbaeka\BuckhillCurrencyConverter\Tests\TestCase;
use Illuminate\Support\Facades\Cache;

class ConvertTest extends TestCase
{
    public function testGetRatesFromClient(): void
    {
        Cache::shouldReceive('get')->with('exchange_rates')->andReturnNull();
        Cache::shouldReceive('put');
        $this->mock(Client::class)->shouldReceive('getRates')->andReturn([
            'USD' => 10
        ]);
        $response = $this->getJson('api/v1/currency/convert?currency=USD&amount=10');
        $response->assertOk()
            ->assertJsonStructure([
                'error', 'success', 'data' => ['result']
            ])
            ->assertJsonFragment([
                'success' => 1,
                'result' => 100
            ]);
    }

    public function testGetRatesFromCache(): void
    {
        Cache::shouldReceive('get')->with('exchange_rates')->andReturn([
            'USD' => 10
        ]);
        $response = $this->getJson('api/v1/currency/convert?currency=USD&amount=10');
        $response->assertOk()
            ->assertJsonStructure([
                'error', 'success', 'data' => ['result']
            ])
            ->assertJsonFragment([
                'success' => 1,
                'result' => 100
            ]);
    }

    public function testGetRatesFailWrongParams(): void
    {
        $response = $this->getJson('api/v1/currency/convert?currency=USD');
        $response->assertUnprocessable();

        $response = $this->getJson('api/v1/currency/convert?amount=20');
        $response->assertUnprocessable();
    }
}
