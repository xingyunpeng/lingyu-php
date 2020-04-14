<?php
namespace app\controller;
use app\BaseController;
use app\model\File as modelFile;
use app\common\Log as toolLog;

class Upload extends BaseController
{
    public function index(){

    }

    //上传图片
    public function image(){

        $file = request()->file('image');

        if(!$file){
            return json(['msg'=>'上传失败','status'=>400]);
        }

        //文件名
        $md5=$file->md5();
        //文件扩展名
        $e=explode('/',$_FILES['image']['type'])[1];

        //判断扩展名
        if(!in_array($_FILES['image']['type'],['image/png','image/jpg','image/jpeg','image/gif','image/webp'])){
            return json(['msg'=>'上传图片类型错误','status'=>400]);
        }

        //上传文件临时地址
        $tmp = $_FILES['image']['tmp_name'];

        //文件大小
        $filesize=filesize($tmp);
        //判断文件大小
        if($filesize<10 || $filesize>(1024*1024*1024*5)){
            return json(['msg'=>'上传图片最大5M','status'=>400]);
        }

        $e_dir_md5=str_split($this->user_id,1);
        $e_dir=$e_dir_md5[0].'/'.$e_dir_md5[1];
        $filepath = 'storage/images/'.$e_dir.'/'.$this->user_id;

        $dir=$_SERVER['DOCUMENT_ROOT'].'/'.$filepath.'/';

        //在写入之前判断当前文件以前是否有存在
        $modelFile=new modelFile();
        $file_count=$modelFile->hasMd5($md5);

        //图片已经存在于数据库中 直接返回数据库ID
        if(count($file_count)){
            return json(['msg'=>'上传成功','status'=>200,'img_id'=>$file_count[0]['file_id'].'_'.str_split($file_count[0]['file_md5'],8)[0]]);
        }

        if(!is_dir($dir)){
            mkdir($dir,0777,true);
        }
        //临时文件移动保存
        $file_res=move_uploaded_file($tmp,$dir.$md5.".".$e);

        if(!$file_res){
            return json(['msg'=>'上传失败','status'=>400]);
        }

        $img_data['file_user_id']=$this->user_id;
        $img_data['file_md5']=$md5;
        $img_data['file_size']=$filesize;
        $img_data['file_type']=$e;
        $img_data['file_path']=$filepath;
        $img_data['file_local']=0;

        $img_add_res=$modelFile->addImage($img_data);

        if(!$img_add_res){
            return json(['msg'=>'上传失败','status'=>400]);
        }

        //添加一个日志
        $log_data['log_type']='add_image';
        $log_data['log_user_id']=$this->user_id;
        $log_data['log_message']='文件上传成功：'.$filepath.'/'.$md5.' 数据库ID：'.$img_add_res;
        $toolLog=new toolLog();
        $toolLog->save($log_data);

        return json(['msg'=>'上传成功','status'=>200,'img_id'=>$img_add_res.'_'.str_split($md5,8)[0]]);
    }

    //上传文件
    public function file(){

    }

    //上传视频
    public function video(){

    }
}
