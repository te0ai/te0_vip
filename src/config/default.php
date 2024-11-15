<?php

if($_SERVER['HTTP_HOST']==='te0.ai'||$_SERVER['HTTP_HOST']==='api.te0.ai'){
  include_once('default/te0.ai.php');
}else if($_SERVER['HTTP_HOST']==='pre.te0.ai'||$_SERVER['HTTP_HOST']==='pre.api.te0.ai'){
  include_once('default/pre.te0.ai.php');
}else if($_SERVER['HTTP_HOST']==='te0.jp'||$_SERVER['HTTP_HOST']==='api.te0.jp'){
  include_once('default/te0.jp.php');
}else if($_SERVER['HTTP_HOST']==='pre.te0.jp'||$_SERVER['HTTP_HOST']==='api.pre.te0.jp'){
  include_once('default/pre.te0.jp.php');
}else if( $_SERVER['HTTP_HOST']==='dev.te0.ai'){
  include_once('default/dev.te0.ai.php');
}else if( $_SERVER['HTTP_HOST']==='ec2-13-231-199-33.ap-northeast-1.compute.amazonaws.com' ){
  include_once('default/ec2-13-231-199-33.ap-northeast-1.compute.amazonaws.com.php');
}else if($_SERVER['HTTP_HOST']==='localhost'||$_SERVER['HTTP_HOST']==='127.0.0.1'){
  include_once('default/localhost.php');
}else{
  echo 'Fatal : config data is not defined('.$_SERVER['HTTP_HOST'].').';
  exit();
}

//共通定数
include_once('common/common.php');