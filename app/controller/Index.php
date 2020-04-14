<?php
namespace app\controller;

use app\BaseController;

class Index extends BaseController
{
    public function index()
    {
        return json(['msg'=>'操作失败','status'=>400]);
    }
    public function init(){
    	return json(['msg'=>'初始化页面','status'=>200,'user'=>$this->user]);
    }
}
