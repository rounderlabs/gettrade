module.exports = {
    apps: [
        {
            name: 'ProHorizon',
            script: './artisan',
            interpreter: 'php',
            instances: 1,
            args: 'horizon',
            autorestart: true,
            watch: false,
            max_memory_restart: '1G',
            env: {
                NODE_ENV: 'development',
            },
            env_prod: {
                NODE_ENV: 'production',
            },
        },
        {
            name: 'ProScheduler',
            script: './artisan',
            interpreter: 'php',
            instances: 1,
            args: 'schedule:work',
            autorestart: true,
            watch: false,
            max_memory_restart: '1G',
            env: {
                NODE_ENV: 'development',
            },
            env_prod: {
                NODE_ENV: 'production',
            },
        },
        {
            name: 'ProShortScheduler',
            script: './artisan',
            interpreter: 'php',
            instances: 1,
            args: 'short-schedule:run',
            autorestart: true,
            watch: false,
            max_memory_restart: '512M',
            env: {
                NODE_ENV: 'development',
            },
            env_prod: {
                NODE_ENV: 'production',
            },
        },
        {
            name: 'ProRedis',
            script: './artisan',
            interpreter: 'php',
            instances: 1,
            args: 'queue:work redis --sleep=3 --tries=3 --timeout=90',
            autorestart: true,
            watch: false,
            max_memory_restart: '2G',
            env: {
                NODE_ENV: 'development',
            },
            env_prod: {
                NODE_ENV: 'production',
            },
        },
    ],
};
