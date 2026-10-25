<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Dataawal extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = new User();
        $user->name = 'Admin';
        $user->email = 'admin@kasir.com';
        $user->password = bcrypt('admin');
        $user->peran = 'admin';
        $user->save();
    }
}
