<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            'nama' => 'Administrator',
            
            // ===================================
            // == EMAIL BARU DITAMBAHKAN ==
            // ===================================
            'email' => 'admin@smartpresence.com',
            
            'nik' => '0000', 
            'username' => 'admin',
            'password' => Hash::make('password'),
            'jabatan' => 'Administrator',
            'alamat' => 'Kantor Pusat',
            'no_hp' => '08123456789',
            'role' => 'admin',
            'status' => 'aktif',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}