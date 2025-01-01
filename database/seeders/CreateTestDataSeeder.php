<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Place;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class CreateTestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory(5)->create();
        Category::factory(5)->create();
        Place::factory(20)
            ->has(Review::factory(5)->state(['user_id' => User::query()->value('id')]))
            ->create();
        $places = [];
        foreach (Place::all() as $place) {
            $places[] = $place->id;
            if (count($places) % 5 === 0) {
                $offset = count($places) / 5 - 1;
                Category::limit(1)->offset($offset)->first()->places()->attach(array_chunk($places, 5)[$offset]);
            }
        }
    }
}
