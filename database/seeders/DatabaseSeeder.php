<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Car;
use App\Models\Category;
use App\Models\Part;
use App\Models\Store;
use App\Models\TyreSize;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{   
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin
        User::factory(1)
        ->has(Car::factory(2))  // Cars
        ->create([
            'username' => 'admin',
            'password' => bcrypt('jiahow123'),
        ]);

        Schema::disableForeignKeyConstraints();


        // Stores
        // $stores_filepath = '/json_data/stores.json';
        // $stores = Storage::json($stores_filepath);
        // foreach ($stores as $index => $store) {
        //     Store::create([
        //         'name' => $store['name'],
        //         'email' => "store$index@hotmail.com",
        //         'slug' => Str::slug($store['name']),
        //         'address' => $store['vicinity'],
        //         'place_id' => $store['place_id'],
        //         'uid' => $index,
        //         'token' => null
        //     ]);
        // };
        

        // Category
        $category_filepath = '/json_data/categories.json';
        $categories = Storage::json($category_filepath);
        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => $category['slug'],
            ]);
        };

        // Spare Parts
        $categories = Category::all();
        $part_filepath = '/json_data/spare_parts.json';
        $parts = Storage::json($part_filepath);
        foreach ($parts as $category_slug => $parts) {
            foreach ($parts as $part) {
                $newPart = Part::create([
                        'name' => $part['name'],
                        'brand_name' => $part['brand_name'],
                        'description' => $part['description'],
                        'stock' => $part['stock'] ?? 0,
                        'price' => $part['price'],
                        'category_id' => $categories->where('slug', $category_slug)->first()->id,
                        'store_id' => 1,
                    ]);
                $newPart->images()->create([
                    'url' => $part['image']
                ]);
            }
        };

        // Tyre sizes
        $tyreSize_filepath = '/json_data/tyre_sizes.json';
        $tyreSizes = Storage::json($tyreSize_filepath);
        foreach ($tyreSizes as $size) {
            TyreSize::create([
                "inch" => $size['inch'],
                "width" => $size['width']
            ]);
        }

        // Attach tires with sizes
        $tyres = Part::whereHas('category', function($query) {
            $query->where('slug', 'tyre');
        })->get();
        $sizes = TyreSize::inRandomOrder()->get();
        foreach($tyres as $tyre) {
            $tyre->availableInchs()->attach($sizes->random()->id, ['stock' => rand(1,50)]);
        }

        // // Stores (json)
        // $stores_data = Storage::json('/json_data/stores.json');
        // // Spare parts (json)
        // $parts = Storage::json('/json_data/spare_parts.json');

        // // Create stores and parts
        // foreach ($stores_data as $store_data) {
        //     $store = Store::create([
        //         'name' => $store_data['name'],
        //         'slug' => $store_data['slug'],
        //         'address' => $store_data['address'],
        //         'lat' => $store_data['lat'],
        //         'lng' => $store_data['lng'],
        //         'rating' => fake()->numberBetween(3,5)
        //     ]);
        //     // Create 5 random parts for store
        //     for ($i = 0; $i < 3; $i++) {
        //         $randomCategory = array_rand($parts);
        //         $random_parts = $parts[$randomCategory];
        //         foreach ($random_parts as $random_part) {
        //             // Part
        //             $part = $store->parts()->create([
        //                 'name' => $random_part['name'],
        //                 'brand_name' => $random_part['brand_name'],
        //                 'description' => fake()->sentence(20),
        //                 'price' => number_format(fake()->numberBetween(50, 300), 2),
        //                 'stock' => fake()->numberBetween(0, 10),
        //                 'category_id' => $categories->where('slug', $randomCategory)->first()->id,
        //             ]);
        //             // Image
        //             $part->images()->create([
        //                 'url' => $random_part['image']
        //             ]);
        //         }
        //     }
        // }

        // Build car and parts M-M relationshio
        $parts = Part::all();
        $cars = Car::inRandomOrder()->get();

        foreach ($parts as $part) {
            $part->cars()->attach($cars->random()->id);
        }

    }
}
