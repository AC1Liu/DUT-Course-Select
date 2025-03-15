<?php
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Origin: *');
function getheaders($param=null)
{
    $headers=array();
    $filteredkeys=array("Dlut-Cas-Un","Cas-Hash","Jsessionidcas");
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
$cookie=getheaders();
if(is_array($cookie))
{
    $str='';
    foreach ($cookie as $key=>$value){
        if($key==="Jsessionidcas") $str.=strtoupper($key).'='.$value.'; ';
        else $str.=preg_replace('/-/','_',strtolower($key)).'='.$value.'; ';
    }
    $cookie=substr($str,0,-1);
}
$url='https://sso.dlut.edu.cn/cas/login?service=http%3A%2F%2Fjxgl.dlut.edu.cn%2Fstudent%2Fucas-sso%2Flogin';
$opts=array(
   'http'=>array(
   'method'=>"POST",
   'header'=>"Content-type: application/x-www-form-urlencoded\n".
             "Cookie: ".$cookie."\n".
             "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.2365.106 Safari/537.36\n".
             "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8\n".
             "\n",
   'content'=>file_get_contents('php://input'),
   )
);
$context=stream_context_create($opts);
$text=file_get_contents($url,false,$context);
$responseInfo = $http_response_header;
foreach ($responseInfo as $name => $content)
{
   if(preg_match('/SESSION=(.*?);/',$content,$matched)) { $ses=$matched[1]; break; }
}
foreach ($responseInfo as $name => $content)
{
   if(preg_match('/INGRESSCOOKIE=(.*?);/',$content,$matched)) { $ing=$matched[1]; break; }
}
$url='http://jxgl.dlut.edu.cn/student/ucas-sso/login';
$opts=array(
   'http'=>array(
   'method'=>"GET",
   'header'=>"User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.2365.106 Safari/537.36\n".
             "Cookie: SESSION=".$ses."; INGRESSCOOKIE=".$ing."\n".
             "\n"
   )
);
$context=stream_context_create($opts);
file_get_contents($url,false,$context);
$url='http://jxgl.dlut.edu.cn/student/for-std/course-select';
$opts=array(
   'http'=>array(
   'method'=>"GET",
   'header'=>"User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.2365.106 Safari/537.36\n".
             "Cookie: SESSION=".$ses."; INGRESSCOOKIE=".$ing."\n".
             "\n"
   )
);
$context=stream_context_create($opts);
$text=file_get_contents($url,false,$context);
preg_match('/studentId: (.*?),/',$text,$matched);
echo $matched[1].';'.$ses.';'.$ing;