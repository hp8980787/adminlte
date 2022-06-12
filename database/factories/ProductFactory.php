<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'sku' => now('Asia/Shanghai')->format('YmdHis') . Str::random(8),
            'category' => $this->category(),
            'brand' => $this->faker->shuffleString,
            'cover_img' => 'images/product/1WO0SjEFm6OxRzfBwYGxFq4siUmy9myuUwpgyBxC.jpg',
            'dy' => 'sadsa',
            'dl' => 'asdsadsa',
            'price_eu' => $this->faker->randomFloat(2, 2, 1000),
            'price_us' => $this->faker->randomFloat(2, 2, 1000),
            'price_uk' => $this->faker->randomFloat(2, 2, 1000),
            'price_jp' => $this->faker->randomFloat(2, 2, 1000),
            'status' => 1,
            'replace' => 'asdasd sadsadsa  asdsadsa xzczxczxczx',
            'description' => $this->faker->text,
            'stock' => rand(1, 99999),
        ];
    }

    public function category(): string
    {
        $array = ['battery', 'adapter'];
        return $array[array_rand($array, 1)];
    }
}
