<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;

class AdministratorSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate([
            'name' => 'Administrador',
            'email' => 'admin@custom-job.com',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('admin')
        ]);
    }
}
