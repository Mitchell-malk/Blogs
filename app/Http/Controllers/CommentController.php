<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Validator;

/**
 *  评论控制器
 */
class CommentController extends Controller
{
    /**
     * 创建一个新的评论
     * @POST (comment/{id})
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, $id)
    {
        $data = $request->all();
        // 验证器
        $rules = [
            'comment' => 'required',
        ];

        // 自定义信息
        $msg = [
            'required' => '评论内容不为空'
        ];

        $validate = Validator::make($data, $rules, $msg);
        if ($validate->fails()) {
            return $this->json(422, $validate->errors());
        }

        // 获取当前登录用户
        $user_id = \Auth::id();
        $article_id = $id;
        // 创建评论
        $comment = Comment::create([
            'user_id' => $user_id,
            'article_id' => $article_id,
            'comment' => $data['comment']
        ]);
        return $this->json(200, '评论成功', $comment);
    }

    /**
     * 修改评论
     * @PUT (comment/{id})
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        // 验证器
        $rules = [
            'comment' => 'required',
        ];
        // 自定义信息
        $msg = [
            'required' => '评论内容不为空'
        ];
        $validate = Validator::make($data, $rules, $msg);
        if ($validate->fails()) {
            return $this->json(422, $validate->errors());
        }
        // 判断是否有权限
        $comment = Comment::find($id);
        if ($comment->user_id != \Auth::id()) {
            return $this->json(403, '这不是你的评论无法修改');
        }
        $comment->comment = $data['comment'];
        $comment->save();
        return $this->json(200, '修改成功', $comment);
    }

    /**
     * 删除评论
     * @DELETE (comment/{id})
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $comment = Comment::find($id);
        if (!$comment) {
            return $this->json(404, '评论不存在');
        }
        // 判断是否是当前用户的评论
        if ($comment->user_id != \Auth::id()) {
            return $this->json(403, '这不是你的评论无法删除');
        }
        $comment->delete();
        return $this->json(200, '删除成功');
    }
}
