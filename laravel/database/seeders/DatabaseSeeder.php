<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Setting;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin User
        User::create([
            'name' => 'Admin',
            'surname' => 'User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'matricola' => 'ADMIN001',
            // Anagrafica dummies
            'area_appartenenza' => 'Amministrazione',
            'profilo_attuale' => 'Dirigente',
            'nato_a' => 'Roma',
            'data_nascita' => '1980-01-01',
            'residente_a' => 'Roma',
            'via' => 'Via Roma 1',
            'cap' => '00100',
            'prov' => 'RM',
            'codice_fiscale' => 'ADMINCF001',
            'telefono' => '123456789'
        ]);

        // Default Window (Open for 2025)
        Setting::create(['key' => 'start_date', 'value' => '2025-01-01']);
        Setting::create(['key' => 'end_date', 'value' => '2025-12-31']);
    }
}
