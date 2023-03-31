<?php

namespace Dbaeka\BuckhillCurrencyConverter\Tests\Unit;

use Dbaeka\BuckhillCurrencyConverter\Services\Client;
use Dbaeka\BuckhillCurrencyConverter\Tests\TestCase;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class ClientTest extends TestCase
{
    public function testGetRatesSuccessfully(): void
    {
        $service = app(Client::class);
        /** @var string $sample_xml */
        $sample_xml = @file_get_contents(__DIR__ . '/../fixtures/sample.xml');
        Http::fake([
            '*' => Http::response($sample_xml)
        ]);
        $rates = $service->getRates();
        $this->assertNotEmpty($rates);
        $this->assertArrayHasKey('USD', $rates);
    }

    public function testGetRatesFail(): void
    {
        $this->expectException(RequestException::class);
        $service = app(Client::class);
        Http::fake([
            '*' => Http::response('', 400)
        ]);
        $service->getRates();
    }
}
