<?php
namespace app\validate;

use think\Validate;

class User extends Validate
{
    protected $rule =   [
        'user_user'  => 'require|max:32|min:6|alphaDash',
        'user_pass'   => 'require|max:32|min:8|alphaDash',
        'user_nickname'=>'require|max:12|min:6|chsDash',
    ];
    
    protected $message  =   [
        'user_user.require' => '帐号必须填写',
        'user_user.max'     => '帐号最大长度为32位',
        'user_user.min'   => '帐号最小长度为6位',
        'user_user.alphaDash'   => '帐号格式必须是：字母和数字，下划线_及破折号',

        'user_pass.require' => '帐号必须填写',
        'user_pass.max'     => '帐号最大长度为32位',
        'user_pass.min'   => '帐号最小长度为6位',
        'user_pass.alphaDash'   => '帐号格式必须是：字母和数字，下划线_及破折号',

        'user_nickname.require' => '昵称必须填写',
        'user_nickname.max'     => '昵称有点过长',
        'user_nickname.min'   => '昵称最小长度为6位',
        'user_nickname.chsDash'   => '昵称格式必须是：汉字、字母、数字，下划线_及破折号',
        
    ];

    protected $scene = [
        'reg'  =>  ['user_user','user_pass'],
        'login'  =>  ['user_user','user_pass'],
        'nickname'=>['user_nickname']
    ]; 

}

?>