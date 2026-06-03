<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Shareholder;
use App\Models\UserProfile;
use Illuminate\Database\Seeder;

class ShareholderSeeder extends Seeder
{
    public function run(): void
    {
        $shareholders = [
            ['name' => 'Abdur Rahman', 'email' => 'shareholder1@example.com'],
            ['name' => 'Fatima Begum', 'email' => 'shareholder2@example.com'],
            ['name' => 'Khaled Hasan', 'email' => 'shareholder3@example.com'],
            ['name' => 'Nadia Islam', 'email' => 'shareholder4@example.com'],
            ['name' => 'Tariq Ahmed', 'email' => 'shareholder5@example.com'],
            ['name' => 'Shamima Akhter', 'email' => 'shareholder6@example.com'],
        ];

        $percentage = round(100 / 6, 4);

        foreach ($shareholders as $i => $data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]);
            $user->assignRole('Shareholder');
            UserProfile::create(['user_id' => $user->id, 'first_name' => explode(' ', $data['name'])[0], 'last_name' => explode(' ', $data['name'])[1] ?? '']);

            Shareholder::create([
                'user_id' => $user->id,
                'shareholder_code' => 'SH-' . str_pad($i + 1, 3, '0', STR_PAD_LEFT),
                'share_percentage' => $percentage,
                'total_investment' => round(500000 / 6, 2),
                'total_shares' => round(6000 / 6),
                'join_date' => now()->subMonths(6),
                'status' => 'active',
            ]);
        }
    }
}
