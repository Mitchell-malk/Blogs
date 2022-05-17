<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admin_user')->insert([
            ['id' => 1, 'name' => 'admin', 'password' => bcrypt(123456)],
            ['id' => 2, 'name' => 'admin1', 'password' => bcrypt(123456)],
            ['id' => 3, 'name' => 'admin2', 'password' => bcrypt(123456)],
        ]);
    }
}
