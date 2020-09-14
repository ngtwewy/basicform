<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://restfulapi.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: ngtwewy <62006464@qq.com> 
// +----------------------------------------------------------------------
// | DateTime: 2020-06-15
// +----------------------------------------------------------------------
// | Description: 
// +----------------------------------------------------------------------

session_start();
if(!isset($_SESSION['submit_time'])){
    $_SESSION['submit_time'] = time();
}

// 初始化 wordpress 的一些全局设置
require_once '../../../wp-load.php';
wp(); // 这里产生的状态码是 404

// 加载我的插件
define( 'MY__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
require_once MY__PLUGIN_DIR . "lib/Validator/Validator.php";

// 发送头信息
header("Content-Type:application/json; chartset=uft-8");
Header("HTTP/1.1 200");

// 返回数据
$response['success'] = "false";
$response['msg'] = "请求错误";

// 检查请求类型
if($_SERVER['REQUEST_METHOD'] != "POST"){
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    die();
}

// 验证数据
$rule = [
    'user_name' => 'required|minlen:1|maxlen:10 `称呼`',
    'mobile' => 'required|is_mobile `手机号`',
    'content' => 'required|minlen:1|maxlen:200 `需求登记`',
    // 'company' => 'required|minlen:1|maxlen:200 `公司名称`',
    'country' => 'required|minlen:1|maxlen:200 `地区`',
    // 'email' => 'required|is_email|minlen:1|maxlen:200 `邮箱`',
    // 'purpose' => 'required|maxlen:3 `需求类型`'
];
$v = new Validator();
if (!$v->setRules($rule)->validate($_POST)) {
    $response['success'] = "false";
    $response['msg'] = $v->getErrorString();
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    die();
}


// 保存数据前限流
if(!isset($_SESSION['submit_time'])){
    $response['success'] = "false";
    $response['msg'] = "客户端必须支持 COOKIE";
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    die();
}

if( (time() - $_SESSION['submit_time']) < 30){
    $response['success'] = "false";
    $response['msg'] = "已经添加过合作意向，请一小时以后再试";
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    die();
}

// 保存数据
global $wpdb;
$data = $v->getData();
$data['create_time'] = date("Y-m-d H:i:s");
$res = $wpdb->insert("my_form", $data);

if($res == 1){
    $response['success'] = "true";
    $response['msg'] = "提交成功，我们会尽快跟你联系";
    $_SESSION['submit_time'] = time();
} else {
    $response['success'] = "false";
    $response['msg'] = "添加失败，请稍后再试";
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);