<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Custom\Item;
use DB;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::table('items')->truncate();

        // FOR FAKER
        // Item::factory()->count(50)->create();


        // FOR CUSTOM FAKER SEEDING
        $faker = \Faker\Factory::create();

        $parts = [
            'HYDRAULIC HOSE',
            'SPRING PIN',
            'LEAF SPRING',
            'BRAKE CHAMBER',
            'OIL FILTER',
            'FUEL FILTER',
            'CONTROL CABLE',
            'TIE ROD END',
            'BALL JOINT',
            'WATER PUMP',
        ];

        $sizes = [
            '1/2x140"',
            '1/2x184"',
            '30x42x88',
            '32x42x88',
            '24 VOLTS',
        ];

        $brands = [
            'HINO',
            'ISUZU',
            'TOYOTA',
            'GATES',
            'DENSO',
        ];

        $suffixes = [
            'ASSY.',
            'DOUBLE',
            'JAPAN SURPLUS',
            null,
        ];

        $locations = [
            'C1B-006',
            'C1B-007',
            'C1B-008',
            'C2A-055',
            'C2A-056',
            'C2A-058',
            'C2C-075',
            'C2C-076',
            'C2C-077',
            'C3D-010',
            'C3D-011',
            'C3D-012',
            'OUTSIDE PARTS STORAGE',
        ];


        $items = [];

        for ($i = 0; $i < 70; $i++) {
            $name = collect([
                $faker->randomElement($parts),
                $faker->optional()->randomElement($sizes),
                $faker->optional()->randomElement($brands),
                $faker->optional()->randomElement($suffixes),
            ])->filter()->implode(' ');

            $items[] = [
                'sku' => '20240' . str_pad($i + 100, 4, '0', STR_PAD_LEFT),
                'name' => $name,
                'slug' => \Str::slug($name),
                'category_id' => rand(1, 4),
                'type_id' => rand(1, 4),
                'supplier_id' => '['. rand(1, 3) . ']',
                'location' => $locations[array_rand($locations)],
                'price' => rand(100, 1000),
                'is_inventory' => rand(0, 1),
                'minimum_stock' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }


        \DB::table('items')->insert($items);
    }
}
