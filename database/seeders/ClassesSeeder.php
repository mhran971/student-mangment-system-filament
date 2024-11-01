<?php

namespace Database\Seeders;

use App\Models\Classes;
use App\Models\Section;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class ClassesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        classes::factory()
            ->count(10)
            ->sequence(fn($sequance) => ['name' => 'class' . $sequance->index + 1])
            ->has(
                Section::factory()
                    ->count(2)
                    ->state(
                        new sequence(
                            ['name' => 'Section A'],
                            ['name' => 'Section B'],

                        )
                    )
                    ->has(
                        Student::factory()
                             ->count(5)
                                 ->state(
                                    function (array $attributes , Section $section){
                                        return ['class_id' => $section->class_id];
                                    }
                                )
                                ))->create();



    }
}
