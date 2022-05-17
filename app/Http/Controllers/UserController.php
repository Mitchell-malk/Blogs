<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

/**
 * 用户控制器
 */
class UserController extends Controller
{

    /**
     * 个人中心
     * @Get (users)
     * @return \Illuminate\Http\JsonResponse
     */
    public function show()
    {
        // 获取当前用户id
        $id = \Auth::id();
        $user = User::find($id);
        return $this->json(200, '数据获取成功', $user);
    }

    /**
     * 修改个人信息
     * @PUT (users)
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $data = $request->all();
        $rule = [
            'name' => 'required|string|max:10',
            'phone' => 'string|max:11',
            'address' => 'string|max:255',
        ];
        $message = [
            'required' => ':attribute必须填写',
            'string' => ':attribute必须是字符串',
            'max' => ':attribute最大长度为:max',
        ];
        $placeholder = [
            'name' => '姓名',
            'phone' => '手机号',
            'address' => '地址',
        ];
        $validator = \Validator::make($data, $rule, $message, $placeholder);
        if ($validator->fails()) {
            return $this->json(400, $validator->errors());
        }
        $id = \Auth::id();
        $user = User::find($id);
        $user->name = $data['name'];
        $user->phone = $data['phone'];
        $user->address = $data['address'];
        $user->save();
        return $this->json(200, '修改成功', $user);
    }

    /**
     * 修改密码
     * @POST (users/password)
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function password(Request $request)
    {
        $data = $request->all();
        $rule = [
            'oldPassword' => 'required|string|max:255',
            'newPassword' => 'required|string|max:255',
        ];
        $message = [
            'required' => ':attribute必须填写',
            'string' => ':attribute必须是字符串',
            'max' => ':attribute最大长度为:max',
        ];
        $placeholder = [
            'oldPassword' => '旧密码',
            'newPassword' => '新密码',
        ];
        $validator = \Validator::make($data, $rule, $message, $placeholder);
        if ($validator->fails()) {
            return $this->json(400, $validator->errors());
        }
        $id = \Auth::id();
        $user = User::find($id);
        if (!\Hash::check($data['oldPassword'], $user->password)) {
            return $this->json(400, '旧密码错误');
        }
        $user->password = \Hash::make($data['newPassword']);
        $user->save();
        return $this->json(200, '修改成功');
    }

    /**
     * 上传头像
     * @POST users/avatar
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(Request $request)
    {
        // 获取全部数据
        $data = $request->all();
        // 验证
        $rule = [
            'avatar' => 'required|image'
        ];
        $msg = [
            'id.required' => '请传入用户id',
            'avatar.required' => '请上传头像',
            'avatar.image' => '请上传图片'
        ];
        $validate = Validator::make($data, $rule, $msg);
        if ($validate->fails()) {
            return $this->json(422, $validate->errors()->first());
        }
        // id是否为整数
        $id = \Auth::id();
        // 判断用户是否存在
        $user = User::select('id', 'avatar')->find($id);
        if (!$user) {
            return $this->json(422, '用户不存在');
        }
        // 删除原头像
        if ($user->avatar != 'avatar/default.jpg') {
            Storage::delete($user->avatar);
        }
        // 上传图片
        $file = $request->file('avatar');
        $path = $file->store('avatar');
        $user->avatar = $path;
        $user->save();
        return $this->json(200, '头像上传成功', $user);
    }
}
