<?php

class Session{

	//セッション書き込み
	static public function write($arr){

		//$_SESSION = array_merge($_SESSION,$arr);
		$_SESSION = array_replace_recursive($_SESSION,$arr);

	}

	//プッシュする
	static public function push($arr){

		foreach( $arr as $k => $v ){
			if(!is_array($v)){
				$_SESSION[$k][] = $v;
			}else{
				foreach( $v as $k2 => $v2 ){
					if(!isset($_SESSION[$k][$k2]) || !is_array($_SESSION[$k][$k2])) $_SESSION[$k][$k2] = array();
					$_SESSION[$k][$k2][] = $v2;
				}
			}

		}

	}

	//配列をリセットする
	static public function reset($arr){
		foreach( $arr as $k => $v ){
			if(empty($v)){
				$_SESSION[$k] = array();
			}
		}
	}

	//セッション呼び出し
	static public function read($str=''){

		if (strpos($str, '.') === false){

			//点が存在しない配列要求

			if($str===''){

				return $_SESSION;

			}else{

				if(isset($_SESSION[$str])){
					return $_SESSION[$str];
				}else{
					return NULL;
				}

			}

		}else{

			//点が存在する直接参照

			//直接アクセス
			$str_arr = array();
			$str_arr = explode('.',$str);

			$config = $_SESSION;
			foreach ($str_arr as $v){
				if (!isset($config[$v])) {
					return NULL;
				}
				$config = $config[$v];
			}

			return $config;

		}

	}

	//ゲット呼び出し
	static public function get($str=''){

		if (strpos($str, '.') === false){

			//点が存在しない配列要求

			if($str===''){

				return $_GET;

			}else{

				if(isset($_GET[$str])){
					return $_GET[$str];
				}else{
					return NULL;
				}

			}

		}else{

			//点が存在する直接参照

			//直接アクセス
			$str_arr = array();
			$str_arr = explode('.',$str);

			$config = $_GET;
			foreach ($str_arr as $v){
				if (!isset($config[$v])) {
					return NULL;
				}
				$config = $config[$v];
			}

			return $config;

		}

	}

	//キャッシュ呼び出し(Helperエイリアス)
	public static function cache($str,$lev=''){

		if($lev===''){
			return Helper::cacheRead(array(
				'path' => $str,
			));
		}else{

			$arr = array();
			$arr = Helper::cacheRead(array(
				'path' => $str,
			));

			$str_arr = array();
			$str_arr = explode('.',$lev);

			foreach ($str_arr as $v){
				if (!isset($arr[$v])) {
					return '';
				}
				$arr = $arr[$v];
			}

			return $arr;

		}


	}

	//ログアウト
	static public function LogOut(){

		//全てを初期化
		$_SESSION = array();
		session_destroy();

	}

	//リフレッシュ情報呼び出し
  public static function get_refresh($gid='0'){

    $gid = isset($_SESSION['auth']['gid']) ? $_SESSION['auth']['gid'] : $gid;
    if((string)$gid==='0'||!is_numeric($gid)) return false;
    //ニューメリック限定だからSQLインジェクション不可能、ここをいじるときは注意！！！！
    $sys_refresh = array();
    $sys_refresh = My::show("SELECT `time` FROM `sys_refresh` WHERE `gid` =".$gid." ORDER BY `sys_refresh`.`time` DESC LIMIT 1;");
    if($sys_refresh['count']){
      return $sys_refresh['data'][0]['time'];
    }else{
      return date("Y-m-d H:i:s");
    }

  }

  //リフレッシュ情報セット
  public static function set_refresh($gid='0'){
    $gid = isset($_SESSION['auth']['gid']) ? $_SESSION['auth']['gid'] : $gid;
    if((string)$gid==='0'||!is_numeric($gid)) return false;
    //ニューメリック限定だからSQLインジェクション不可能、ここをいじるときは注意！！！！
    $sys_refresh = array();
    $sys_refresh = My::edit("INSERT INTO `sys_refresh` (`gid` ,`time`)VALUES ('".$gid."','".date("Y-m-d H:i:s")."');");
    if($sys_refresh['status']){
      return true;
    }else{
      return false;
    }
  }

	//リフレッシュ情報呼び出し
  public static function get_lock($key,$arr=array()){
		//組み立て
		$gid = isset($arr['gid']) ? $arr['gid'] : $_SESSION['auth']['gid'];
		$uid = isset($arr['uid']) ? $arr['uid'] : $_SESSION['auth']['uid'];
		$key = preg_replace('/[^0-9a-zA-Z\_\-]/su','',$key);
		$key = str_replace('_','\_',$key);
		//整合性
		if(!is_numeric($gid)) return false;
		if(!is_numeric($uid)) return false;
		if($key==='') return false;
    //ニューメリックor安全文字 限定だからSQLインジェクション不可能、ここをいじるときは注意！！！！
    $sys_lock = array();
    $sys_lock = My::show("SELECT `uid`,`user`,`expiration` FROM `sys_lock` WHERE `gid` =".$gid." AND `key` LIKE '".$key."' AND `expiration` > '".date("Y-m-d H:i:s")."' AND `delete` = 0 ORDER BY `sys_lock`.`expiration` DESC LIMIT 1;");
    if($sys_lock['count']){
			return array(
				'uid' => $sys_lock['data'][0]['uid'],
				'user' => $sys_lock['data'][0]['user'],
				'expiration' => $sys_lock['data'][0]['expiration'],
			);
    }else{
      return false;
    }

  }

  //リフレッシュ情報セット
  public static function set_lock($key,$sec=0,$arr=array()){
		//組み立て
    $gid = isset($arr['gid']) ? $arr['gid'] : $_SESSION['auth']['gid'];
		$uid = isset($arr['uid']) ? $arr['uid'] : $_SESSION['auth']['uid'];
		$key = preg_replace('/[^0-9a-zA-Z\_\-]/su','',$key);
		if(isset($_SESSION['auth']['user']['title'])&&$_SESSION['auth']['user']['title']!==''){
			$user = My::r($_SESSION['auth']['user']['title']);
		}else{
			$user = 'ユーザー('.$uid.')';
		}
		//整合性
		if(!is_numeric($gid)) return false;
		if(!is_numeric($uid)) return false;
		if($key==='') return false;
		if((string)$sec==='0'||!is_numeric($sec)) $sec = _SYS_LOCK_TIME_;
    //ニューメリックor安全文字 限定だからSQLインジェクション不可能、ここをいじるときは注意！！！！
    $sys_lock = array();
    $sys_lock = My::edit("INSERT INTO `sys_lock` (`gid`,`uid`,`user`,`key`,`expiration`,`delete`)VALUES ('".$gid."','".$uid."','".$user."','".$key."','".date("Y-m-d H:i:s",strtotime("+".$sec." second"))."','0');");
    if($sys_lock['status']){
      return true;
    }else{
      return false;
    }
  }

	//自分の情報
	public static function iam($str=''){
		$iam = array();
		$iam = self::read('auth');
		if($str===''){
			return $iam;
		}else if(isset($iam[$str])){
			return $iam[$str];
		}else{
			return false;
		}
	}

	//ログイン初期行動
	static public function LoginSuccess($arr){

		//初期化
		$_SESSION = array();

		$arr2 = array();
		$arr2['auth'] = $arr;
		$arr2['auth']['login'] = true;

		self::write($arr2);
		return true;

	}

	//ログインされているか
	static public function loggedIn(){

		$bool = self::read('auth.login');

		if($bool===NULL){
			return false;
		}else{
			return true;
		}
	}

	//インフォ
	public static function info($message = ''){
		$arr = array();
		$arr['Message']['info'] = $message;
		self::push($arr);
	}

	//ワーニング
	public static function warning($message = ''){
		$arr = array();
		$arr['Message']['warning'] = $message;
		self::push($arr);
	}

	//ソフトなエラー
	public static function error($message = ''){
		$arr = array();
		$arr['Message']['error'] = $message;
		$arr['Message']['etype'] = 'error';
		self::push($arr);
	}
	//ソフトエラーかどうか判別
	static public function is_error(){

		$etype = self::read('Message.etype');
		if( isset($etype) && is_array($etype) &&  in_array("error",$etype,true) ){
			return true;
		}else{
			return false;
		}

	}

	//ハードなエラー（表示停止）
	static public function fatal($message = '',$errorCode=400) {

		$arr = array();
		$arr['Message']['error'] = $message;
		$arr['Message']['ecode'] = $errorCode;
		$arr['Message']['etype'] = 'fatal';
		self::write($arr);

	}
	//ハードエラーかどうか判別
	static public function is_fatal(){

		$etype = self::read('Message.etype');
		if( isset($etype) && $etype === "fatal" ){
			return true;
		}else{
			return false;
		}

	}



	//メッセージをセット
	static public function setFlash($message, $element = 'default', $params = array(), $key = 'flash') {

		$arr = array();
		$arr['Message']['flash']['message'] = $message;
		$arr['Message']['flash']['element'] = $element;
		$arr['Message']['flash']['params'] = $params;
		self::write($arr);

	}

	//メッセージを表示
	static public function flash() {

		$arr = self::read();

		if(!empty($arr['Message'])){

			//エラーが格納
			$dup = array();
			if( isset($arr['Message']['error']) && is_array($arr['Message']['error']) ){
				foreach($arr['Message']['error'] as $message){
					if(!isset($dup[$message])){
						Elem::import('info/error',array('message'=>$message));
						$dup[$message] = true;
					}
				}
			}

			//ワーニングが格納
			$dup = array();
			if( isset($arr['Message']['warning']) && is_array($arr['Message']['warning']) ){
				foreach($arr['Message']['warning'] as $message){
					if(!isset($dup[$message])){
						Elem::import('info/warning',array('message'=>$message));
						$dup[$message] = true;
					}
				}
			}

			//インフォが格納
			$dup = array();
			if( isset($arr['Message']['info']) && is_array($arr['Message']['info']) ){
				foreach($arr['Message']['info'] as $message){
					if(!isset($dup[$message])){
						Elem::import('info/info',array('message'=>$message));
						$dup[$message] = true;
					}
				}
			}


		}


	}


}

?>
