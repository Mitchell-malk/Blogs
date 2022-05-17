<?php

namespace App\Http\Controllers;

use App\Http\Response\ResponseJson;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

/**
 * 登录模块控制器
 */
class AuthController extends Controller
{
    use ResponseJson;

    /**
     * 验证
     * @var string[]
     */
    protected $validate = [
        'name' => 'required|string',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:6|confirmed'
    ];

    /**
     * 自定义验证信息
     * @var string[]
     */
    protected $msg = [
        'required' => ':attribute 为必填项',
        'string' => ':attribute 必须是字符串',
        'min' => ':attribute 最小长度为 :min',
        'email' => ':attribute 格式错误',
        'unique' => ':attribute 已存在',
        'confirmed' => ':attribute 两次输入不一致'
    ];

    /**
     * 占位符
     * @var string[]
     */
    protected $placeholder = [
        'name' => '用户名',
        'email' => '邮箱',
        'password' => '密码'
    ];

    /**
     * 用户注册
     * @POST (register)
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        // 获取用户提交的数据
        $data = $request->all();
        // 错误信息
        $validate = Validator::make($data, $this->validate, $this->msg, $this->placeholder);
        if ($validate->fails()) {
            return $this->json(422, $validate->errors());
        }
        // 创建用户
        $data['password'] = bcrypt($data['password']);
        $user = User::create($data);
        // 返回用户信息
        return $this->json(200, '注册成功', $user);
    }

    /**
     * 用户登录
     * @POST (login)
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validate = [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ];
        // 自定义验证信息
        $msg = [
            'required' => ':attribute 为必填项',
            'string' => ':attribute 必须是字符串',
            'min' => ':attribute 最小长度为 :min',
            'email' => ':attribute 格式错误',
        ];
        // 占位符
        $placeholder = [
            'name' => '用户名',
            'email' => '邮箱',
            'password' => '密码',
        ];
        // 错误信息
        $validate = Validator::make($request->all(), $validate, $msg, $placeholder);
        if ($validate->fails()) {
            return $this->json(422, $validate->errors());
        }
        // 获取用户提交的验证码数据
        $captcha = $request->input('captcha'); //验证码
        $key = $request->input('key'); //key
        // 判断验证码是否正确
        if (!captcha_api_check($captcha, $key)){
            return $this->json(400, '验证码错误');
        }
        $user = User::where('email', $request->email)->first();
        if (!$user || Hash::check($request->password, $user->password) == false) {
            return $this->json(422, '邮箱或密码不正确');
        }
        $token = $user->createToken('Laravel Password Grant Client')->plainTextToken;
        $user = [
            'name' => $user->name,
            'email' => $user->email
        ];
        $data = [
            $user,
            $token
        ];
        return $this->json(200, '用户登录成功', $data);
    }

    /**
     * 用户退出
     * @POST (logout)
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return $this->json(200, '用户成功退出');
    }

    public function code(){

        //return [
        //    'code' => 101,
        //    'message' => '请求成功',
        //    'result' => app('captcha')->create('default', true) //create是生成验证码的方法
        //    //这里可以直接app('captcha')的原因就是因为在config\app.php中的providers中添加了这一句\Mews\Captcha\CaptchaServiceProvider::class,然后在CaptchaServiceProvider中的register绑定了bind的名字是captcha。
        //];
        return $this->json(200,'请求成功',app('captcha')->create('default', true));
    }
}
