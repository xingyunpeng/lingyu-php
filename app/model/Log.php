<?php
namespace app\model;
use think\Model;
use think\model\concern\SoftDelete;

class Log extends Model
{
	use SoftDelete;
	//表名称
	protected $name = 'log';
	//表的主键
	protected $pk = 'log_id';
	//模型允许写入的字段列表（数组）
	protected $field = [];
	//自动写入创建时间辍
	protected $createTime = 'create_times';
	//自动写入更改时间辍
    protected $updateTime = 'update_times';
    //软删除
    protected $deleteTime = 'delete_times';
    protected $defaultSoftDelete = 0;
    // 设置json类型字段
	protected $json = ['log_json'];

	//写入一个日志
	public function write($data){
		return $this->save($data);
	}
}