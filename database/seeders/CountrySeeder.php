<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = array(
            array('id' => '1','name' => 'Kuwait','user_id' => '1','currency' => 'kwd','decimal_points' => '3','created_at' => '2023-06-28 05:19:04','updated_at' => '2023-06-28 05:19:04'),
            array('id' => '2','name' => 'Egypt','user_id' => '1','currency' => 'egp','decimal_points' => '2','created_at' => '2023-06-28 05:19:04','updated_at' => '2023-06-28 05:19:04'),
            array('id' => '3','name' => 'Kuwait','user_id' => '2','currency' => 'kwd','decimal_points' => '3','created_at' => '2023-06-29 21:17:58','updated_at' => '2023-06-29 21:17:58'),
            array('id' => '4','name' => 'Kuwait','user_id' => '3','currency' => 'KD','decimal_points' => '3','created_at' => '2023-09-02 12:49:51','updated_at' => '2023-09-02 12:49:51')
          );

          Country::withoutGlobalScopes()->insert($countries);
    }
}
