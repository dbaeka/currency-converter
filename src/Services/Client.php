<?php

namespace Dbaeka\BuckhillCurrencyConverter\Services;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class Client
{
    public function __construct(
        protected string   $uri,
        protected int      $timeout = 10,
        protected null|int $retryTimes = null,
        protected null|int $retryMilliseconds = null,
    ) {
    }

    /**
     * @return array<int|string, mixed>
     * @throws RequestException
     */
    public function getRates(): array
    {
        $request = Http::withHeaders([
            'Accept' => 'application/xml',
        ])->timeout(
            seconds: $this->timeout,
        );

        if (!is_null($this->retryTimes) && !is_null($this->retryMilliseconds)) {
            $request->retry(
                times: $this->retryTimes,
                sleepMilliseconds: $this->retryMilliseconds,
            );
        }

        $response = $request->get(url: $this->uri);
        $response->throw();
        return $this->convertXMLToRateMap($response->body());
    }

    /**
     * @param string $xml
     * @return array<int|string, mixed>
     */
    private function convertXMLToRateMap(string $xml): array
    {
        $node = xml_to_array($xml);
        $values = data_get($node, 'Cube.Cube.Cube');
        $mapping = array();
        foreach ($values as $value) {
            $value = head($value);
            $currency = $value['currency'];
            $rate = $value['rate'];
            $mapping[$currency] = $rate;
        }
        return $mapping;
    }
}
