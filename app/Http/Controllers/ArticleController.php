<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * 文章控制器
 * @Resource("Articles",url="/articles")
 *
 * @package App\Http\Controllers
 */
class ArticleController extends Controller
{

    /**
     * 验证规则
     * @var string[]
     */
    protected $rule = [
        'title' => 'required|string|min:3',
        'comment' => 'required|string',
    ];
    /**
     * 错误信息
     * @var string[]
     */
    protected $msg = [
        'required' => ':attribute 必填项',
        'string' => ':attribute 必须是字符串',
        'min' => ':attribute 最小长度为 :min'
    ];

    /**
     * 占位符内容
     * @var string[]
     */
    protected $placeholder = [
        'title' => '标题',
        'comment' => '内容',
    ];

    /**
     * 获取登录用户的id
     * @return int|string|null
     */
    protected function userId()
    {
        return Auth::id();
    }

    /**
     * 显示文章列表。
     * @Get("/articles")
     * @return \Illuminate\Http\Response|string
     */
    public function index()
    {
        // 显示文章列表
        $articles = Article::select('id','title','comment','updated_at')->paginate(10);
        return $this->json(200, '数据获取成功', $articles);
    }

    /**
     * 创建文章信息。
     * @POST("/articles")
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // 获取全部数据
        $data = $request->all();
        // 返回错误信息
        $validate = Validator::make($data, $this->rule, $this->msg, $this->placeholder);
        if ($validate->fails()) {
            return $this->json(422, $validate->errors());
        }
        $user = User::find($this->userId());
        $article = $user->article()->create([
            'title' => $request->input('title'),
            'comment' => $request->input('comment')
        ]);
        return $this->json(200, '文章创建成功', $article);
    }

    /**
     * 显示指定的文章。
     * @Get("/articles/{id}")
     * @param int $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function show($id)
    {
        //判断id是否为整数
        $id = intval($id);
        if ($id == 0) {
            return $this->json(422, 'id不合法');
        }

        // 查看文章是否存在
        $article = Article::find($id);
        if (!$article) {
            return $this->json(404, '文章不存在');
        }

        // 查看指定文章
        $article = Article::select('title', 'comment', 'updated_at')->find($id);
        // 根据文章查找用户
        $user = Article::find($id)->user()->get('name');
        // 将用户名添加到文章中
        $article->user = $user[0]->name;
        // 根据文章查找评论
        $comments = Article::find($id)->comments()->get(['comment', 'updated_at']);
        // 根据文章查找评论的用户
        $comment_user = Article::find($id)->users()->first(['name'])->makeHidden('pivot');
        // 合并评论和用户
        $comments = $comments->map(function ($comment) use ($comment_user) {
            $comment->user = $comment_user;
            return $comment;
        });
        $data = [
            'article' => $article,
            //'user' => $user,
            'comments' => $comments,
            //'comments_user' => $comment_user
        ];
        return $this->json(200, '数据获取成功', $data);
    }

    /**
     *更新存储中的指定资源。
     * PUT/PATCH("/articles/{id}")
     * @param int $id
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // 获取全部数据
        $data = $request->all();

        // id是否合法
        $id = intval($id);
        if ($id == 0) {
            return $this->json(422, 'id不合法');
        }

        $article = Article::find($id);
        // 判断文章是否存在
        if (!$article) {
            return $this->json(404, '文章不存在');
        }
        // 返回错误信息
        $validate = Validator::make($data, $this->rule, $this->msg, $this->placeholder);
        if ($validate->fails()) {
            return $this->json(422, $validate->errors());
        }

        // 判断是否有权限修改
        if ($article->user_id != $this->userId()) {
            return $this->json(403, '没有权限修改');
        }
        // 更新文章
        $article->update([
            'title' => $request->input('title'),
            'comment' => $request->input('comment')
        ]);
        return $this->json(200, '文章更新成功', $article);
    }

    /**
     * 删除指定的文章的信息。
     * @Delete("/articles/{id}")
     * @param int $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // 判断id是否合法
        $id = intval($id);
        if ($id == 0) {
            return $this->json(422, 'id不合法');
        }

        // 查看文章是否存在
        $article = Article::find($id);
        if (!$article) {
            return $this->json(404, '文章不存在');
        }

        // 判断是否有权限删除
        $admin = User::find($this->userId())->role()->get()->makeHidden('pivot');
        if ($article->user_id != $this->userId()) {
            return $this->json(403, '没有权限删除');
        }

        if ($article->user_id == $this->userId()) {
            $article->delete();
            return $this->json(200, '文章删除成功');
        }

        if ($admin[0]->type == '管理员') {
            // 永久删除
            $article->forceDelete();
            return $this->json(200, '文章已被永久删除');
        }
        return $this->json(400, '未知错误，删除失败');
    }

    /**
     * 查找被删除文章
     * @Get ("article/showDeleted")
     * @return \Illuminate\Http\JsonResponse
     */
    public function showDeleted()
    {
        $article = Article::where('user_id', $this->userId())->onlyTrashed()->get();
        return $this->json(200, '数据获取成功', $article);
    }

    /**
     * 恢复被删除文章
     * @Get ("article/restore/{id}")
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function restoreArticle($id)
    {
        // 判断id是否合法
        $id = intval($id);
        if ($id == 0) {
            return $this->json(422, 'id不合法');
        }
        // 查看文章是否存在
        $article = Article::withTrashed()->find($id);
        if (!$article) {
            return $this->json(404, '文章不存在');
        }
        // 判断是否有权限恢复
        if ($article->user_id != $this->userId()) {
            return $this->json(403, '没有权限恢复');
        }
        // 恢复文章
        $article->restore();
        return $this->json(200, '文章恢复成功');
    }


    /**
     * 彻底删除被删除文章
     * @Get ("article/forceDelete/{id}")
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function forceDeleteArticle($id)
    {
        // 判断id是否合法
        $id = intval($id);
        if ($id == 0) {
            return $this->json(422, 'id不合法');
        }
        // 查看文章是否存在
        $article = Article::withTrashed()->find($id);
        if (!$article) {
            return $this->json(404, '文章不存在');
        }
        // 判断是否有权限删除
        if ($article->user_id != $this->userId()) {
            return $this->json(403, '没有权限删除');
        }
        // 彻底删除文章
        $article->forceDelete();
        return $this->json(200, '文章彻底删除成功');
    }

    /**
     * 获取文章的评论
     * @Get ("article/{id}")
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function comments($id)
    {
        // 判断id是否合法
        $id = intval($id);
        if ($id == 0) {
            return $this->json(422, 'id不合法');
        }
        // 查看文章是否存在
        $article = Article::find($id);
        if (!$article) {
            return $this->json(404, '文章不存在');
        }
        // 获取文章的评论
        $comments = $article->comments()->get()->count();
        // 判断是否有数据
        if ($comments == 0) {
            return $this->json(200, '暂无评论',0);
        }

        return $this->json(200,'数据获取成功',$comments);
    }
}
