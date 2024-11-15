<?php

class Elem{

	static public $viewVars = array();

	//エレメントファイルインポート
	static public function import($uri,$_=array(),$bool=false){

		$uri = str_replace('.','',$uri);

		//viewVarsが優先される
		self::$viewVars = self::$viewVars + $_;

		//変数を展開'EXTR_SKIP'により既にある変数を上書きしない
		extract(self::$viewVars,EXTR_SKIP);

		//ロジックを分けている場合は先に展開
		if(is_file(_ROOT_._SRC_.'Elogic/'.$uri.'.php')){
			require(_ROOT_._SRC_.'Elogic/'.$uri.'.php');
		}else if(is_file(_ROOT_._ASRC_.'Elogic/'.$uri.'.php')){
			require(_ROOT_._ASRC_.'Elogic/'.$uri.'.php');
		}

		//エレメントテンプレートの展開
		if($bool){
			$str = '';
			ob_start();
		}
		if(Session::is_fatal()){
			$_['message'] = Session::read('Message.error');
			require(_ROOT_._SRC_.'Element/info/error.ctp');
		}else if(is_file(_ROOT_._SRC_.'Element/'.$uri.'.ctp')){
			require(_ROOT_._SRC_.'Element/'.$uri.'.ctp');
		}else if(is_file(_ROOT_._ASRC_.'Element/'.$uri.'.ctp')){
			require(_ROOT_._ASRC_.'Element/'.$uri.'.ctp');
		}
		if($bool){
			$str = ob_get_contents();
			ob_end_clean();
			return $str;
		}
	}

	//エレメントからのSET要求（コントローラーと同一）
	static public function set($one, $two = null) {
		$data = null;
		if (is_array($one)) {
			if (is_array($two)) {
				$data = array_combine($one, $two);
			} else {
				$data = $one;
			}
		} else {
			$data = array($one => $two);
		}
		if (!$data) {
			return false;
		}
		self::$viewVars = $data + self::$viewVars;
	}

}


?>
