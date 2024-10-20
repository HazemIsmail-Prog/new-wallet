<?php

namespace Database\Seeders;

use App\Models\Contact;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contacts = array(
            array('id' => '1','name' => 'Amir Hazem','country_id' => '1','created_at' => '2023-06-28 05:19:04','updated_at' => '2023-06-30 20:33:49'),
            array('id' => '2','name' => 'Arab Consultants','country_id' => '1','created_at' => '2023-06-28 05:19:04','updated_at' => '2023-07-02 16:21:42'),
            array('id' => '3','name' => 'Kareem Amin','country_id' => '1','created_at' => '2023-07-04 03:40:53','updated_at' => '2023-07-04 03:40:53'),
            array('id' => '4','name' => 'Misk Al Dar','country_id' => '1','created_at' => '2023-07-16 23:20:19','updated_at' => '2023-07-16 23:20:19'),
            array('id' => '7','name' => 'Trio International','country_id' => '1','created_at' => '2023-07-31 18:43:17','updated_at' => '2023-07-31 18:43:17'),
            array('id' => '8','name' => 'Nour Hazem','country_id' => '1','created_at' => '2023-08-19 23:13:08','updated_at' => '2023-08-19 23:13:08'),
            array('id' => '9','name' => 'عبد الرحمن المرشد','country_id' => '1','created_at' => '2023-08-30 16:13:23','updated_at' => '2023-08-30 16:13:23'),
            array('id' => '10','name' => 'حسن لابيانو','country_id' => '1','created_at' => '2023-08-31 16:14:42','updated_at' => '2023-08-31 16:14:42'),
            array('id' => '11','name' => 'M Film','country_id' => '4','created_at' => '2023-09-02 12:52:47','updated_at' => '2023-09-02 12:52:47'),
            array('id' => '12','name' => 'وديعة بيت التمويل','country_id' => '1','created_at' => '2023-10-05 10:41:57','updated_at' => '2023-10-05 10:41:57'),
            array('id' => '13','name' => 'Stem','country_id' => '1','created_at' => '2023-10-16 19:52:25','updated_at' => '2023-10-16 19:52:25'),
            array('id' => '14','name' => 'Unipiles','country_id' => '1','created_at' => '2023-10-31 19:07:51','updated_at' => '2023-10-31 19:07:51'),
            array('id' => '15','name' => 'وليد زاك','country_id' => '1','created_at' => '2023-12-16 16:00:22','updated_at' => '2023-12-16 16:00:22'),
            array('id' => '16','name' => 'هيثم رستم','country_id' => '2','created_at' => '2024-01-08 13:05:14','updated_at' => '2024-01-08 13:05:14'),
            array('id' => '17','name' => 'عمرو سعودي','country_id' => '2','created_at' => '2024-01-17 15:09:16','updated_at' => '2024-01-17 15:09:16'),
            array('id' => '18','name' => 'سالي','country_id' => '2','created_at' => '2024-02-03 22:17:42','updated_at' => '2024-02-03 22:17:42'),
            array('id' => '19','name' => 'قرض السيارة','country_id' => '1','created_at' => '2024-02-06 14:47:51','updated_at' => '2024-02-06 14:47:51'),
            array('id' => '20','name' => 'ربيع 2','country_id' => '1','created_at' => '2024-03-11 09:28:16','updated_at' => '2024-03-11 09:28:16'),
            array('id' => '21','name' => 'FMH','country_id' => '1','created_at' => '2024-05-03 12:29:15','updated_at' => '2024-05-03 12:29:15'),
            array('id' => '22','name' => 'صابر عبد الستار','country_id' => '1','created_at' => '2024-05-08 04:33:11','updated_at' => '2024-05-08 04:33:11'),
            array('id' => '23','name' => 'ربيع','country_id' => '1','created_at' => '2024-05-22 08:51:00','updated_at' => '2024-05-22 08:51:00'),
            array('id' => '24','name' => 'مصطفى امين ','country_id' => '1','created_at' => '2024-07-29 17:35:29','updated_at' => '2024-07-29 17:35:29'),
            array('id' => '25','name' => 'On Hold','country_id' => '1','created_at' => '2024-10-07 06:34:10','updated_at' => '2024-10-07 06:34:10')
          );

          Contact::insert($contacts);
    }
}
