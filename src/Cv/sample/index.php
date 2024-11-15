<?php

class indexC extends commonSubC{

	//初期設定**********************************

	//タイトル
	static public $title = 'コードサンプル';

	//追加CSS
	static public $addCss = array();

	//フレームレス
	static public $type = 'gid';

	//モデル読み込み
	static public $use = array();

	//実処理**********************************
	static public function beforeFilter(){
		
		
		//親の継承（意図しないかぎり消してはいけない）
		parent::beforeFilter();

	}

	static public function action(){

		
	}

}
