<?php
return [
    'default' => env('NETWORK', 'mainnet'),
    'networks' => [
        'mainnet' => [
            'network_id' => 43114,
            'websocket_uri' => 'wss://rpc.ankr.com/avalanche/ws/0ef364cff94c48cbb99a2cd61ea1f24baed2e391c7a0ebea6a12b800133ac4be',
            'rpc_uri' => 'https://rpc.ankr.com/avalanche/0ef364cff94c48cbb99a2cd61ea1f24baed2e391c7a0ebea6a12b800133ac4be'
        ],
        'testnet' => [
            'network_id' => 43113,
            'websocket_uri' => 'wss://speedy-nodes-nyc.moralis.io/818df772bbb4d3696f422cb8/avalanche/testnet/ws',
            'rpc_uri' => 'https://speedy-nodes-nyc.moralis.io/818df772bbb4d3696f422cb8/avalanche/testnet'
        ]
    ],
    'api_key' => env('INFURA_KEY', null)
];
