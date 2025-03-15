<?php
header('Access-Control-Allow-Origin: *');
if(isset($_GET['v'])) echo file_get_contents("http://jxgl.dlut.edu.cn/student/cache/course-select/version/".$_GET['v']."/version.json");