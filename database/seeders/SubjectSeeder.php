<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Subject;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {       
        collect([
            ['description' => 'Mathématiques'
            ],
            ['description' => 'Physique'
            ],
            ['description' => 'Chimie'
            ],
            ['description' => 'Informatique'
            ],
            ['description' => 'Biologie'
            ],
        ])->each(fn($subject) => Subject::create($subject));
    }
}
