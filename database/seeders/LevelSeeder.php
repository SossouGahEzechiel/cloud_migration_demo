<?php

namespace Database\Seeders;

use App\Models\Level;
use Illuminate\Database\Seeder;

class LevelSeeder extends Seeder
{

	public function run(): void
	{
		collect([
			// Collège
			[
				'name' => '6e',
				'description' => 'Sixième',
			],
			[
				'name' => '5e',
				'description' => 'Cinquième',
			],
			[
				'name' => '4e',
				'description' => 'Quatrième',
			],
			[
				'name' => '3e',
				'description' => 'Troisième',
			],
			// Lycée - A4
			[
				'name' => '2de A4',
				'description' => 'Seconde A4',
			],
			[
				'name' => '1re A4',
				'description' => 'Première A4',
			],
			[
				'name' => 'Tle A4',
				'description' => 'Terminale A4',
			],

			// Lycée - D
			[
				'name' => '2de C-D',
				'description' => 'Seconde C-D',
			],
			[
				'name' => '1re D',
				'description' => 'Première D',
			],
			[
				'name' => 'Tle D',
				'description' => 'Terminale D',
			],

			// Lycée - C
			[
				'name' => '1re C',
				'description' => 'Première C',
			],
			[
				'name' => 'Tle C',
				'description' => 'Terminale C',
			],

			// Lycée - F1
			[
				'name' => '2nde F1',
				'description' => 'Seconde F1',
			],
			[
				'name' => '1re F1',
				'description' => 'Première F1',
			],
			[
				'name' => 'Tle F1',
				'description' => 'Terminale F1',
			],

			// Lycée - F2
			[
				'name' => '2nde F2',
				'description' => 'Seconde F2',
			],
			[
				'name' => '1re F2',
				'description' => 'Première F2',
			],
			[
				'name' => 'Tle F2',
				'description' => 'Terminale F2',
			],
		])->each(fn($level) => Level::create($level));
	}
}
