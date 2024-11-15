<?php

//コンフィグファイル読み込み
include_once('../src/config/default.php');

// エラー出力する場合
if(_DEBUG_) ini_set('display_errors',1);

//セッション処理開始
if (_MAINTENANCE_!==true) {
  require_once _ROOT_.'smart/core/SysSession.php';
  $handler = new MysqlSessionHandler();
  session_set_save_handler(
      array($handler, 'open'),
      array($handler, 'close'),
      array($handler, 'read'),
      array($handler, 'write'),
      array($handler, 'destroy'),
      array($handler, 'gc')
  );
  register_shutdown_function('session_write_close');// シャットダウンする際にセッション情報を書き込んでクローズ
  session_start(); // セッション開始
}

//DB情報セット
if(isset($_SESSION['auth']['org']['cn'])){
  require_once _ROOT_.'smart/core/Define.php';
  Define::set('connection/'.$_SESSION['auth']['org']['cn']);
}

//必要ファイル読み込み
include_once(_ROOT_.'smart/core/Import.php');

//URLを解析
$_REQUEST = array();
$_REQUEST = parse_url($_SERVER['REQUEST_URI']);
if(_PATH_==='/'){
	$_REQUEST['URI'] = explode('/',trim($_REQUEST['path'],'/'));
}else{
	$_REQUEST['URI'] = explode('/',trim(str_replace(_PATH_,'',$_REQUEST['path']),'/'));
}
$_REQUEST['URL'] = $_SERVER['REQUEST_URI'];

//パス生成＆ディレクトリトラバーサル対策
$_PATH = '';
if( isset($_REQUEST['URI'][0]) && !empty($_REQUEST['URI'][0])){
	if($_REQUEST['URI'][0] !== _URI_ADMIN_ ) $_PATH .= str_replace('.','',$_REQUEST['URI'][0]);//予約URLでなければパスとして使用
	if(isset($_REQUEST['URI'][1]) && !empty($_REQUEST['URI'][1])){
		$_PATH .= '/'.str_replace('.','',$_REQUEST['URI'][1]);
    if($_REQUEST['URI'][0] === _URI_ADMIN_ ){
      //予約URLだったら第二階層も取得
      if(isset($_REQUEST['URI'][2]) && !empty($_REQUEST['URI'][2])){
    		$_PATH .= '/'.str_replace('.','',$_REQUEST['URI'][2]);
    	}else{
    		$_PATH .= '/index';
        $_REQUEST['URI'][2] = 'index';
    	}
    }
	}else{
		$_PATH .= '/index';
    $_REQUEST['URI'][1] = 'index';
	}
}else{
	$_PATH = 'index';
  $_REQUEST['URI'][0] = 'index';
}


//ソースルートの場所指定
if($_REQUEST['URI'][0]===_URI_ADMIN_){
  //admin
  define('_SRC_','smart/src/');
  define('_ASRC_','smart/src/');
  //先頭の予約語を詰める(速度のためにわざと関数使わない)
  $tmp_arr = array();
  $tmp_arr = $_REQUEST['URI'];
  $_REQUEST['URI'] = array();
  $_REQUEST['URI'][0] =$tmp_arr[1];
  $_REQUEST['URI'][1] =$tmp_arr[2];
}else{
  //通常
  define('_SRC_','src/');
  define('_ASRC_','smart/src/');
}

//ページ表示処理
if(file_exists(_ROOT_._SRC_.'Cv/'.$_PATH.'.php')){

	include_once(_ROOT_._SRC_.'Cv/common.php');
  if(
    $_REQUEST['URI'][0] !== 'index' &&
    file_exists(_ROOT_._SRC_.'Cv/'.$_REQUEST['URI'][0].'/common.php')
  ){
    include_once(_ROOT_._SRC_.'Cv/'.$_REQUEST['URI'][0].'/common.php');
  }
	include_once(_ROOT_._SRC_.'Cv/'.$_PATH.'.php');

	$ClassName = basename($_PATH).'C';
	$ClassName::$coreFilePath = $_PATH;
	$ClassName::coreSet($ClassName);
	if(
    !Session::is_fatal() &&
    !(
      isset($ClassName::$redirect['sec']) &&
      (string)$ClassName::$redirect['sec'] === '0'
    )
  ){
    $ClassName::action();
  }
	$ClassName::coreView($ClassName);

}else{

	//404ページ
	include_once(_ROOT_._SRC_.'Cv/common.php');
  include_once(_ROOT_._SRC_.'Cv/index.php');

  $ClassName = 'indexC';
	$ClassName::$coreFilePath = 'index';
  $ClassName::coreSet($ClassName);
	Session::fatal('指定のページは削除されたか存在しません。',404);
	$ClassName::coreView($ClassName);

}
