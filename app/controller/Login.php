<?php
namespace app\controller;

use app\validate\User as validateUser;
use app\model\User as modelUser;
use think\facade\Cache;
use think\facade\Request;
use app\common\Log as toolLog;
use app\common\User as toolUser;


class Login
{
	//登录
	public function index(){

    	$json['msg']='操作失败';
    	$json['status']=400;
    	$json['notice']=1;

    	$data['user_user']=input("post.user",'');
    	$data['user_pass']=input("post.pass");

    	//验证
    	$validateUser = new validateUser();
		$validateResult = $validateUser->scene("login")->check($data);

		if(!$validateResult){
			$json['msg']=$validateUser->getError();
			return json($json);
		}

		//判断当前IP是否过多存在帐号密码错误多次
		$request = Request::instance();
		$ip=$request->ip();
		$ip_login_count=Cache::get('frequency_login_error_ip_'.$ip);
		if($ip_login_count>=5){
			$json['msg']='帐号密码错误多次，一小时后再试';
			return json($json);
		}
		//判断当前帐号是否错误多次
		$user_login_count=Cache::get('frequency_login_error_user_'.$data['user_user']);
		if($user_login_count>=5){
			$json['msg']='帐号密码错误多次，一小时后再试';
			return json($json);
		}
		//开始查询数据库当中是否有此用户
		$modelUser=new modelUser();
		$login=$modelUser->login($data);

		if(!count($login)){
			$json['msg']='帐号密码错误';
			//缓存当前错误次数
			if($ip_login_count){
				Cache::inc('frequency_login_error_ip_'.$ip);
			}else{
				Cache::tag('frequency_login_error_ip')->set('frequency_login_error_ip_'.$ip,1,3600);
			}
			if($user_login_count){
				Cache::inc('frequency_login_error_user_'.$data['user_user']);
			}else{
				Cache::tag('frequency_login_error_user')->set('frequency_login_error_user_'.$data['user_user'],1,3600);
			}
			return json($json);
		}

		//登录成功

		//写入日志信息
		$log_data['log_user_id']=$login[0]['user_id'];
    	$log_data['log_type']='login';
    	$log_data['log_message']='登录成功';
    	$Log=new toolLog();
    	$Log->save($log_data);

    	$toolUser=new toolUser;
    	$token=$toolUser->token($login[0]['user_id']);

    	//如果当前用户状态码为0时 则要进行判断当前需要进行哪一步补全

    	if($login[0]['user_status']==0){
    		$step=$this->user_step($login[0]['user_id']);
    	}else{
    		$step='none';
    	}

    	$json['msg']='登录成功';
    	$json['status']=200;
    	$json['user']=$login[0];
    	$json['token']=$token;
    	$json['step']=$step;

    	return json($json);
    }
    //获取当前用户需要进行验证的步骤
    protected function user_step($user_id){
    	$modelUser=new modelUser();
		$login=$modelUser->getUser($user_id)[0];
    	//判断是否设置昵称
    	if(strlen($login['user_nickname'])<1){
    		return 'nickname';
    	}
    	//判断是否设置头像
    	if($login['user_img_id']==0){
    		return 'user_img';
    	}
    }
    //获取当前用户是否有步骤没有设置
    
    //注册
    public function reg()
    {

    	$json['msg']='操作失败';
    	$json['status']=400;

    	$data['user_user']=input("post.user");
    	$data['user_pass']=input("post.pass");

    	//验证

    	$validateUser = new validateUser();
		$validateResult = $validateUser->scene("reg")->check($data);

		if(!$validateResult){
			$json['msg']=$validateUser->getError();
			return json($json);
		}

		//判断当前IP是否过多注册帐号
		$request = Request::instance();
		$ip=$request->ip();
		$ip_reg_count=Cache::get('frequency_reg_ip_'.$ip);
		if($ip_reg_count){
			$json['msg']='当前IP地址已经注册过,请24小时后再注册';
			return json($json);
		}

		//开始查询数据库当中是否有此用户
		$modelUser=new modelUser();
		$hasUserName=$modelUser->hasUserName($data['user_user']);

		if($hasUserName){
			$json['msg']='当前帐号已经存在';
			return json($json);
		}

		//开始注册用户
		$res=$modelUser->reg($data);

		if($res){
			$json['msg']='注册成功';
			$json['status']=200;

			//缓存IP注册
			Cache::tag('frequency_reg_ip')->set('frequency_reg_ip_'.$ip,1,86400);

			//写入日志信息
	    	$log_data['log_type']='reg';
	    	$log_data['log_user_id']=$res;
	    	$log_data['log_message']='用户注册成功：'.$data['user_user'];
	    	$log_data['log_json']=$data;
	    	$Log=new toolLog();
	    	$Log->save($log_data);

			return json($json);
		}

        return json(['msg'=>'操作失败','status'=>400]);
    }
}
