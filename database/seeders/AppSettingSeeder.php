<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $appSettings = [
            // General Setting
            ['name' => 'site_name', 'value' => 'Stream Line Pro'],
            ['name' => 'site_url', 'value' => null],
            ['name' => 'app_url', 'value' => null],
            ['name' => 'site_logo_light', 'value' => 'settings/logo/logo-light.png'],
            ['name' => 'site_logo_dark', 'value' => 'settings/logo/logo-dark.png'],
            ['name' => 'site_logo_small', 'value' => 'settings/logo/logo-sm.png'],
            ['name' => 'site_timezone', 'value' => 'UTC'],

            // Authentication
            ['name' => 'can_admin_register', 'value' => 0],
            ['name' => 'can_admin_reset_password', 'value' => 0],
            ['name' => 'can_customer_register', 'value' => 1],
            ['name' => 'can_user_reset_password', 'value' => 1],
            
            ['name' => 'login_page_heading', 'value' => null],
            ['name' => 'login_page_heading_color', 'value' => null],
            ['name' => 'login_page_heading_bg_color', 'value' => null],
            ['name' => 'login_page_text', 'value' => null],
            ['name' => 'login_page_text_color', 'value' => null],
            ['name' => 'login_page_text_bg_color', 'value' => null],
            ['name' => 'login_page_image', 'value' => 'settings/page/login-page.jpg'],

            ['name' => 'register_page_heading', 'value' => null],
            ['name' => 'register_page_heading_color', 'value' => null],
            ['name' => 'register_page_heading_bg_color', 'value' => null],
            ['name' => 'register_page_text', 'value' => null],
            ['name' => 'register_page_text_color', 'value' => null],
            ['name' => 'register_page_text_bg_color', 'value' => null],
            ['name' => 'register_page_image', 'value' => 'settings/page/register-page.jpg'],

            ['name' => 'reset_page_heading', 'value' => null],
            ['name' => 'reset_page_heading_color', 'value' => null],
            ['name' => 'reset_page_heading_bg_color', 'value' => null],
            ['name' => 'reset_page_text', 'value' => null],
            ['name' => 'reset_page_text_color', 'value' => null],
            ['name' => 'reset_page_text_bg_color', 'value' => null],
            ['name' => 'reset_page_image', 'value' => 'settings/page/reset-page.jpg'],
        ];

        DB::table('app_settings')->insert($appSettings);
    }
}