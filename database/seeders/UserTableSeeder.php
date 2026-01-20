<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Rank;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $user = User::create([
            'name' => 'Get Trade',
            'email' => 'gettrade@gmail.com',
            'password' => '12345678',
            'security_answer' => '12345678',
            'mobile' => '11111111',
            'country_id' => 101,
            'ref_code' => 'GT11111',
            'username' => 'GT11111',
            'sponsor_id' => 0,
            'position' => 'LEFT'
        ]);

        $user->userActive()->create([
            'active_at' => now()
        ]);

        $user->tree()->create([
            'sponsor_id' => 0,
            'position' => 1
        ]);

        $user->team()->create();
        $user->userLevels()->create([
            'level' => 1,
            'team' => 0,
            'active_team' => 0
        ]);




    }
}
