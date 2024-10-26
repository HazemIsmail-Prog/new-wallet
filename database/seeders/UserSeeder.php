<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = array(
            array('id' => '1', 'name' => 'Hazem', 'email' => 'hazem.ismail@hotmail.com', 'last_selected_country_id' => '1', 'email_verified_at' => NULL, 'password' => '$2y$10$EyyOpni7lC9bzlOeuMGEOOEMlU9mxig0FiD46W.FSlbJrVlqXvCju', 'remember_token' => 'X1mIbHq4bC3E7BHaGDYa6WAbcinKzK7GPyNb3SaltIoTZrI9IBqyoo4HIrFG', 'created_at' => '2023-06-28 05:19:04', 'updated_at' => '2024-10-06 23:57:15'),
            array('id' => '2', 'name' => 'Test', 'email' => 'test@test.com', 'last_selected_country_id' => '3', 'email_verified_at' => NULL, 'password' => '$2y$10$FY/zGi0ef7BLnQD/HDq7iOA5vqRRIOEcFjvg2Fh8NSl9maRAk0Pci', 'remember_token' => NULL, 'created_at' => '2023-06-29 16:29:29', 'updated_at' => '2023-06-29 21:17:58'),
            array('id' => '3', 'name' => 'Mina Adel', 'email' => 'minaadel1085@gmail.com', 'last_selected_country_id' => '4', 'email_verified_at' => NULL, 'password' => '$2y$10$7lD/GOe1hwonjH8drJVnMOhr.xJAuHAqtGROhdpmlnwGtY7iLhL5q', 'remember_token' => NULL, 'created_at' => '2023-09-02 12:49:25', 'updated_at' => '2023-09-02 12:50:07')
        );

        User::withoutGlobalScopes()->insert($users);
    }
}
