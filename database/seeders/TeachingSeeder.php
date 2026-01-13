<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Teaching;
use App\Models\Subject;
use App\Models\Level;
use App\Models\Repeater;


class TeachingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $subjects = Subject::pluck('id')->toArray();
        $levels = Level::pluck('id')->toArray();
        $repeaters = Repeater::pluck('id')->toArray();

        
        collect([
            [
                'title' => 'Algebra Basics',
                'subject_id' => $subjects[array_rand($subjects)],
                'level_id' => $levels[array_rand($levels)],
                'repeater_id' => $repeaters[array_rand($repeaters)],
            ],
            [
                'title' => 'Introduction to Physics',
                'subject_id' => $subjects[array_rand($subjects)],
                'level_id' => $levels[array_rand($levels)],
                'repeater_id' => $repeaters[array_rand($repeaters)],
            ],
            [
                'title' => 'Organic Chemistry',
                'subject_id' => $subjects[array_rand($subjects)],
                'level_id' => $levels[array_rand($levels)],
                'repeater_id' => $repeaters[array_rand($repeaters)],
            ],
            [
                'title' => 'Programming 101',
                'subject_id' => $subjects[array_rand($subjects)],
                'level_id' => $levels[array_rand($levels)],
                'repeater_id' => $repeaters[array_rand($repeaters)],
            ],
            [
                'title' => 'Cell Biology',
                'subject_id' => $subjects[array_rand($subjects)],
                'level_id' => $levels[array_rand($levels)],
                'repeater_id' => $repeaters[array_rand($repeaters)],
            ],
        ])->each(fn($teaching) => Teaching::create($teaching));

    }
}

