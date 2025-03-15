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
       'header'=>"Content-type: application/json\n".
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
if((!$content)||(!$cookie)) die('��������ݸ�ʽ�������鷢�͵������Ƿ���ȷ');
$uuid=postRequest('http://jxgl.dlut.edu.cn/student/ws/for-std/course-select/add-request',$content,$cookie);
if(!$uuid) echo 'error';
else if(substr($uuid,0,9)==='<!DOCTYPE') echo 'false';
else echo $uuid;
