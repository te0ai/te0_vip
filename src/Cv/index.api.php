<?php

class indexC extends commonC{

	//初期設定**********************************

	//タイトル
	static public $title = 'トップページ';

	//追加CSS
	static public $addCss = array('index');

	//インデックス用フレーム
	static public $type = 'common';

	//ロール
	static public $role = array();

	//実処理**********************************
	static public function beforeFilter(){

		//親の継承（意図しないかぎり消してはいけない）
		parent::beforeFilter();

		self::$addScript[] = 'index';

	}

	static public function action(){


	}



}
