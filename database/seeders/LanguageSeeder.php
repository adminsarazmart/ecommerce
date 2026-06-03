<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    public function run(): void
    {
        $languages = [
            ['name' => 'English', 'code' => 'en', 'is_default' => true, 'is_active' => true, 'rtl' => false],
            ['name' => 'বাংলা (Bengali)', 'code' => 'bn', 'is_default' => false, 'is_active' => true, 'rtl' => false],
            ['name' => 'हिन्दी (Hindi)', 'code' => 'hi', 'is_default' => false, 'is_active' => true, 'rtl' => false],
            ['name' => 'العربية (Arabic)', 'code' => 'ar', 'is_default' => false, 'is_active' => true, 'rtl' => true],
            ['name' => 'اردو (Urdu)', 'code' => 'ur', 'is_default' => false, 'is_active' => true, 'rtl' => true],
            ['name' => '中文 (Chinese)', 'code' => 'zh', 'is_default' => false, 'is_active' => true, 'rtl' => false],
            ['name' => 'Español (Spanish)', 'code' => 'es', 'is_default' => false, 'is_active' => true, 'rtl' => false],
            ['name' => 'Français (French)', 'code' => 'fr', 'is_default' => false, 'is_active' => true, 'rtl' => false],
            ['name' => '日本語 (Japanese)', 'code' => 'ja', 'is_default' => false, 'is_active' => true, 'rtl' => false],
        ];

        foreach ($languages as $language) {
            Language::create($language);
        }
    }
}
