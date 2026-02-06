<?php

namespace Database\Factories;

use App\Models\Custom\Item;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ItemFactory extends Factory
{
    protected $model = Item::class;

    public function definition()
    {
        $name = $this->faker->words(3, true);

        return [
            'sku'           => strtoupper($this->faker->bothify('SKU-####')),
            'name'          => $name,
            'slug'          => Str::slug($name),
            'supplier_id'   => $this->faker->numberBetween(1, 10),
            'location'      => $this->faker->word(),
            'category_id'   => $this->faker->numberBetween(1, 4),
            'type_id'       => $this->faker->numberBetween(1, 3),
            'image_cover'   => null, // or fake image path
            'price'         => $this->faker->randomFloat(2, 50, 5000),
            'minimum_stock' => $this->faker->numberBetween(1, 50),
            'is_inventory'  => $this->faker->boolean(),
        ];
    }
}
