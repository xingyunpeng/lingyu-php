<?php
declare (strict_types = 1);
namespace think\log\driver;
use think\App;
use think\contract\LogHandlerInterface;
/**
 * SQL日志驱动
 */
class Sql implements LogHandlerInterface
{

    // 实例化并传入参数
    public function __construct(App $app, $config = [])
    {
        
    }

    /**
     * 日志写入接口
     * @access public
     * @param array $log 日志信息
     * @return bool
     */
    public function save(array $log): bool
    {
        return true;
    }

    //向sql写入用户日志
    public function user(){
        
    }

}
