<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BackupLocation;

class BackupLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locations = [
            'HARD DISK (HDD)',
            'PENDRIVE',
            'GOOGLE DRIVE',
            'GITHUB',
            'SERVER',
            'SOFTWARE',
        ];

        foreach ($locations as $location) {
            BackupLocation::firstOrCreate(['name' => $location]);
        }
    }
}
