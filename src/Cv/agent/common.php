<?php
/**
 * フォルダ内共通処理
 *
 */
class commonSubC extends commonC{

	//共通処理
	static public function beforeFilter(){

		$gid = '6';

		//タイプが「merchant」でなければ転送
		if (
			!isset($_SESSION['auth']['gid']) ||
			$_SESSION['auth']['gid'] !== $gid
		) {
			self::$redirect = array('url' => _HOME_, 'sec' => 0);
			return false;
		}

		$menu = array();
		$menu[] = array(
			'name' => 'ログイン',
			'url' => 'gid'.$gid.'/'
		);
		self::set('menu', $menu);

		//親の継承（意図しないかぎり消してはいけない）
		parent::beforeFilter();

	}

	static public function beforeSubFilter(){

		

	}

}