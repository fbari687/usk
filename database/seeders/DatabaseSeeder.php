<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Member;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Admin::create([
            'f_nama' => 'Admin Perpustakaan',
            'f_username' => 'admin',
            'f_password' => bcrypt('admin123'),
            'f_level' => 'Admin',
            'f_status' => 'Aktif'
        ]);

        Member::create([
            'f_nama' => 'Fadli',
            'f_username' => 'fadli',
            'f_password' => bcrypt('rahasia'),
            'f_tempatlahir' => 'Jakarta',
            'f_tanggallahir' => '2005-08-17'
        ]);
    }
}
