<?php

class Define{

	public static function get($str){
		//ディレクトリトラバーサル
		$str = str_replace('.','',$str);
		$define = array();
		if(is_file(_ROOT_.'src/config/'.$str.'.php')){
			require(_ROOT_.'src/config/'.$str.'.php');
			return $define;
		}else{
			return false;
		}
	}

	public static function set($str){
		//バックトレース
		// $dbg = debug_backtrace();
		// if (!defined('_BACKTRACE_')) define('_BACKTRACE_',print_r($dbg,true));
		//ディレクトリトラバーサル
		$define = array();
		$define = self::get($str);
		if(is_array($define)){
			foreach($define as $k => $v){
				if(!defined($k)) define($k,$v);
			}
			return true;
		}else{
			return false;
		}
	}



}

?>
