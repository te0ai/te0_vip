<?php
/**
 * フォルダ内共通処理
 *
 */
class commonSubC extends commonC{

	//共通処理
	static public function beforeFilter(){

		$menu = array();
		self::set('menu', $menu);

		//親の継承（意図しないかぎり消してはいけない）
		parent::beforeFilter();

	}

	static public function beforeSubFilter(){

	}

}
