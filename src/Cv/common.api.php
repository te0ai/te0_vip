<?php
/**
 * 全ページ共通処理
 *
 */
class commonC extends coreC{

	//タイトル
	static public $title = '';

	//Javascriptに送るコンフィグ
	static public $configScript = '';

	//モデル読み込み
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


	}


}
