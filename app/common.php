<?php
// 应用公共文件

//json头
header("Content-type: application/json");
//跨域
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Origin: *");
//CORS
header("Access-Control-Request-Methods:GET, POST, PUT, DELETE, OPTIONS");
header('Access-Control-Allow-Headers:token,tokenid,content-type');
$_SERVER['REQUEST_METHOD']=='OPTIONS' && exit;