<?php
namespace app\home\validate;

use think\Validate;

class User extends Validate
{
    protected $rule = [
        "username" => "require|unique:user,username|max:25",
        'email' => 'unique:user,email',
        "password" => "require|min:6"
    ];

    protected $message = [
        "username.unique" => "该名称已存在",
        "username.require" => "名称不能为空",
        "username.max" => "名称最多不能超过25个字符",
        "email" => "邮箱格式错误",
        "password.require" => "密码不能为空",
        "password.min" => "密码不能小于6位",
    ];

    protected $scene = [
        'add'   =>  ['username','email','password'],
        'edit'  =>  ['email'],
    ];

}