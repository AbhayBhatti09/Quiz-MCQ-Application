<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;



class AdminuserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email'=>'admin@mcqquiz.com'],
            ['name'=>'Admin',
            'password'=>Hash::make('admin@123'),
            'role_id'=>1,
        ],
        );
    }
}
