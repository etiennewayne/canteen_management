<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class OfficeSeeder extends Seeder
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
                'office' => 'IBFS',
            ],
            [
                'office' => 'ICJE',
            ],
            [
                'office' => 'PROPERTY CUSTODIAN',
            ],
            [
                'office' => 'HR OFFICE',
            ],
            [
                'office' => 'VP ADMIN',
            ],
        ];

        \App\Models\Office::insertOrIgnore($data);


    }
}
