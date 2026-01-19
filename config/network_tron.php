<?php
return [
    'default' => env('NETWORK_TRON', 'mainnet'),
    'networks' => [
        'mainnet' => [
            'websocket_uri' => null,
            'rpc_uri' => 'https://api.trongrid.io'

        ],
        'shasta' => [
            'network_id' => 56,
            'websocket_uri' => null,
            'rpc_uri' => 'https://api.shasta.trongrid.io'
        ],
        'nileex' => [
            'websocket_uri' => null,
            'rpc_uri' => 'https://api.nileex.io'
        ],
        'development' => [
            'websocket_uri' => 'ws://127.0.0.1:9090',
            'rpc_uri' => 'http://127.0.0.1:9090'
        ]
    ],
    'api_key' => env('TRONGRID_API_KEY', null),
    'contract_address' => !is_null(env('BSC_CONTRACT_ADDRESS', null)) ? strtolower(env('BSC_CONTRACT_ADDRESS')) : null,
    'subscription_methods' => [
    ],
    'fund_wallet' => env('TRON_FUND_WALLET_ADDRESS', null)
];
