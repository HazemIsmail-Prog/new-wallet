<?php

namespace Database\Seeders;

use App\Models\Wallet;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WalletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $wallets = array(
            array('id' => '1','name' => 'Wallet','country_id' => '1','init_amount' => '5250','order' => '1','is_visible' => '1','color' => '#6B7280','created_at' => '2023-06-28 05:19:04','updated_at' => '2023-06-28 05:35:39'),
            array('id' => '2','name' => 'Hazem - KFH','country_id' => '1','init_amount' => '351500','order' => '1','is_visible' => '1','color' => '#046C4E','created_at' => '2023-06-28 05:19:04','updated_at' => '2023-06-30 20:46:58'),
            array('id' => '3','name' => 'Hazem - Al Waha','country_id' => '1','init_amount' => '1661','order' => '1','is_visible' => '1','color' => '#1E429F','created_at' => '2023-06-28 05:19:04','updated_at' => '2023-06-28 17:52:57'),
            array('id' => '4','name' => 'Norhan - KFH','country_id' => '1','init_amount' => '1500000','order' => '1','is_visible' => '1','color' => '#E74694','created_at' => '2023-06-28 05:19:04','updated_at' => '2023-06-28 05:23:09'),
            array('id' => '7','name' => 'CBK','country_id' => '3','init_amount' => '1607201','order' => '1','is_visible' => '1','color' => '#4B5563','created_at' => '2023-06-30 10:45:41','updated_at' => '2023-06-30 12:15:44'),
            array('id' => '17','name' => 'حسابي','country_id' => '4','init_amount' => '1000000','order' => '1','is_visible' => '1','color' => '#31C48D','created_at' => '2023-09-02 12:51:06','updated_at' => '2023-09-02 12:51:06'),
            array('id' => '20','name' => 'Wallet Egy','country_id' => '2','init_amount' => '213500','order' => '1','is_visible' => '1','color' => '#9CA3AF','created_at' => '2023-12-30 11:12:41','updated_at' => '2023-12-30 11:12:41'),
            array('id' => '21','name' => 'NBE Hazem','country_id' => '2','init_amount' => '5036166','order' => '1','is_visible' => '1','color' => '#057A55','created_at' => '2023-12-30 11:18:43','updated_at' => '2023-12-30 11:18:43'),
            array('id' => '22','name' => 'NBE Norhan','country_id' => '2','init_amount' => '541544','order' => '1','is_visible' => '1','color' => '#E74694','created_at' => '2023-12-30 11:19:17','updated_at' => '2023-12-30 11:20:48'),
            array('id' => '23','name' => 'Hazem - CBK','country_id' => '1','init_amount' => '0','order' => '1','is_visible' => '1','color' => '#E3A008','created_at' => '2024-08-24 19:05:06','updated_at' => '2024-08-24 19:05:06')
          );

          Wallet::insert($wallets);
    }
}
