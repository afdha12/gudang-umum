<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeders extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    User::updateOrInsert(
        ['email' => 'admin@admin.com'], // pencocokan berdasarkan email
        [
            'name' => 'admin',
            'username' => 'admin',
            'role' => 'admin',
            'password' => bcrypt('Hermina32'),
        ]
    );

    User::updateOrInsert(
        ['email' => 'arief@gmail.com'], // pencocokan berdasarkan email
        [
            'name' => 'Arief Sukmawan',
            'username' => 'arief',
            'role' => 'coo',
            'password' => bcrypt('Hermina32'),
        ]
    );
}

}
