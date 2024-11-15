<?php
/**
 * フォルダ内共通処理
 *
 */
class commonSubC extends commonC{

	//共通処理
	static public function beforeFilter(){

		if (_MAINTENANCE_) {
			self::$redirect = array('url' => _HOME_ . 'terms/maintenance/', 'sec' => 0);
			return false;
		}

		//親の継承（意図しないかぎり消してはいけない）
		parent::beforeFilter();

	}

	static public function beforeSubFilter(){

	}

}
