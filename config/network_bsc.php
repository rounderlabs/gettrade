<?php
return [
    'default' => env('NETWORK_BSC', 'development'),
    'networks' => [
        'testnet' => [
            'network_id' => 97,
            'websocket_uri' => null,
            'rpc_uri' => 'https://data-seed-prebsc-2-s3.binance.org:8545/'

        ],
        'bsc' => [
            'network_id' => 56,
            'websocket_uri' => null,
            'rpc_uri' => 'https://bsc-dataseed1.binance.org/'
        ],
        'development' => [
            'network_id' => '*',
            'websocket_uri' => 'ws://127.0.0.1:8545',
            'rpc_uri' => 'http://127.0.0.1:8545'
        ]
    ],
    'api_key' => env('INFURA_KEY', null),
    'contract_address' => !is_null(env('BSC_CONTRACT_ADDRESS', null)) ? strtolower(env('BSC_CONTRACT_ADDRESS')) : null,
    'subscription_methods' => [
        'newPayment' => strtolower('0x04b737a2e241d9198fa78b80bf0f14aa58250d7cabe0ad875ee26691a412d7c4'),
    ]
];
