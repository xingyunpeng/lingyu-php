<?php
namespace app\model;
use think\Model;
use think\model\concern\SoftDelete;

class File extends Model
{
	use SoftDelete;
	//表名称
	protected $name = 'file';
	//表的主键
	protected $pk = 'file_id';
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
	protected $json = [];
	//只读字段
	protected $readonly = ['file_user_id','file_md5','file_size','file_type','file_path','file_local'];
	//隐藏字段
	protected $hidden=[];

	//写入图片
	public function addImage($data){
		$this->save($data);
		return $this->file_id;
	}
	//查询一个用户的图片是否存在
	//必须是以查询用户 否则多个用户上传同一个内容 会出问题
	public function hasMd5($md5){
		return $this->where(['file_md5'=>$md5])->limit(1)->select()->toArray();
	}
	//验证一个文件ID是否存在
	public function verify($file_id){
		$file_name=explode('_',$file_id);
		if(!((int)$file_name[0])){
			return false;
		}
		$res=$this->cache('img_cache_'.$file_id,0,'img_cache')->where(['file_id'=>$file_name[0]])->limit(1)->select()->toArray();

		if(!count($res)){
			return false;
		}

		$r=str_split($res[0]['file_md5'],8);

		if($r[0]!=$file_name[1]){
			return false;
		}
		
		return $res[0];
	}

}