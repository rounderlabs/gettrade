<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // Branding
            ['key' => 'site_name', 'value' => 'Site Name', 'type' => 'string'],
            ['key' => 'site_tagline', 'value' => 'Earn. Watch. Grow.', 'type' => 'string'],
            ['key' => 'logo_desktop', 'value' => null, 'type' => 'file'],
            ['key' => 'logo_mobile', 'value' => null, 'type' => 'file'],

            // Commission
            ['key' => 'direct_percent', 'value' => 10, 'type' => 'number'],
            ['key' => 'level_percentages', 'value' => json_encode([
                1 => 5,
                2 => 3,
                3 => 2,
            ]), 'type' => 'json'],

            // System
            ['key' => 'site_status', 'value' => 'live', 'type' => 'string'],
            ['key' => 'allow_registration', 'value' => true, 'type' => 'boolean'],
        ];

        foreach ($settings as $setting) {
            SiteSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
