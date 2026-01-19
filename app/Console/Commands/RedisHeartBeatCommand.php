<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class RedisHeartBeatCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'redis:heart-beat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Heart Beat to Redis Subscriber';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }


    public function handle()
    {
        Redis::publish('lara.php.heart_beat', json_encode([
            'beat' => 'Heat Beat...'
        ]));
    }
}
