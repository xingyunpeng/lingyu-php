<?php
declare (strict_types = 1);

namespace app;

use think\App;
use app\model\User as modelUser;

/**
 * 控制器基础类
 */
abstract class BaseController
{
    /**
     * Request实例
     * @var \think\Request
     */
    protected $request;

    /**
     * 应用实例
     * @var \think\App
     */
    protected $app;

    /**
     * 控制器中间件
     * @var array
     */
    protected $middleware = [];

    /**
     * 构造方法
     * @access public
     * @param  App  $app  应用对象
     */
    public function __construct(App $app)
    {
        $this->app     = $app;
        $this->request = $this->app->request;

        // 控制器初始化
        $this->initialize();
    }

    // 初始化
    protected function initialize()
    {
        $json['times']=time();
        $json['data']=[
            'msg'=>'登录超时，请重新登录',
            'status'=>758,
        ];
        $json['statusCode']=758;

        if(empty($this->request->header()['token']) || empty($this->request->header()['tokenid'])){
            echo json_encode($json);
            exit;
        }

        $token=$this->request->header()['token'];
        $token_id=(int)($this->request->header()['tokenid']);
        $token_string=cache("user_token_id_".$token_id);

        if($token_string===NULL || $token_string!=$token){
            echo json_encode($json);
            exit;
        }else{
            $this->user_id=$token_id;
            $GLOBALS['user_id']=$token_id;
            $modelUser=new modelUser();
            $user=$modelUser->getUser($token_id);
            if(!count($user)){
                $json['data']['msg']='用户不存在';
                echo json_encode($json);
                exit;
            }else{
                $this->user=$user[0];
            }
        }
    }


}
