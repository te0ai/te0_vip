<?php
/**
 * 全ページ共通処理
 *
 */
class commonC extends coreC{

	//タイトル
	static public $title = '';

	//イメージ
	static public $img = '';

	//Javascriptに送るコンフィグ
	static public $configScript = '';

	//使用モデル
	static public $use = array();

	//基本CSS
	static public $css = '';

	//追加CSS
	static public $addCss = array();

	//追加JS
	static public $addScript = array();

	//フェッチ
	static public $fetch = array();

	//リダイレクト
	static public $redirect = array();

	//ロール設定(空で誰でもアクセス可能)
	static public $role = array();

	//フレームタイプ
	static public $type = '';

	//メニューアクティブ化
	static public $active = '';

	//共通処理
	static public function beforeFilter(){

		//config情報を送る
		self::$configScript = array('_HOME_','_IMG_HOME_','_OB_HOME_','_API_HOME_','_DEBUG_');

		//ポップアップの場合はフレームレス
		$frame = Session::get('frame');
		if(isset($frame))self::$type = $frame;

		//デバッグの場合はデバッグ用のCSS
		$debug = Session::get('debug');
		if(isset($debug)){
			self::$addCss[] = 'debug';
			self::$addScript[] = 'debug';
		}

		// カスタム例外ハンドラを設定
		set_exception_handler("commonC::customErrorHandler");
	}

	// エラーおよび例外発生時にログをテーブルに記録
	static public function customErrorHandler($e) {
		//エラーをDBに記録
		$db_data = array();
		$db_data = My::insert(array(
			'HOST' => _DB_SESSION_HOST_,
			'DB' => _DB_SESSION_DBN_,
			'USER' => _DB_RW_USER_,
			'PASSWORD' => _DB_RW_PASSWORD_,
			'DB' => 'te0_session',
			'TABLE' => 'tmp_phperrorlog',
			'SET' => array(
				'error_message' => (string)$e,
				'error_file' => $e->getFile(),
				'error_line' => $e->getLine(),
			),
		));
		$rcd = Mail::send(array(
			'to' => 'te0jpsystem+fatalalert@gmail.com',
			'title' => "PHPエラー発生".date("Y-m-d H:i:s"),
			'set' => array(
				'pl' => _PL_,
				'gl' => _GL_,
				'host' => gethostname(),
				'error_message' => (string)$e,
				'error_file' => $e->getFile(),
				'error_line' => $e->getLine(),
			),
			'template' => 'error/php',
		));
		if (ini_get('display_errors')) {
			echo ((string)$e);
		}else{
			echo "<html><head><meta charset=\"UTF-8\"><title>エラーが発生しました</title></head><body><h1>原因不明のエラーが発生しました</h1><p>このたびはご不便をおかけし、誠に申し訳ございません。エラーの内容は自動送信されており当方で把握しております。迅速に調査を進め、解決に努めておりますので、今しばらくお待ちくださいますようお願い申し上げます。</p></body></html>";
		}
		exit();
	}
}
