<?php


namespace App\Blockchains;


use Illuminate\Support\Facades\Http;

class EthereumRpc
{

    public static function sendRawTransaction(string $url, string $rawTransaction)
    {
        return $response = Http::withBody(json_encode([
            'jsonrpc' => '2.0',
            'method' => 'eth_sendRawTransaction',
            'params' => [$rawTransaction],
            'id' => 1
        ]), 'application/json')->post($url);
    }

    public static function getTrasactionCount(string $url, string $address, string $block = "pending")
    {
        return $response = Http::withBody(json_encode([
            'jsonrpc' => '2.0',
            'method' => 'eth_getTransactionCount',
            'params' => [strtolower($address), $block],
            'id' => 1
        ]), 'application/json')->post($url);
    }

    public static function getBalance(string $url, string $address, string $block = "latest")
    {
        return $response = Http::withBody(json_encode([
            'jsonrpc' => '2.0',
            'method' => 'eth_getBalance',
            'params' => [strtolower($address), $block],
            'id' => 1
        ]), 'application/json')->post($url);
    }

    public static function newBlockFilter(string $url, int $id)
    {
        return $response = Http::withBody(json_encode([
            'jsonrpc' => '2.0',
            'id' => $id,
            'method' => 'eth_newBlockFilter',
            'params' => []
        ]), 'application/json')->post($url);
    }

    public static function getFilterChanges(string $url, int $id, array $params = [])
    {
        return $response = Http::withBody(json_encode([
            'jsonrpc' => '2.0',
            'id' => $id,
            'method' => 'eth_getFilterChanges',
            'params' => $params
        ]), 'application/json')->post($url);
    }

    public static function getLogs(string $url, int $id, array $params = [])
    {
        return $response = Http::withBody(json_encode([
            "jsonrpc" => "2.0",
            "id" => $id,
            "method" => "eth_getLogs",
            "params" => [$params]
        ]), 'application/json')->post($url);
    }

    public static function getBlockByNumber(string $url, int $id, string $blockNumberHex)
    {
        return $response = Http::withBody(json_encode([
            'jsonrpc' => '2.0',
            'id' => $id,
            'method' => 'eth_getBlockByNumber',
            'params' => [
                $blockNumberHex, false
            ]
        ]), 'application/json')->post($url);
    }

    public static function getBlockNumber(string $url, int $id)
    {
        return $response = Http::withBody(json_encode([
            'jsonrpc' => '2.0',
            'id' => $id,
            'method' => 'eth_blockNumber',
            'params' => []
        ]), 'application/json')->post($url);
    }

    public static function getCode(string $url, string $address)
    {
        return $response = Http::withBody(json_encode([
            'jsonrpc' => '2.0',
            'id' => 0,
            'method' => 'eth_getCode',
            'params' => [
                strtolower($address),
                'latest'
            ]
        ]), 'application/json')->post($url);
    }

    public static function getTransactionReceipt(string $url, string $txnHash)
    {
        return $response = Http::withBody(json_encode([
            'jsonrpc' => '2.0',
            'id' => 0,
            'method' => 'eth_getTransactionReceipt',
            'params' => [
                strtolower($txnHash),
            ]
        ]), 'application/json')->post($url);
    }

    public static function call(string $url, array $params)
    {
        return $response = Http::withBody(json_encode([
            'jsonrpc' => '2.0',
            'id' => 9,
            'method' => 'eth_call',
            'params' => [$params, 'latest']
        ]), 'application/json')->post($url);
    }

    public static function estimateGas(string $url, array $params)
    {
        return $response = Http::withBody(json_encode([
            'jsonrpc' => '2.0',
            'id' => 10,
            'method' => 'eth_estimateGas',
            'params' => [$params]
        ]), 'application/json')->post($url);
    }
}
