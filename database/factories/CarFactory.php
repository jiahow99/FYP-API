<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Car>
 */
class CarFactory extends Factory
{
    // private $cars_data;

    // public function __construct()
    // {
    //     $this->cars_data = Storage::json('json_data/cars.json');
    // }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Select random car
        $random_car = fake()->randomElement(Storage::json('json_data/cars.json'));

        return [
            'name' => $random_car['name'],
            'brand' => $random_car['brand'],
            'year_make' => $random_car['year'],
            'mileage' => fake()->numberBetween(1000, 80000),
        ];
    }
}
