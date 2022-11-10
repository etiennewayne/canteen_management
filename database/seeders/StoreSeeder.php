<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        $data = [
            [
                'store' => 'VILMA STORE',
                'user_id' => 3,
                'owner' => 'VILMA SANTOS',
                'contact_no' => '1234567890',
            ]
        ];

        \App\Models\Store::insertOrIgnore($data);
    }
}
