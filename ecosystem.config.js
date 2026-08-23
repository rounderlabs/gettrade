module.exports = {
    apps: [
        {
            name: 'GetWealth-Horizon',
            cwd: __dirname,
            script: 'artisan',
            interpreter: 'php',
            instances: 1,
            args: 'horizon',
            autorestart: true,
            watch: false,
            max_memory_restart: '1G',
            env: {
                APP_ENV: 'production',
            },
        },
        {
            name: 'GetWealth-Scheduler',
            cwd: __dirname,
            script: 'artisan',
            interpreter: 'php',
            instances: 1,
            args: 'schedule:work',
            autorestart: true,
            watch: false,
            max_memory_restart: '1G',
            env: {
                APP_ENV: 'production',
            },
        },
        {
            name: 'GetWealth-ShortScheduler',
            cwd: __dirname,
            script: 'artisan',
            interpreter: 'php',
            instances: 1,
            args: 'short-schedule:run',
            autorestart: true,
            watch: false,
            max_memory_restart: '512M',
            env: {
                APP_ENV: 'production',
            },
        },
        {
            name: 'GetWealth-Redis',
            cwd: __dirname,
            script: 'artisan',
            interpreter: 'php',
            instances: 1,
            args: 'queue:work redis --sleep=3 --tries=3 --timeout=90',
            autorestart: true,
            watch: false,
            max_memory_restart: '2G',
            env: {
                APP_ENV: 'production',
            },
        },
    ],
};

