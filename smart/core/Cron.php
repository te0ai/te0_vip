<?php
//メンテナンス中は処理を中断
if (_MAINTENANCE_) {
  exit('CRON processing is stopped due to maintenance mode');
}

//セッション処理開始
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
session_start();// セッション開始

//必要追加定数
define('_SRC_','src/');
define('_ASRC_','smart/src/');
define('_CACHE_ROOT_',_ROOT_.'cache/');
define('_CACHE_DEFAULT_',_ROOT_.'cache/');

//必要ファイル読み込み
include_once(_ROOT_.'smart/core/Import.php');

//コアファイル読み込み
coreC::coreSet();

class Cron{

	//開始時間
	public static $start_time = 0;
	public static $pid = '';

  public static function model_use($arr=NULL){
    //モデルの読み込み
		if( isset($arr) && is_array($arr)){
			include_once(_ROOT_.'smart/core/Model.php');
			include_once(_ROOT_._SRC_.'Model/common.php');
			foreach($arr as $v){
				$path_arr = array();
				$path = str_replace('.','',$v);
				$path_arr = explode('/',$path);
				if($path_arr[0] !== 'index' && file_exists(_ROOT_._SRC_.'Model/'.$path_arr[0].'/common.php')) include_once(_ROOT_._SRC_.'Model/'.$path_arr[0].'/common.php');
				if(file_exists(_ROOT_._SRC_.'Model/'.$path.'.php')){
					include_once(_ROOT_._SRC_.'Model/'.$path.'.php');
				}
			}
		}
  }

  //共通クラスファイル
  public static function start($pid=NULL){

		//開始時間
		self::$start_time = microtime(true);

		//プロセスID
    if($pid===NULL){
      self::$pid = md5(mt_rand());
    }else{
      self::$pid = $pid;
    }

    //ロックファイルの存在確認
    $exist = array();
    $exist = My::select(array(
		  'HOST' => _DB_HOST_,
		  'DB' => _DBN_,
		  'USER' => _DB_USER_,
		  'PASSWORD' => _DB_PASSWORD_,
		  'TABLE' => "tmp_cronprc",
		  'WHERE' => array(
				'pid' => self::$pid,
        'db' => _DBN_,
        'pl' => _PL_,
        'delete' => 0,
		  ),
		));
    if(!$exist['status']){

      //DBが存在しない
      $main  = "--- database none(tmp_cronlog) ---\n\n";
      $main .= "CREATE TABLE IF NOT EXISTS `"._DBN_."`.`tmp_cronlog` (\n";
      $main .= "  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,\n";
      $main .= "  `pid` varchar(255) NOT NULL,\n";
      $main .= "  `db` varchar(30) NOT NULL,\n";
      $main .= "  `pl` varchar(10) NOT NULL,\n";
      $main .= "  `file` text NOT NULL,\n";
      $main .= "  `line` text NOT NULL,\n";
      $main .= "  `comment` text NOT NULL,\n";
      $main .= "  `microtime` decimal(18,9) NOT NULL,\n";
      $main .= "  `add_user` int(11) NOT NULL,\n";
      $main .= "  `edit_user` int(11) NOT NULL,\n";
      $main .= "  `add_date` datetime NOT NULL,\n";
      $main .= "  `edit_date` datetime NOT NULL,\n";
      $main .= "  `delete` int(1) NOT NULL,\n";
      $main .= "  PRIMARY KEY (`id`)\n";
      $main .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8;\n\n\n";

      $main .= "--- database none(tmp_cronprc) ---\n\n";
      $main .= "CREATE TABLE IF NOT EXISTS `"._DBN_."`.`tmp_cronprc` (\n";
      $main .= "  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,\n";
      $main .= "  `pid` varchar(255) NOT NULL,\n";
      $main .= "  `db` varchar(30) NOT NULL,\n";
      $main .= "  `pl` varchar(10) NOT NULL,\n";
      $main .= "  `file` text NOT NULL,\n";
      $main .= "  `line` int(11) NOT NULL,\n";
      $main .= "  `add_user` int(11) NOT NULL,\n";
      $main .= "  `edit_user` int(11) NOT NULL,\n";
      $main .= "  `add_date` datetime NOT NULL,\n";
      $main .= "  `edit_date` datetime NOT NULL,\n";
      $main .= "  `delete` int(1) NOT NULL,\n";
      $main .= "  PRIMARY KEY (`id`),\n";
      $main .= "  KEY `pid` (`pid`)\n";
      $main .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8;\n\n\n";
      exit(nl2br($main));

    }else if(!$exist['count']){

      //ロックファイル制作
      $backtrace = debug_backtrace();
      $rcd = My::insert(array(
  		  'HOST' => _DB_HOST_,
  		  'DB' => _DBN_,
  		  'USER' => _DB_USER_,
  		  'PASSWORD' => _DB_PASSWORD_,
  		  'TABLE' => "tmp_cronprc",
  		  'SET' => array(
  				'pid' => self::$pid,
          'db' => _DBN_,
          'pl' => _PL_,
          'file' => $backtrace[0]['file'],
          'line' => $backtrace[0]['line'],
  		  ),
  		));

    }else{

      //ロックされている
      $comment = 'pid:('.self::$pid.') is exists( FILE:'.__FILE__.' LINE:'.__LINE__.')';

      //30分経ってたらロック解除
      if($exist['data'][0]['add_date'] < date("Y-m-d H:i:s",strtotime("-30 min"))){
        $comment .= ' But unlocked because 30 min passed('.$exist['data'][0]['add_date'].').';
        $rcd = My::update(array(
          'HOST' => _DB_HOST_,
          'DB' => _DBN_,
          'USER' => _DB_USER_,
          'PASSWORD' => _DB_PASSWORD_,
          'TABLE' => "tmp_cronprc",
          'SET' => array(
            'delete' => 1,
          ),
          'WHERE' => array(
    				'pid' => self::$pid,
            'db' => _DBN_,
            'pl' => _PL_,
            'delete' => 0,
    		  ),
        ));
      }
      self::log($comment, self::$pid);

      exit($comment);

    }

  }

	public static function end($pid=NULL){

    //プロセスID
    if($pid!==NULL){
      self::$pid = $pid;
    }

    //ロックファイル削除
    $rcd = My::update(array(
      'HOST' => _DB_HOST_,
      'DB' => _DBN_,
      'USER' => _DB_USER_,
      'PASSWORD' => _DB_PASSWORD_,
      'TABLE' => "tmp_cronprc",
      'SET' => array(
        'delete' => 1,
      ),
      'WHERE' => array(
				'pid' => self::$pid,
        'db' => _DBN_,
        'pl' => _PL_,
        'delete' => 0,
		  ),
    ));

  }

  public static function log($str,$pid=NULL){

    //プロセスID
    if($pid!==NULL){
      self::$pid = $pid;
    }

		$microtime =  microtime(true) - self::$start_time;
    $backtrace = debug_backtrace();

		My::insert(array(
		  'HOST' => _DB_HOST_,
		  'DB' => _DBN_,
		  'USER' => _DB_USER_,
		  'PASSWORD' => _DB_PASSWORD_,
		  'TABLE' => "tmp_cronlog",
		  'SET' => array(
        'pid' => self::$pid,
        'db' => _DBN_,
        'pl' => _PL_,
        'file' => $backtrace[0]['file'],
        'line' => $backtrace[0]['line'],
        'comment' => $str,
        'microtime' => $microtime,
		  ),
		));
  }

}

?>
