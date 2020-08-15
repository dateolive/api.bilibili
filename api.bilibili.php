<?php
header("Content-type: text/html; charset=utf-8"); 

error_reporting(E_ALL ^ E_NOTICE);// 显示除去 E_NOTICE 之外的所有错误信息
$uid = $_GET["uid"];

function curl_get_https($url){
    $curl = curl_init(); // 启动一个CURL会话
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);// TRUE 将curl_exec()获取的信息以字符串返回，而不是直接输出。
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
    $tmpInfo = curl_exec($curl); // 返回api的json对象
    curl_close($curl);
    return $tmpInfo; // 返回json对象
}
if ($uid != null) {
    $file_contents = curl_get_https('https://api.bilibili.com/x/space/acc/info?mid=' . $uid);
    $arr = json_decode($file_contents,true);
    $status = 200;
    $avatar=str_replace('http:','',$arr['data']['face']);
    if($arr['data']['mid']!=null)
    {
        $message = "获取用户信息成功";
        $data = array(
            "code"=>"1",
            "mid"=>$arr['data']['mid'],
            "name"=>$arr['data']['name'],
            "avatar"=>$avatar,
            "sex"=>$arr['data']['sex'],
            "level"=>$arr['data']['level'],
            "isVip"=>$arr['data']['vip']['status'],
            "nickname"=>$arr['data']['official']['title'],
            "sign"=>$arr['data']['sign']
        );
        $result = array(
            "status"=>$status,
            "msg"=>$message,
            "data"=>$data
            
        );
    }else{
        $message = "用户uid不存在";
        $data = array(
            "code"=>"0",
        );
        $result = array(
            "status"=>$status,
            "msg"=>$message,
            "data"=>$data
        );
    }

    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}else{
    $code = 201;
    $message = "参数错误";
    $data = "null";
    $result = array(
        "status"=>$status,
        "msg"=>$message,
        "data"=>$data
    );
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}



?>