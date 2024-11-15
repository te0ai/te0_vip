<?php
/**
 * フォルダ内共通処理
 *
 */
class commonSubC extends commonC{

	//共通処理
	static public function beforeFilter(){

		//親の継承（意図しないかぎり消してはいけない）
		parent::beforeFilter();

		$menu = array();
		self::set('menu', $menu);

	}

	static public function beforeSubFilter(){

	}

}
