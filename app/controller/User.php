<?php
namespace app\controller;
use app\BaseController;
use app\validate\User as validateUser;
use app\model\User as modelUser;
use app\model\File as modelFile;
use app\common\Log as toolLog;

class User extends BaseController
{
    public function index()
    {
        return json(['msg'=>'操作失败','status'=>400]);
    }
    //获取当前用户还有哪些步骤未补充
    public function step(){
    	$user_status=$this->user['user_status'];
    	$step='none';
    	if($user_status==0){
    		if($this->user['user_img_id']==0){
    			$step='user_img';
    		}
    		if(!$this->user['user_nickname']){
    			$step='nickname';
    		}
    	}

    	if($step=='none'){
    		$modelUser=new modelUser();
    		$nickname_res=$modelUser->edit(['user_id'=>$this->user_id],['user_status'=>1]);
    	}

    	$json['msg']='获取成功';
    	$json['status']=200;
    	$json['step']=$step;
    	return json($json);
    }
    //设置一个用户的昵称
    public function nickname(){
    	//判断当前昵称是否设置 如果已经设置则跳过
    	if(strlen($this->user['user_nickname'])){
    		$json['msg']='当前昵称已经设置过了';
	    	$json['status']=200;
	    	return json($json);
    	}
    	//开始设置昵称
    	$nickname=input("post.nickname");
    	//开始验证昵称
    	$validateUser = new validateUser();
		$validateResult = $validateUser->scene("nickname")->check(['user_nickname'=>$nickname]);

		if(!$validateResult){
			$json['msg']=$validateUser->getError();
			$json['status']=400;
			$json['notice']=1;
			return json($json);
		}
		//判断数据库当中是否有此昵称
		$modelUser=new modelUser();
		$nickname_count=$modelUser->query(['user_nickname'=>$nickname]);
		if(count($nickname_count)){
			$json['msg']='要设置的昵称已经存在';
			$json['status']=400;
			$json['notice']=1;
			return json($json);
		}

		//开始重新设置
		$nickname_res=$modelUser->edit(['user_id'=>$this->user_id],['user_nickname'=>$nickname]);
		if($nickname_res){
			//写入日志
			$toolLog=new toolLog();
			$log_data['log_type']='edit_nickname';
			$log_data['log_user_id']=$this->user_id;
	    	$log_data['log_message']='昵称修改为：'.$nickname;
			$toolLog->save($log_data);

			$json['msg']='昵称设置成功';
			$json['status']=200;
			return json($json);
		}

		$json['msg']='操作失败';
		$json['status']=400;
		return json($json);
    }
    public function set_header_img(){
    	$modelUser=new modelUser();
		// $nickname_count=$modelUser->query(['user_nickname'=>$nickname]);
		$img_id=input("post.img_id");
		$modelFile=new modelFile();
		$file_res=$modelFile->verify($img_id);
		if($file_res===false){
			return  json(['msg'=>'文件ID不正确','status'=>400]);
		}
		$modelUser=new modelUser();
		$res=$modelUser->edit(['user_id'=>$this->user_id],['user_img_id'=>$img_id]);
		if($res){
			return json(['msg'=>'头像修改成功','status'=>200]);
		}
		return json(['msg'=>'上传失败','status'=>400]);
    }
    //用于前端vuex重新获取数据
    public function vuex(){
    	
    }
}
