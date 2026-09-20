<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $name = $this->faker->words(3, true);
        
        return [
            'name' => ucwords($name),
            'slug' => Str::slug($name),
            'sku' => 'SKU-' . strtoupper($this->faker->bothify('??##-##')),
            'description' => $this->faker->paragraph(),
            'price' => $this->faker->randomFloat(2, 10, 500), // Prices between 10 and 500
        ];
    }
}
