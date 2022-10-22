<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
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
                'username' => 'admin',
                'lname' => 'SERINO',
                'fname' => 'VILMA',
                'mname' => 'P',
                'sex' => 'MALE',
                'province' => '1265',
                'city' => '126510',
                'barangay' => '126510026',
                'street' => 'P-6',
                'email' => 'admin@dev.com',
                'contact_no' => '09167789585',
                'role' => 'ADMINISTRATOR',
                'password' => Hash::make('a')
            ],

            //client
            [
                'username' => 'abadia',
                'lname' => 'ABADIA',
                'fname' => 'JONETHER',
                'mname' => 'P',
                'sex' => 'MALE',
                'province' => '1265',
                'city' => '126510',
                'barangay' => '126510026',
                'street' => 'P-6',
                'email' => 'jonehter@dev.com',
                'contact_no' => '09167789585',
                'role' => 'CUSTOMER',
                'password' => Hash::make('a')
            ],

            [
                'username' => 'vilma',
                'lname' => 'SERINO',
                'fname' => 'VILMA',
                'mname' => 'P',
                'sex' => 'MALE',
                'province' => '1265',
                'city' => '126510',
                'barangay' => '126510026',
                'street' => 'P-6',
                'email' => 'vilma@dev.com',
                'contact_no' => '09167789585',
                'role' => 'VENDOR',
                'password' => Hash::make('a')
            ],




        ];

        \App\Models\User::insertOrIgnore($data);
    }
}
