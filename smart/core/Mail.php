<?php
////////////////////////////////////////////////////////////////////////////////
/// @class      SwiftMailを使ったメールクラス
/// @brief      SwiftMailを使ったメールクラスです、利用にはvendorにswiftmailerが必要です
/// @copyright  株式会社ワイアード＆エコ
/// @author 鈴木巨史
/// @code
/// 使い方は以下の通り
/// $rcd = Mail::send(array(
/// 	'host' => "ホスト名",
/// 	'port' => "ポート番号(通常は587)",
/// 	'user' => "ユーザー名",
/// 	'pass' => "パスワード",
/// 	'name' => "送信者名",
/// 	'from' => "送信元アドレス",
/// 	'to' => "送信先アドレス",
/// 	'title' => "件名",
/// 	'body' => "本文",
/// 	'cc' => array("アドレス１","アドレス２"),
/// 	'bcc' => array("アドレス１","アドレス２"),
/// 	'files' => "添付ファイルパス(内部PATH)",
/// ));
/// @endcode
////////////////////////////////////////////////////////////////////////////////

class Mail{

	static public function send($arr){

		//必須チェック
		if(!defined("_ROOT_")) return self::error("必須パラメーター_ROOT_が定義されていません");
		if(!is_dir(_ROOT_.'vendor/swiftmailer')) return self::error('「'._ROOT_.'vendor/swiftmailer」が存在しません');
		if(!isset($arr['host'])&&!defined("_MAIL_HOST_")) return self::error("_MAIL_HOST_ または hostは必須パラメーターです");
		if(!isset($arr['user'])&&!defined("_MAIL_USER_")) return self::error("_MAIL_USER_ または userは必須パラメーターです");
		if(!isset($arr['pass'])&&!defined("_MAIL_PASS_")) return self::error("_MAIL_PASS_ または passは必須パラメーターです");
		if(!isset($arr['from'])&&!defined("_MAIL_FROM_")) return self::error("_MAIL_FROM_ または fromは必須パラメーターです");
		if(!isset($arr['name'])&&!defined("_TITLE_")) return self::error("_TITLE_ または nameは必須パラメーターです");
		if(!isset($arr['to'])) return self::error("toは必須パラメーターです");
		if(!self::valid($arr['to'])) return self::error("「".$arr['to']."」は正しいメールアドレスの形式ではありません");
		if(!isset($arr['body'])&&!isset($arr['template'])) return self::error("bodyまたはtemplateは必須パラメーターです");
		if(isset($arr['template'])&&!is_file(_ROOT_.'src/Template/Email/'.str_replace('.','',$arr['template']).'.ctp')) return self::error($arr['template']."は存在しません");
		if(isset($arr['files'])&&!is_file($arr['files'])) return self::error($arr['files']."は存在しません");
		//ソフト読み込み
		date_default_timezone_set('Asia/Tokyo');
		require_once _ROOT_.'vendor/swiftmailer/autoload.php';
		//補完処理
		if(!isset($arr['host'])) $arr['host'] = _MAIL_HOST_;
		if(!isset($arr['user'])) $arr['user'] = _MAIL_USER_;
		if(!isset($arr['pass'])) $arr['pass'] = _MAIL_PASS_;
		if(!isset($arr['from'])) $arr['from'] = _MAIL_FROM_;
		if(!isset($arr['name'])) $arr['name'] = _TITLE_;
		if(!isset($arr['port'])) $arr['port'] = _MAIL_PORT_;
		if(!isset($arr['enc'])) $arr['enc'] = _MAIL_ENC_==='tls' ? 'tls' : 'ssl';
		if(!isset($arr['html'])){
			$arr['mime'] = 'text/plain';
		}else{
			$arr['mime'] = 'text/html';
		}
		//処理開始
		$stderr = fopen('php://stderr','wb');
		fputs($stderr,"*** 開始 ***\n");

		$transport = (new Swift_SmtpTransport($arr['host'],$arr['port'],$arr['enc']))
		  ->setUsername($arr['user'])
		  ->setPassword($arr['pass']);

		// 3. Swift_Mailer クラスを new する
		$mailer = new Swift_Mailer($transport);
		//メッセージ製作
		$message = new Swift_Message();
		$message->setSubject($arr['title']);
		$message->setFrom([$arr['from'] =>$arr['name']]);
		if(!isset($arr['body'])) $arr['body'] = "";
		if(defined('_MAIL_DEBUG_')&&_MAIL_DEBUG_!==''){
			$message->setTo([_MAIL_DEBUG_]);//デバッグモードの場合メール送信しない
			$arr['body'] .= "※デバッグモードにより「".$arr['to']."」ではなく「"._MAIL_DEBUG_."」に送信されています。\n\n".$arr['body'];
		}else{
			$message->setTo([$arr['to']]);
		}
		if(isset($arr['template'])){
			if(!isset($arr['set'])) $arr['set'] = array();
			$arr['body'] .= self::template($arr['template'],$arr['set']);
		}
		$message->setBody($arr['body'],$arr['mime']);
		if(isset($arr['cc'])) $message->setCc($arr['cc']);
		if(isset($arr['bcc'])) $message->setBcc($arr['bcc']);
		if(isset($arr['files'])) $message->attach(Swift_Attachment::fromPath($arr['files']));
		try{
			$result = $mailer->send($message);
			if(defined('_MAIL_LOG_')&&_MAIL_LOG_){
				//DBに送信履歴を記録
				self::lg($arr['body'],$arr['to'],$arr['title']);
			}
		}catch (Exception $e){
			return self::error($e->getMessage());
		}
		fputs($stderr,"*** 終了 ***\n");
		return array(
			'status' => 'success',
			'message' => $result
		);
	}

	static private function template($template,$set) {
		$body = "";
		if( !is_null( $template ) ){
			$path = str_replace('.','',$template);
			if(is_file(_ROOT_.'src/Template/Email/'.$path.'.ctp')){
				extract($set,EXTR_SKIP);
				ob_start();
				include(_ROOT_.'src/Template/Email/'.$path.'.ctp');
				$body = ob_get_contents();
				ob_end_clean();
			}
		}
		return $body;
	}


	static public function error($msg){
		return array(
			'status' => 'error',
			'message' => $msg
		);
	}

	public static function valid( $addr ){

		if( strlen( $addr ) <=0 ) return false;
		if( defined("_MAIL_ALIAS_") && _MAIL_ALIAS_ ){
			//エイリアス(xxx+123@gmail.com)を許可
			$result = preg_match( "/^([a-zA-Z0-9])+([\+a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $addr );
		}else{
			$result = preg_match( "/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $addr );
		}
		if( $result === false || $result === 0 ) return false;
		return true;

	}

	static private function lg($body,$to,$title){

		$gid = isset($_SESSION['auth']['gid']) ? $_SESSION['auth']['gid'] : '0';

		$debug = array();$env = array();
		$debug = debug_backtrace();
		foreach($debug as $k => $v){
			$env[$k] = array();
			if(isset($v['file'])) $env[$k]['file'] = $v['file'];
			if(isset($v['line'])) $env[$k]['line'] = $v['line'];
			if(isset($v['function'])) $env[$k]['function'] = $v['function'];
			if(isset($v['class'])) $env[$k]['class'] = $v['class'];
		}

		$rcd = My::insert(array(
	    'HOST' => _DB_LOG_HOST_,
	    'DB' => _DB_LOG_DBN_,
	    'USER' => _DB_RW_USER_,
	    'PASSWORD' => _DB_RW_PASSWORD_,
			'TABLE' => 'tmp_mail',
			'SET' => array(
				'gid' => $gid,
				'to' => $to,
				'body' => $body,
				'title' => $title,
				'env' => json_encode($env),
			),
		));

	}

}

?>
