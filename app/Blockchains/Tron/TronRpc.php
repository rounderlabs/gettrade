<?php


namespace App\Blockchains\Tron;


use Illuminate\Support\Facades\Http;

class TronRpc
{
    public static function getAccountTransactions(string $url, string $address, bool $onlyTo = true, bool $onlyFrom = false, $minTimestamp = null, $maxTimeStamp = null)
    {
        return $response = Http::withHeaders([
            'TRON-PRO-API-KEY' => config('network_tron.api_key'),
            'Content-Type' => 'application/json'
        ])->get($url . '/v1/accounts/' . $address . '/transactions', [
            'only_to' => $onlyTo,
            'only_from' => $onlyFrom,
            'min_timestamp' => is_null($minTimestamp) ? 0 : $minTimestamp,
            'mx_timestamp' => is_null($maxTimeStamp) ? now()->unix() : $maxTimeStamp,
            'order_by' => 'block_timestamp,asc'
        ]);
    }
}
