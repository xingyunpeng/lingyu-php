<?php
namespace app\controller;

use app\BaseController;
use League\Glide\ServerFactory;
use app\model\File as modelFile;

class Image// extends BaseController
{
	//获取图片
    public function index($name){
    	$t1=microtime(true);
    	preg_match_all('/([\d]+)\_([0-9a-z]+)\_?([a-z]+)?\.?([a-z]+)?/i',$name,$file_array);

    	$file_array=[$file_array[1][0],$file_array[2][0],$file_array[3][0],$file_array[4][0]];

    	$img_id=$name;
    	$root_dir=$_SERVER['DOCUMENT_ROOT'];

    	//图片规则
    	$rule_list=[
    		'default'=>[
    			'w'=>400,//宽度
    			// 'h'=>400,//高度
    			// 'fit'=>'crop-center',//居中裁剪
    			'q'=>75,//图片质量
    			'fm'=>'jpg',//图片格式
                // 'or'=>'auto',//自动旋转
    		],
    		'list'=>[
    			'w'=>300,//宽度
    			'h'=>200,//高度
    			'fit'=>'crop-center',//居中裁剪
    			'q'=>75,//图片质量
    			'fm'=>'jpg',//图片格式
    		],
    		'max'=>[
    			'w'=>800,//宽度
    			'h'=>800,//高度
    			'q'=>75,//图片质量
    			'fm'=>'jpg',//图片格式
    		],
            'min'=>[
                'w'=>100,//宽度
                'h'=>100,//高度
                'fit'=>'crop-center',//居中裁剪
                'q'=>85,//图片质量
                'fm'=>'jpg',//图片格式
            ],
            //头像
            'avatar'=>[
                'w'=>100,//宽度
                'h'=>100,//高度
                'fit'=>'crop-center',//居中裁剪
                'q'=>85,//图片质量
                'fm'=>'jpg',//图片格式
                'dpr'=>2,//密度
            ],
    	];
    	//扩展名
    	$extension=['jpg','jpeg','png','gif','webp'];

    	//获取数据参数
    	$extension_name=in_array($file_array[3],$extension) ? $file_array[3]:'jpg';

    	//从数据库中查询数据
    	$modelFile=new modelFile();
	    $img_res=$modelFile->verify($file_array[0].'_'.$file_array[1]);

	    if($img_res===false){
	    	//本地图片具体地址
	    	$img_dir='storage/images/';
	    	//本地图片地址 带扩展名
	    	$img_url='default.jpg';
	    }else{
	    	//本地图片具体地址
	    	$img_dir=$img_res['file_path'];
	    	//本地图片地址 带扩展名
	    	$img_url=$img_res['file_md5'].'.'.$img_res['file_type'];
	    	if(!is_file($root_dir.'/'.$img_dir.'/'.$img_url)){
	    		//本地图片具体地址
		    	$img_dir='storage/images/';
		    	//本地图片地址 带扩展名
		    	$img_url='default.jpg';
	    	}
    	}

    	//获取图片规则数据
    	$rule_name=!empty($rule_list[$file_array[2]]) ? $file_array[2]:'default';
    	$rule=$rule_list[$rule_name];

    	//自定义扩展名
    	$rule['fm']=$file_array[3] ?:'jpg';

        $server = ServerFactory::create([
		    'source' => $img_dir,
		    'cache' => 'cache/'.$img_dir,
		]);
		$server->outputImage($img_url,$rule);
    }
    //上传文件
    public function file(){

    }
    //上传视频
    public function video(){

    }
}
