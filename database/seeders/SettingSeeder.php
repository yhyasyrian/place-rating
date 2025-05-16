<?php

namespace Database\Seeders;

use App\Models\Setting;
use Filament\Forms\Set;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            'description' => 'Description',
            'contact_message' => 'Contact Message',
            'top_message' => 'Top Message',
            'facebook' => 'https://www.facebook.com/profile.php?id=100000000000000',
            'twitter' => 'https://twitter.com/profile',
            'instagram' => 'https://www.instagram.com/profile',
            'linkedin' => 'https://www.linkedin.com/in/profile',
            'youtube' => 'https://www.youtube.com/profile',
            'whatsapp' => 'https://wa.me/profile',
            'site_keywords' => 'Keyword1, Keyword2, Keyword3',
            'title_home' => 'Home',
            'description_home' => 'Description Home',
        ];
        foreach ($settings as $key => $value) {
            Setting::create([
                'key' => $key,
                'value' => $value,
            ]);
        }
    }
}
