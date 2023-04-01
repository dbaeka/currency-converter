<?php

namespace Dbaeka\BuckhillCurrencyConverter\Http\Controllers;

use Dbaeka\BuckhillCurrencyConverter\BuckhillCurrencyConverter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Tag(
 *     name="Currency",
 *     description="Currency Exchange API endpoint"
 * )
 */
class CurrencyConverterController
{
    /**
     * @OA\Get(
     *     path="/api/v1/currency/convert",
     *     operationId="currency-convert",
     *     summary="Convert EUR amount to another currency",
     *     tags={"Currency"},
     *     security={{}},
     *     @OA\Parameter(
     *      name="amount",
     *      parameter="amount_query",
     *      in="query",
     *      required=true,
     *      @OA\Schema(type="number"),
     *     ),
     *     @OA\Parameter(
     *      name="currency",
     *      parameter="currency_query",
     *      in="query",
     *      required=true,
     *      @OA\Schema(type="string"),
     *     ),
     *     @OA\Response(response=200, description="OK"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=404, description="NotFound"),
     *     @OA\Response(response=422, description="Unprocessable"),
     *     @OA\Response(response=500, description="ServerError")
     * )
     */
    public function convert(Request $request, BuckhillCurrencyConverter $converter): JsonResponse
    {
        Validator::make($request->query->all(), [
            'amount' => 'required|numeric',
            'currency' => 'required|string|size:3'
        ])->validate();

        /** @var float $amount */
        $amount = $request->query('amount');
        /** @var string $currency */
        $currency = $request->query('currency');

        $result = $converter->convert(amount: $amount, currency: $currency);
        $response = [
            "error" => $result ? "" : "Not Found",
            "errors" => [],
            "data" => $result ? ["result" => $result] : [],
            "success" => $result ? 1 : 0,
        ];
        return response()->json($response);
    }
}
