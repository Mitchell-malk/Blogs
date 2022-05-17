<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('article')->insert([
            [
                'id' => '6',
                'title' => '寓情于国，献礼于党',
                'content' => '在我眼中，党就像一位母亲，时刻关怀、体贴我们；党就像一位老师，教会了我们怎么做人；党就像亲人，朋友，在我们为难得时刻，
                会向我们伸出援助之手。我们应该感谢党，因为没有党，就没有我们今天的美好生活。党是中华民族的英勇缔造者和守护神，如果没有党，我们中华民族
                也许到尽头依旧是一个黑暗的旧中国...',
                'user_id' => rand(1, 0),
            ],
            [
                'id' => '7',
                'title' => '我爱中国共产党',
                'content' => '是啊！对于新中国的每一代人来说，中国共产党都好比事伟大的母亲，正是在她的温暖怀抱里，人们得到哺育和呵护，共享幸福和安康
                。而对于我来说，这感受尤其刻苦铭心...',
                'user_id' => rand(1, 5)
            ],
            [
                'id' => '8',
                'title' => '我们坚持一个中国',
                'content' => '我们坚持一个中国我们坚持一个中国我们坚持一个中国我们坚持一个中国我们坚持一个中国....',
                'user_id' => rand(1, 5),
            ],
            [
                'id' => '9',
                'title' => '巩固脱贫攻坚',
                'content' => '巩固脱贫攻坚我们在路上巩固脱贫攻坚我们在路上巩固脱贫攻坚我们在路上....',
                'user_id' => rand(1, 5),
            ],
            [
                'id' => '10',
                'title' => '服务乡村振兴',
                'content' => '乡村路由、乡村信息化、乡村建设....',
                'user_id' => rand(1, 5),
            ],
            [
                'id' => '11',
                'title' => '《我爱祖国》',
                'content' => '这就是我爱的祖国....',
                'user_id' => rand(1, 5),
            ],
            [
                'id' => '12',
                'title' => '我爱我的祖国',
                'content' => '我爱我的祖国，一刻也不能分割....',
                'user_id' => rand(1, 5),
            ],
            [
                'id' => '13',
                'title' => '抗击疫情，你我同行',
                'content' => '2020年春天，我们记住了无数的刻骨铭心，经历了太多的永生难忘....',
                'user_id' => rand(1, 5),
            ],
            [
                'id' => '14',
                'title' => '抗击疫情，你我同行',
                'content' => '一份份请战书，千万个红手印，那是对病毒的无言宣战',
                'user_id' => rand(1, 5),
            ],
            [
                'id' => '15',
                'title' => '百年风雨，砥砺奋进',
                'content' => '沧海桑田，有容乃大，不忘初心，砥砺前行',
                'user_id' => rand(1, 5),
            ],

        ]);
    }
}
