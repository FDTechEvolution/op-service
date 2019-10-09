<?php
header ("Access-Control-Allow-Origin: *");
header ("Access-Control-Allow-Methods: *");
header ("Access-Control-Allow-Credentials: true");
header ("Access-Control-Allow-Headers: DNT,X-Mx-ReqToken,Keep-Alive,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Content-Type,Authorization");
//echo $this->fetch('content');
echo isset($json)?$json:'';
//echo $json;
