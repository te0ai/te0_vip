<?php

class indexC extends commonSubC{

	//初期設定**********************************

	//タイトル
	static public $title = '利用規約';

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

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			// プロモーションコードを取得
			$gid = isset($_POST['gid']) ? $_POST['gid'] : '';
			if(is_numeric($gid)){
				$_SESSION['auth']['gid'] = $gid;
				$_SESSION['auth']['type'] = 'marchant';
			}

			//転送
			if (
				$_SESSION['auth']['type'] !== 'crew'
			) {
				self::$redirect = array('url' => _HOME_.'gid'.$gid, 'sec' => 0);
				return false;
			}

		}

	}

}
