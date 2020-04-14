<?php
namespace app\common;
use think\facade\Request;
use think\facade\Cache;
use app\model\User as modelUser;

//记录用户日志信息

class User
{
	//写入一个日志
    public function token($user_id)
    {
    	$md5=md5($user_id);
        $md5=md5($md5.time());
        $md5=md5($md5.'__ABC__');
        $md5=md5($md5.time());
        $md5=md5($md5);
        Cache::set("user_token_id_".$user_id,$md5,86400*15);
        return $md5;
    }
    
}
