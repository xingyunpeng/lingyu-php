<?php
namespace app\common;
use think\facade\Request;
// use think\facade\Cache;
use app\model\Log as modelLog;

//记录用户日志信息

class Log
{
	//写入一个日志
    public function save($log=[])
    {
    	$request = Request::instance();

    	//先做数据处理
    	$log['log_ip']=$request->ip();

        //设置用户ID
        if(empty($log['log_user_id'])){
            $log['log_user_id']=$GLOBALS['user_id'];
        }

    	$modelLog= new modelLog();
    	$res=$modelLog->write($log);
    	return $res;
    }
}
