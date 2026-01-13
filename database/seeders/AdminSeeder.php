<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
	public function run(): void
	{
		collect([
			[
				'first_name' => 'Admin',
				'last_name' => 'Test',
				'email' => 'admin@test.dev',
				'phone' => '0123456789',
				'password' => Hash::make('password'),
				'email_verified_at' => now(),
			],
			[
				'first_name' => 'Hugo',
				'last_name' => 'Lefebvre',
				'email' => 'h.lefebvre@test.dev',
				'password' => Hash::make('password'),
				'email_verified_at' => now(),
			],
		])->each(fn($admin) => Admin::create($admin));
	}
}
