<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Str;
use DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  public function run()
  {
    DB::table('users')->insert([
      'name' => 'Administrator',
      'email' => 'admin@email.com',
      'password' => Hash::make('admin123'),
      'token' => Str::random(20),
      'role_id' => 1,
      'created_at' => date('Y-m-d H:i:s'),
    ]);

    $roles = [
      [
        'name' => 'Administrator',
        'created_at' => date('Y-m-d H:i:s'),
      ],
      [
        'name' => 'User',
        'created_at' => date('Y-m-d H:i:s'),
      ]
    ];
    DB::table('roles')->insert($roles);
  }
}
