<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Repeater;

class RepeaterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {  
        collect([
            [   'approved_at' => now(), 
                'is_active' => true
            ],
            [   'approved_at' => null, 
                'is_active' => false
            ],
            [   'approved_at' => now(), 
                'is_active' => true
            ],
        ])->each(fn($repeater) => Repeater::create($repeater));
    }
}
