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
			$dtb_org = array();
			$dtb_org = My::select(array(
				'HOST' => _DB_COMMON_RO_HOST_,
				'DB' => _DB_COMMON_RO_DBN_,
				'USER' => _DB_RO_USER_,
				'PASSWORD' => _DB_RO_PASSWORD_,
				'TABLE' => 'dtb_org',
				'WHERE' => array(
					'id' => $gid,
					'agt' => $_SESSION['auth']['gid'],
					'delete' => 0
				),
				'LIMIT' => 1
			));
			if ((string)$dtb_org['count'] !== '0') {
				$_SESSION['auth']['gid'] = $gid;
				$_SESSION['auth']['type'] = 'marchant';
			}else{
				Session::error('gid:'.$gid.'は御社管轄の組織ではありません。');
				return;
			}

			//転送
			if (
				$_SESSION['auth']['type'] !== 'agent'
			) {
				self::$redirect = array('url' => _HOME_.'gid'.$gid, 'sec' => 0);
				return false;
			}

		}

	}

}
