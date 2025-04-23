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
        User::insert([
            'name'=>'admin',
            'username'=>'admin',
            'email'=>'admin@admin.com',
            'role'=>'admin',
            'password'=>bcrypt('Hermina32'),
        ]);

        User::insert([
            'name'=>'Arief Sukmawan',
            'username'=>'arief',
            'email'=>'arief@gmail.com',
            'role'=>'coo',
            'password'=>bcrypt('Hermina32'),
        ]);
    }
}
