<?php

namespace App\Http\Controllers;

use App\Http\Response\ResponseJson;
use App\Models\Zan;
use Illuminate\Http\Request;

class ZanController extends Controller
{
    use ResponseJson;
    public function createZan($id){
        // 获取点赞的用户id
        $user_id = \Auth::id();
        // 判断是否已经点赞
        $zan = Zan::where('user_id',$user_id)->where('article_id',$id)->first();
        if($zan){
            return $this->json(400, '已经点赞过了');
        }
        // 创建点赞
        Zan::create([
            'user_id' => $user_id,
            'article_id' => $id,
        ]);
        // 返回点赞成功
        return $this->json(200, '点赞成功');
    }

    public function deleteZan($id){
        // 获取点赞的用户id
        $user_id = \Auth::id();
        // 判断是否已经点赞
        $zan = Zan::where('user_id', $user_id)->where('article_id', $id)->first();
        if(!$zan){
            return $this->json(400, '还没有点赞过');
        }
        // 删除点赞
        $zan->delete();
        // 返回点赞成功
        return $this->json(200, '取消点赞成功');
    }
}
