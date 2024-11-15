<?php

if($_SERVER['HTTP_HOST']=== 'vip.te0.ai'){
  include_once('default/vip.te0.ai.php');
}else if($_SERVER['HTTP_HOST']==='localhost'||$_SERVER['HTTP_HOST']==='127.0.0.1'){
  include_once('default/localhost.php');
}else{
  echo 'Fatal : config data is not defined('.$_SERVER['HTTP_HOST'].').';
  exit();
}

//共通定数
include_once('common/common.php');