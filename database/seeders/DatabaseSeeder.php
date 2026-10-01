<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed User Admin Sekretariat PKKMB saja
        if (!User::where('email', 'syahputrareyhandwi@gmail.com')->exists()) {
            User::create([
                'name' => 'Admin Sekretariat PKKMB',
                'email' => 'syahputrareyhandwi@gmail.com',
                'password' => Hash::make('reyhansyaputra'),
                'role' => User::ROLE_ADMIN,
            ]);
        }

        // Seed Seksi Panitia Default
        $defaultSections = [
            'Acara',
            'Perlengkapan',
            'Humas',
            'Konsumsi',
            'Dokumentasi & Media',
            'Keamanan & Kedisiplinan',
            'P3K / Kesehatan',
            'IT & Transmisi',
            'Kedisiplinan',
            'Lainnya',
        ];

        foreach ($defaultSections as $sectionName) {
            \App\Models\CommitteeSection::firstOrCreate(['name' => $sectionName]);
        }
    }
}
