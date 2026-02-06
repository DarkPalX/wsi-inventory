<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $setting = [
            // 'id' => 1,
            'api_key' => '',
            'website_name' => 'WSI Inventory',
            'website_favicon' => \URL::to('/').'/theme/images/favicon.ico',
            'company_logo' => \URL::to('/').'/theme/images/hoti-logo-white.png',
            'company_favicon' => '',
            'company_name' => 'WebFocus Solutions Inc.',
            'company_about' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
            'company_address' => '795 Folsom Ave, Suite 600 San Francisco, CA 94107',
            'google_map' => 'https://www.google.com/maps?ll=14.584069,121.062934&z=17&t=m&hl=en&gl=PH&mapclient=embed&cid=4804121224053792784',
            'google_recaptcha_sitekey' => '6Lfgj7cUAAAAAJfCgUcLg4pjlAOddrmRPt86tkQK',
            'google_recaptcha_secret' => '6Lfgj7cUAAAAALOaFTbSFgCXpJldFkG8nFET9eRx',
            'data_privacy_title' => 'Privacy-Policy',
            'data_privacy_popup_content' => 'This website uses cookies to ensure you get the best experience.',
            'data_privacy_content' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.",
            'mobile_no' => '(1) 8547 632521',
            'fax_no' => '13232107114',
            'tel_no' => '(1) 11 4752 1433',
            'email' => 'info@canvas.com',
            'social_media_accounts' => '',
            'copyright' => '2022-2023',
            'user_id' => '1',

        ];

        DB::table('settings')->insert($setting);
    }
}
