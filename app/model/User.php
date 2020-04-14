<?php
namespace app\model;

use think\Model;

class User extends Model
{
	//表名称
	protected $name = 'user';
	//表的主键
	protected $pk = 'user_id';
	//模型允许写入的字段列表（数组）
	protected $field = ['user_pass','delete_times','update_times','user_user','user_img_id','user_nickname','user_vip','user_vip_times','user_integral','user_money','user_status'];
	//自动写入创建时间辍
	protected $createTime = 'create_times';
	//自动写入更改时间辍
    protected $updateTime = 'update_times';
    //软删除
    protected $deleteTime = 'delete_times';
    protected $defaultSoftDelete = 0;
    // 设置json类型字段
	protected $json = ['log_json'];
	//只读字段
	protected $readonly = ['user_user','create_times','user_id'];
	//隐藏字段
	protected $hidden=['user_pass','create_times','delete_times','update_times','user_user'];
	
	//查询一个帐号是否存在
	public function hasUserName($user_user){
		return $this->where(["user_user"=>$user_user])->count();
	}

	//注册一个用户
	public function reg($data){
		$this->save($data);
		return $this->user_id;
	}

	//登录一个用户
	public function login($data){
		$data['user_pass']=$this->setUserPassAttr($data['user_pass'],$data);
		return $this->where(["user_user"=>$data['user_user'],'user_pass'=>$data['user_pass']])->limit(1)->select()->toArray();
	}

	//获取一个用户的基本信息
    public function getUser($user_id){
        return $this->where(['user_id'=>$user_id])->limit(1)->select()->toArray();
    }

    //修改一个用户信息
    public function edit($where,$data){
    	return $this->where($where)->update($data);
    }
    public function query($where,$options=[]){
    	return $this->where($where)->select()->toArray();
    }


    /*__======__修改器__======__*/

	//密码加密
	public function setUserPassAttr($value,$data=[])
    {
        $md5=md5($value.'__abc__');
        $md5=md5($md5.'__app__');
        $md5=md5($md5.'_'.$data['user_user']);
        $md5=md5($md5.'__bbs__');
        $md5=md5($md5.'__xyz__');
        return $md5;
    }
}