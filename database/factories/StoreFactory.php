<?php

namespace Database\Factories;

use App\Models\Part;
use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Store>
 */
class StoreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'rating' => fake()->numberBetween(3,5),
        ];
    }

    
    /**
     * Configure the model factory.
     */
    public function configure(): static
    {
        $parts = Part::inRandomOrder()->limit(5)->get();

        return $this->afterCreating(function (Store $store) use ($parts) {
            $store->parts()->createMany($parts);
        });
    }
}
