<?php

class exitC extends commonSubC{

	//初期設定**********************************

	//タイトル
	static public $title = 'ログアウト';

	//追加CSS
	//static public $addCss = array('login');

	//フレームレス
	static public $type = 'naked';

	//実処理**********************************
	static public function beforeFilter(){

		//親の継承（意図しないかぎり消してはいけない）
		parent::beforeFilter();

	}

	static public function action(){

		// セッション変数の初期化
		$_SESSION = array();
		// セッションファイルの削除
		session_destroy();

		//ログインしていない場合はリダイレクト
		if(!Session::loggedIn()){
			self::$redirect = array('url' => '' ,'sec' =>2);
		}

	}

}
