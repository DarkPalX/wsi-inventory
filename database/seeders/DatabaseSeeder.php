<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $this->users();
        $this->role_permission();
        $this->item_categories();
        $this->item_types();
        $this->suppliers();
        $this->receivers();
        $this->vehicles();
        
        $this->call([
            SettingSeeder::class,
            RoleSeeder::class,
            ItemSeeder::class,
            // PurchaseOrderSeeder::class,
            // ReceivingTransactionSeeder::class,
            // IssuanceTransactionSeeder::class,

            // MenuSeeder::class,
            // MenusHasPagesSeeder::class,
            // PageSeeder::class,
            // AlbumSeeder::class,
            // OptionSeeder::class,
            // BannerSeeder::class,
        ]);
    }

    private function users()
    {
        $users = [
            [
                'name' => 'Admin Istrator',
                'firstname' => 'admin',
                'middlename' => 'user',
                'lastname' => 'istrator',
                'email' => 'wsiprod.demo@gmail.com',
                'email_verified_at' => now(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'remember_token' => Str::random(10),
                'role_id' => 1,
                'is_active' => 1,
                'user_id' => 1,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
                'mobile' => '09456714321',
                'phone' => '022646545',
                'address_street' => 'Maharlika St',
                'address_city' => 'Pasay',
                'address_zip' => '1234'
            ],
            // [
            //     'name' => 'App Rover',
            //     'firstname' => 'App',
            //     'middlename' => 'Ro',
            //     'lastname' => 'Rover',
            //     'email' => 'approver',
            //     'email_verified_at' => now(),
            //     'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            //     'remember_token' => Str::random(10),
            //     'role_id' => 2,
            //     'is_active' => 1,
            //     'user_id' => 2,
            //     'created_at' => date("Y-m-d H:i:s"),
            //     'updated_at' => date("Y-m-d H:i:s"),
            //     'mobile' => '09456714321',
            //     'phone' => '022646545',
            //     'address_street' => 'Maharlika St',
            //     'address_city' => 'Pasay',
            //     'address_zip' => '1234'
            // ]
        ];

        DB::table('users')->insert($users);
    }
    
    private function role_permission()
    {
        $role_permission = [
            [
                'module_id' => 1,
                'role_id' => 1,
                'permission_id' => 1,
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'module_id' => 1,
                'role_id' => 1,
                'permission_id' => 2,
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'module_id' => 2,
                'role_id' => 1,
                'permission_id' => 1,
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'module_id' => 2,
                'role_id' => 1,
                'permission_id' => 2,
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'module_id' => 2,
                'role_id' => 1,
                'permission_id' => 3,
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'module_id' => 3,
                'role_id' => 1,
                'permission_id' => 1,
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'module_id' => 3,
                'role_id' => 1,
                'permission_id' => 2,
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'module_id' => 3,
                'role_id' => 1,
                'permission_id' => 3,
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'module_id' => 4,
                'role_id' => 1,
                'permission_id' => 1,
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'module_id' => 4,
                'role_id' => 1,
                'permission_id' => 2,
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'module_id' => 4,
                'role_id' => 1,
                'permission_id' => 3,
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            
            [
                'module_id' => 1,
                'role_id' => 4,
                'permission_id' => 1,
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'module_id' => 1,
                'role_id' => 4,
                'permission_id' => 2,
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'module_id' => 2,
                'role_id' => 4,
                'permission_id' => 1,
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'module_id' => 2,
                'role_id' => 4,
                'permission_id' => 2,
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'module_id' => 2,
                'role_id' => 4,
                'permission_id' => 3,
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'module_id' => 3,
                'role_id' => 4,
                'permission_id' => 1,
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'module_id' => 3,
                'role_id' => 4,
                'permission_id' => 2,
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'module_id' => 3,
                'role_id' => 4,
                'permission_id' => 3,
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'module_id' => 4,
                'role_id' => 4,
                'permission_id' => 1,
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'module_id' => 4,
                'role_id' => 4,
                'permission_id' => 2,
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'module_id' => 4,
                'role_id' => 4,
                'permission_id' => 3,
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
    
        DB::table('role_permission')->insert($role_permission);
    }
    
    private function item_categories()
    {
        $item_categories = [
            [
                'name' => 'Home Appliances',
                'slug' => 'home-appliances',
                'description' => 'home',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ], 

            [
                'name' => 'School Supplies',
                'slug' => 'school-supplies',
                'description' => 'school',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ], 

            [
                'name' => 'Kitchen Wares',
                'slug' => 'kitchen-wares',
                'description' => 'Sanaol',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ], 

            [
                'name' => 'Gadgets',
                'slug' => 'gadgets',
                'description' => 'edi wow',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ]
        ];

        DB::table('item_categories')->insert($item_categories);
    }
    
    private function item_types()
    {
        $item_types = [
            [
                'name' => 'Piece',
                'slug' => 'piece',
                'description' => 'Piece',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ], 

            [
                'name' => 'Box',
                'slug' => 'box',
                'description' => 'Box',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ], 

            [
                'name' => 'Jumbo Box',
                'slug' => 'jumbo-box',
                'description' => 'Jumbo Box',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ], 

            [
                'name' => 'Cellophane',
                'slug' => 'cellophane',
                'description' => 'Cellophane',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ]
        ];

        DB::table('item_types')->insert($item_types);
    }

    private function suppliers()
    {
        $suppliers = [
            [
                'name' => 'Maligaya Printers',
                'address' => 'Davao City',
                'cellphone_no' => '09987654321',
                'telephone_no' => '2287000',
                'check_no' => '20251201001',
                'tin_no' => '1234-5678-9012',
                'email' => 'maligaya@wsi.com',
                'bank_name' => 'BPI',
                'bank_account_no' => '7823648934',
                'is_vatable' => 1,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ], 

            [
                'name' => 'Epson Printer',
                'address' => 'Davao City',
                'cellphone_no' => '09987654321',
                'telephone_no' => '2287000',
                'check_no' => '20251201002',
                'tin_no' => '5678-1234-9012',
                'email' => 'epson@wsi.com',
                'bank_name' => 'BPI',
                'bank_account_no' => '782362323',
                'is_vatable' => 0,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ], 

            [
                'name' => 'Asus',
                'address' => 'Davao City',
                'cellphone_no' => '09987654321',
                'telephone_no' => '2287000',
                'check_no' => '20251201003',
                'tin_no' => '9012-1234-5678',
                'email' => 'asus@wsi.com',
                'bank_name' => 'BPI',
                'bank_account_no' => '823844342',
                'is_vatable' => 1,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ]
        ];

        DB::table('suppliers')->insert($suppliers);
    }

    private function receivers()
    {
        $receivers = [
            [
                'name' => 'Bureau of Immigrations',
                'address' => 'Davao City',
                'contact' => '09987654321',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ], 
            [
                'name' => 'Commmission on Audit',
                'address' => 'Buhangin, Davao City',
                'contact' => '09987654321',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ], 
            [
                'name' => 'San Miguel',
                'address' => 'Panabo City',
                'contact' => '09987654321',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ]
        ];

        DB::table('receivers')->insert($receivers);
    }
    
    private function vehicles()
    {
        $vehicles = [
            [
                'name' => null,
                'slug' => null,
                'plate_no' => 'LXY 576',
                'type' => 'SHUTTLE',
                'driver' => null,
                'description' => 'Truck',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ], 

            [
                'name' => null,
                'slug' => null,
                'plate_no' => 'LXZ 810',
                'type' => 'EQUIPMENT',
                'driver' => null,
                'description' => 'Truck',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ], 

            [
                'name' => null,
                'slug' => null,
                'plate_no' => 'LZS 245',
                'type' => 'TRUCK',
                'driver' => null,
                'description' => 'Truck',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ], 

            [
                'name' => null,
                'slug' => null,
                'plate_no' => 'LYR 143',
                'type' => 'TRAILER',
                'driver' => null,
                'description' => 'Truck',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ]
        ];

        DB::table('vehicles')->insert($vehicles);
    }
}
