<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\UserRoleEnum;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            "name"=> "Admin User",
            "email"=> "admin@example.com",
            "password"=> Hash::make('password'),
            'role'=> UserRoleEnum::SUPER_ADMIN,
             
        ]);
        
        User::create([
            'name'=> 'Manager User',
            'email'=> 'manager@eample.com',
            'password'=> Hash::make('password'),
            'role'=> UserRoleEnum::INVENTORY_MANAGER,
            ]);


        User::create([
            'name'=> 'Basic User',
            'email'=> 'user@example.com',
            'password'=> Hash::make('password'),
            'role'=> UserRoleEnum::INVENTORY_USER,
            ]);
    }
}
