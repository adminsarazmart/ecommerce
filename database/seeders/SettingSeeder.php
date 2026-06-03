<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General
            ['key' => 'site_name', 'value' => 'BanglaMart', 'group' => 'general', 'type' => 'text'],
            ['key' => 'site_tagline', 'value' => 'Bangladesh\'s Premier Online Marketplace', 'group' => 'general', 'type' => 'text'],
            ['key' => 'site_email', 'value' => 'info@banglamart.com', 'group' => 'general', 'type' => 'email'],
            ['key' => 'site_phone', 'value' => '+8801700000000', 'group' => 'general', 'type' => 'text'],
            ['key' => 'site_address', 'value' => 'Dhaka, Bangladesh', 'group' => 'general', 'type' => 'textarea'],
            ['key' => 'timezone', 'value' => 'Asia/Dhaka', 'group' => 'general', 'type' => 'text'],
            ['key' => 'currency', 'value' => 'BDT', 'group' => 'general', 'type' => 'text'],
            ['key' => 'default_language', 'value' => 'bn', 'group' => 'general', 'type' => 'text'],

            // Payment
            ['key' => 'cod_enabled', 'value' => '1', 'group' => 'payment', 'type' => 'boolean'],
            ['key' => 'bank_transfer_enabled', 'value' => '1', 'group' => 'payment', 'type' => 'boolean'],
            ['key' => 'bkash_enabled', 'value' => '1', 'group' => 'payment', 'type' => 'boolean'],
            ['key' => 'nagad_enabled', 'value' => '1', 'group' => 'payment', 'type' => 'boolean'],
            ['key' => 'stripe_enabled', 'value' => '0', 'group' => 'payment', 'type' => 'boolean'],
            ['key' => 'paypal_enabled', 'value' => '0', 'group' => 'payment', 'type' => 'boolean'],

            // Shipping
            ['key' => 'free_shipping_threshold', 'value' => '1000', 'group' => 'shipping', 'type' => 'number'],
            ['key' => 'standard_shipping_fee', 'value' => '60', 'group' => 'shipping', 'type' => 'number'],
            ['key' => 'express_shipping_fee', 'value' => '150', 'group' => 'shipping', 'type' => 'number'],
            ['key' => 'shipping_zones', 'value' => 'inside_dhaka,outside_dhaka', 'group' => 'shipping', 'type' => 'text'],

            // Email
            ['key' => 'mail_driver', 'value' => 'smtp', 'group' => 'email', 'type' => 'text'],
            ['key' => 'mail_host', 'value' => 'smtp.mailtrap.io', 'group' => 'email', 'type' => 'text'],
            ['key' => 'mail_port', 'value' => '587', 'group' => 'email', 'type' => 'number'],
            ['key' => 'mail_username', 'value' => '', 'group' => 'email', 'type' => 'text'],
            ['key' => 'mail_password', 'value' => '', 'group' => 'email', 'type' => 'password'],
            ['key' => 'mail_from_address', 'value' => 'noreply@banglamart.com', 'group' => 'email', 'type' => 'email'],
            ['key' => 'mail_from_name', 'value' => 'BanglaMart', 'group' => 'email', 'type' => 'text'],

            // SEO
            ['key' => 'meta_title', 'value' => 'BanglaMart - Bangladesh\'s Premier Online Marketplace', 'group' => 'seo', 'type' => 'text'],
            ['key' => 'meta_description', 'value' => 'Shop the best products from trusted vendors across Bangladesh. Electronics, fashion, home goods, and more at unbeatable prices.', 'group' => 'seo', 'type' => 'textarea'],
            ['key' => 'meta_keywords', 'value' => 'banglamart, online shopping, bangladesh, ecommerce, electronics, fashion', 'group' => 'seo', 'type' => 'text'],
            ['key' => 'google_analytics_id', 'value' => '', 'group' => 'seo', 'type' => 'text'],
            ['key' => 'facebook_pixel_id', 'value' => '', 'group' => 'seo', 'type' => 'text'],

            // Security
            ['key' => 'max_login_attempts', 'value' => '5', 'group' => 'security', 'type' => 'number'],
            ['key' => 'lockout_time', 'value' => '15', 'group' => 'security', 'type' => 'number'],
            ['key' => 'password_expiry_days', 'value' => '90', 'group' => 'security', 'type' => 'number'],
            ['key' => 'two_factor_enabled', 'value' => '0', 'group' => 'security', 'type' => 'boolean'],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }
}
