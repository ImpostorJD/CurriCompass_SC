<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
        'userfname' => "Admin",
        'userlname' => "Admin",
        'usermiddle' => "Admin",
        'contact_no' => "091234567",
        'email' => "administrator@baliuagu.edu.ph",
        'password' => Hash::make("admin"),
        ]);

        DB::table('user__roles')->insert([
            'userid' => 1,
            'roleid' => 1,
        ]);

    }
}
