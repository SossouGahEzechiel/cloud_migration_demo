<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
	public function run(): void
	{
		$locations = [
			[
				'name' => 'Lomé',
			],
			[
				'name' => 'Agbalépédo',
			],
			[
				'name' => 'Bé-kpota',
			],
			[
				'name' => 'WUITI',
			],
			[
				'name' => 'Baguida',
			],
			[
				'name' => 'Adidogomé',
			],
		];

		foreach ($locations as $location) {
			Location::create($location);
		}
	}
}
