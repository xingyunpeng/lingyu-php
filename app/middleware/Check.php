<?php
namespace app\middleware;
use think\facade\Cache;

class Check
{
    public function handle($request, \Closure $next)
    {

        $s=strpos($request->param('s'),'//login/');

        if($s===false){
            $token=$request->header()['token'];
            $token_id=$request->header()['tokenid'];


            //下面进行验证帐号 这里也要使用验证器
            $token_string=Cache::get("user_token_id_".$token_id);

            if($token_string==$token){
                $json['times']=time();
                $json['data']=[
                    'msg'=>'登录超时，请重新登录',
                    'status'=>758,
                ];
                $json['statusCode']=758;
            }
        }
        return $next($request);
    }
}