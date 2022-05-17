<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/* Route::get('test', function () {
    return Student::factory()->count(10)->create();
}); */

// 查看全部文章
Route::apiResource('articles', ArticleController::class)->only(['index', 'show']);
// 注册
Route::post('register', [AuthController::class, 'register']);
// 登录
Route::post('login', [AuthController::class, 'login']);
// 图形验证码
Route::get('code',[AuthController::class,'code']);
// 获取文章评论数量
Route::get('article/comment/{id}',[ArticleController::class,'comments']);
// 登录保护的路由
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('articles', ArticleController::class)->except(['index', 'show']);
    // 登出
    Route::post('logout',[AuthController::class,'logout']);
    // 查看被删除的文章
    Route::get('article/showDeleted',[ArticleController::class,'showDeleted']);
    // 恢复被删除的文章
    Route::get('article/restore/{id}',[ArticleController::class,'restoreArticle']);
    // 彻底删除被删除的文章·
    Route::delete('article/forceDelete/{id}',[ArticleController::class,'forceDeleteArticle']);
    // 创建一个新的评论
    Route::post('comment/{id}',[CommentController::class,'store']);
    // 删除一个评论
    Route::delete('comment/{id}',[CommentController::class,'destroy']);
    // 修改一个评论
    Route::put('comment/{id}',[CommentController::class,'update']);
    // 个人中心
    Route::get('users',[UserController::class,'show']);
    // 修改个人信息
    Route::put('users',[UserController::class,'update']);
    // 修改密码
    Route::post('users/password',[UserController::class,'password']);
    // 上传头像
    Route::post('users/avatar',[UserController::class,'upload']);
});


