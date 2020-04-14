<?php
namespace app\controller;

class Error
{
    public function index()
    {
        return json(['msg'=>'操作失败','status'=>400]);
    }
}
