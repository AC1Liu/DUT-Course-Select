<?php
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Origin: *');
$url='https://sso.dlut.edu.cn/cas/login?service=http%3A%2F%2Fjxgl.dlut.edu.cn%2Fstudent%2Fucas-sso%2Flogin';
$opts=array(
   'http'=>array(
   'method'=>"GET",
   'header'=>"User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.2365.106 Safari/537.36".
             "\n"
   )
);
$context=stream_context_create($opts);
$text=file_get_contents($url,false,$context);
$responseInfo = $http_response_header;
foreach ($responseInfo as $name => $content)
{
   if(preg_match('/JSESSIONIDCAS=(.*?);/',$content,$matched)) { echo $matched[1].';'; break; }
}
preg_match('/LT(.*?)cas/',$text,$matched); echo $matched[0];