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
                'province' => 'MISAMIS OCCIDENTAL',
                'city' => 'TANGUB CITY',
                'barangay' => 'CANIANGAN',
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
                'province' => 'MISAMIS OCCIDENTAL',
                'city' => 'TANGUB CITY',
                'barangay' => 'CANIANGAN',
                'street' => 'P-6',
                'email' => 'jonehter@dev.com',
                'contact_no' => '09167789585',
                'role' => 'USER',
                'password' => Hash::make('a')
            ],




        ];

        \App\Models\User::insertOrIgnore($data);
    }
}
