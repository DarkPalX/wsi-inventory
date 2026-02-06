<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Banner::insert([
            [
                'album_id' => 1,
                'image_path' => \URL::to('/').'/theme/images/banners/image1.jpg',
                'title' => 'Best way to save your Money.',
                'description' => 'Interactively seize bricks-and-clicks channels before empowered users. Uniquely maximize bleeding-edge outsourcing.',
                'alt' => 'Banner 1',
                'url' => \URL::to('/'),
                'order' => 1,
                'user_id' => 1,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],
            [
                'album_id' => 1,
                'image_path' => \URL::to('/').'/theme/images/banners/image2.jpg',
                'title' => 'Beautifully Flexible',
                'description' => 'Looks beautiful &amp; ultra-sharp on Retina Screen Displays. Powerful Layout with Responsive functionality that can be adapted to any screen size.',
                'alt' => null,
                'url' => null,
                'order' => 2,
                'user_id' => 1,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],
            [
                'album_id' => 1,
                'image_path' => \URL::to('/').'/theme/images/banners/image3.jpg',
                'title' => 'Great Performance',
                'description' => 'You\'ll be surprised to see the Final Results of your Creation &amp; would crave for more.',
                'alt' => null,
                'url' => null,
                'order' => 3,
                'user_id' => 1,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],

            [
                'album_id' => 2,
                'image_path' => \URL::to('/').'/theme/images/banners/sub1.jpg',
                'title' => null,
                'description' => null,
                'alt' => null,
                'url' => null,
                'order' => 2,
                'user_id' => 1,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],
            [
                'album_id' => 2,
                'image_path' => \URL::to('/').'/theme/images/banners/sub2.jpg',
                'title' => null,
                'description' => null,
                'alt' => null,
                'url' => null,
                'order' => 3,
                'user_id' => 1,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ]
        ]);
    }
}
