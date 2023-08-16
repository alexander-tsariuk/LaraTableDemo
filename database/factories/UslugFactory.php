<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Uslug>
 */
class UslugFactory extends Factory
{
    protected $units = [];
    
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        if (empty($this->units)) {
            dump('test');
            $this->units = \App\Models\Unit::pluck('id')->toArray();
        }
        return [
            'name' => fake()->name(),
            'unit_id' => fake()->randomElement($this->units),
            'vendor_code' => Str::random(5),
            'buy_price' => fake()->numberBetween(100, 20000),
            'sale_price' => fake()->numberBetween(200, 50000)
        ];
    }
}
