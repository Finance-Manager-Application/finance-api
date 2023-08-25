<?php

namespace Database\Seeders\Defaults;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        $usersData = require database_path('seeders/Defaults/Data/users.php');

        foreach ($usersData as $userData) {
            User::create([
                'first_name' => $userData['first_name'],
                'last_name' => $userData['last_name'],
                'email' => $userData['email'],
                'password' => $userData['password'],
                'address' => $userData['address'],
            ]);
        }
    }
}
