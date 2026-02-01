<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    public function run(): void
    {
        Currency::updateOrCreate(
            ['code' => 'INR'],
            [
                'name' => 'Indian Rupee',
                'symbol' => '₹',
                'is_base' => true,
                'is_active' => true,
            ]
        );

        Currency::updateOrCreate(
            ['code' => 'USD'],
            [
                'name' => 'US Dollar',
                'symbol' => '$',
                'is_base' => false,
                'is_active' => true,
            ]
        );
    }
}
