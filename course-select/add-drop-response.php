<?php
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Origin: *');
function postRequest($url,$data,$cookie=null)
{
    if(is_array($cookie))
    {
        $str='';
        foreach ($cookie as $key=>$value){
            $str.=strtoupper($key).'='.$value.'; ';
        }
        $cookie=substr($str,0,-1);
    }
    $opts=array(
       'http'=>array(
       'method'=>"POST",
       'header'=>"Content-type: application/x-www-form-urlencoded\n".
                 "Cookie: ".$cookie."\n".
                 "Content-length:".strlen($data)."\n".
                 "\n",
       'content'=>$data,
       )
    );
    $context=stream_context_create($opts);
    $ret=file_get_contents($url,false,$context);
    return $ret;
}
function getheaders($param=null) {
    $headers=array();
    $filteredkeys=array("Session","Ingresscookie");
    foreach ($_SERVER as $name=>$value) {
        if (substr($name,0,5)=='HTTP_') {
           $name=str_replace(' ','-',ucwords(strtolower(str_replace('_',' ',substr($name,5)))));
           if(in_array($name,$filteredkeys))
               $headers[$name]=$value;
        }
    }
    if($param!=null){
        return $headers[$param];
    }
    return $headers;
}
$content=file_get_contents('php://input');
$cookie=getheaders();
if((!$content)||(!$cookie)) die('传入的数据格式有误，请检查发送的数据是否正确');
$resp=postRequest('http://jxgl.dlut.edu.cn/student/ws/for-std/course-select/add-drop-response',$content,$cookie);
if(!$resp) echo 'error';
else if(substr($resp,0,9)==='<!DOCTYPE') echo 'false';
else echo $resp;
