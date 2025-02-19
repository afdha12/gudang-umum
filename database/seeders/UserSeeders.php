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
    }
}
