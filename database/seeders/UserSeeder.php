<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'id' => 1,
                'name' => '张三',
                'email' => 'zhangsan@163.com',
                'password' => bcrypt('123123'), // hash加密
            ],
            [
                'id' => 2,
                'name' => '李四',
                'email' => 'lisi@163.com',
                'password' => bcrypt('123123'), // hash加密
            ],
            [
                'id' => 3,
                'name' => '王五',
                'email' => 'wangwu@163.com',
                'password' => bcrypt('123123'), // hash加密
            ],
            [
                'id' => 4,
                'name' => '赵六',
                'email' => '赵六@163.com',
                'password' => bcrypt('123123'), // hash加密
            ],
            [
                'id' => 5,
                'name' => 'Bob',
                'email' => 'Bob@163.com',
                'password' => bcrypt('123123'), // hash加密
            ],
        ]);
    }
}
