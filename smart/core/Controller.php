<?php

class coreC{

	//ビュー&フォームのファイルパス
	static public $coreFilePath = '';

	//共通クラスファイル
	//static public $coreUse = array('Html','Elem','Form','Conf','My','Aes','Session','Pdf','Mail','Ic','Json','Helper','Math','Mini');
	static public $coreUse = array('Html','Elem','Conf','Define','My','Aes','Session','Mail','Json','Pdf','Math');

	//ビューに渡す配列
	static public $viewVars = array();

	//vueに渡す配列
	static public $vueVars = array();

	//レンダーファイルパス
	static public $renderFilePath = NULL;

	static public function coreSet($ClassName=NULL){

		//初期化
		$_SESSION['Message'] = array();

		//共通クラスファイル
		if( isset(self::$coreUse) && is_array(self::$coreUse)){
			foreach(self::$coreUse as $v){
				$path = str_replace('.','',$v);
				if(file_exists(_ROOT_.'smart/core/'.$path.'.php')){
					include_once(_ROOT_.'smart/core/'.$path.'.php');
				}
			}
		}

		//モデルの読み込み
		if( $ClassName !== NULL && isset($ClassName::$use) && is_array($ClassName::$use)){
			include_once(_ROOT_.'smart/core/Model.php');
			include_once(_ROOT_._SRC_.'Model/common.php');
			foreach($ClassName::$use as $v){
				$path_arr = array();
				$path = str_replace('.','',$v);
				$path_arr = explode('/',$path);
				if($path_arr[0] !== 'index' && file_exists(_ROOT_._SRC_.'Model/'.$path_arr[0].'/common.php')) include_once(_ROOT_._SRC_.'Model/'.$path_arr[0].'/common.php');
				if(file_exists(_ROOT_._SRC_.'Model/'.$path.'.php')){
					include_once(_ROOT_._SRC_.'Model/'.$path.'.php');
				}
			}
		}
		//HTMLに値を代入********************

		//始まる前のフィルター
		if( $ClassName !== NULL && method_exists($ClassName,'beforeFilter')){
			$ClassName::beforeFilter();
		}


	}

	static public function coreView($ClassName){

		//各種適用********************

		//CSS
		if(isset($ClassName::$addCss)) Html::$addCss = $ClassName::$addCss;

		//JS
		if(isset($ClassName::$addScript)) Html::$addScript = $ClassName::$addScript;

		//Fetch
		if(isset($ClassName::$fetch)) Html::$fetch = $ClassName::$fetch;

		//Config
		if(isset($ClassName::$configScript)) Html::$configScript = $ClassName::$configScript;

		//タイトル
		if(isset($ClassName::$title)) Html::$title = $ClassName::$title;

		//イメージ
		if(isset($ClassName::$img)) Html::$img = $ClassName::$img;

		//アクティブ
		if(isset($ClassName::$active)) Html::$active = $ClassName::$active;

		//リダイレクト
		if(!empty($ClassName::$redirect)) Html::$redirect = $ClassName::$redirect;

		//共通クラスファイルにViewVarsを代入
		if( isset(self::$coreUse) && is_array(self::$coreUse)){
			foreach(self::$coreUse as $v){
				$path = str_replace('.','',$v);
				if(
					file_exists(_ROOT_.'smart/core/'.$path.'.php') &&
					isset($path::$viewVars)
				){
						$path::$viewVars = self::$viewVars;
				}
				if(
					file_exists(_ROOT_.'smart/core/'.$path.'.php') &&
					isset($path::$vueVars)
				){
						$path::$vueVars = self::$vueVars;
				}
			}
		}

		//ロールの処理****************
		if(!empty($ClassName::$role)){
			$role = Session::read('auth.role');
			$deny = true;
			//ローカル権限
			if(
				isset($role['myd'][$ClassName::$role]) &&
				is_array($role['myd'][$ClassName::$role]) && (
				in_array('v',$role['myd'][$ClassName::$role],true) ||
				in_array('p',$role['myd'][$ClassName::$role],true)
			)){
				$deny = false;
			}
			//グローバル権限
			if(
				isset($role['global']['global']) &&
				is_array($role['global']['global']) && (
				in_array('v',$role['global']['global'],true) ||
				in_array('p',$role['global']['global'],true)
			)){
				$deny = false;
			}
			if($deny!==false){
				Session::fatal('このページにアクセスする権限がありません。',403);
			}
		}


		//レイアウト読み込み**********

		//変数を展開'EXTR_SKIP'により既にある変数を上書きしない
		extract($ClassName::$viewVars,EXTR_SKIP);
		//vue対応、変数をJavaScriptへ
		Html::$setScript = $ClassName::$vueVars;

		if( isset($ClassName::$type) && $ClassName::$type==='none' ){

			//共通フレーム無し
			$ClassName::contents();

		}else if( isset($ClassName::$type) && !empty($ClassName::$type) && file_exists(_ROOT_._SRC_.'Template/Layout/'.$ClassName::$type.'.ctp') ){

			//指定のレイアウトファイル読み込み
			include_once(_ROOT_._SRC_.'Template/Layout/'.$ClassName::$type.'.ctp');

		}else{

			//JSON、フレーム無し、エラーでないアクセス＝（メインアクセス）
			//メイン以外の読み込みが走るとトークンが書き換わっちゃうんだよ
			if( !Session::is_error() ){

				//Form::$csrf = rtrim(base64_encode(openssl_random_pseudo_bytes(32)),'=');
				//Session::write(array('token'=>Form::$csrf));

			}


			//レイアウトファイル読み込み
			include_once(_ROOT_._SRC_.'Template/Layout/common.ctp');

		}


	}

	//「/Template/Layout/common.ctp」からの呼び出し
	static public function contents(){

		//エラーコード****************
		if(Session::is_fatal()){

			//変数を展開'EXTR_SKIP'により既にある変数を上書きしない
			$message = Session::read('Message.error');
			$errorCode = Session::read('Message.ecode');
			if(!is_numeric($errorCode)) exit('Fatal error: errorCode is not num.');

			//変数を展開'EXTR_SKIP'により既にある変数を上書きしない
			//extract(self::viewVars,EXTR_SKIP);

			include_once(_ROOT_._SRC_.'Template/Error/error'.$errorCode.'.ctp');
			return;

		}

		if( isset(self::$renderFilePath) && file_exists(_ROOT_._SRC_.'Cv/'.self::$renderFilePath.'.ctp') ){

			//CTP変更要求

			//変数を展開'EXTR_SKIP'により既にある変数を上書きしない
			extract(self::$viewVars,EXTR_SKIP);

			//ビューファイル読み込み
			include_once(_ROOT_._SRC_.'Cv/'.self::$renderFilePath.'.ctp');



		}else if(file_exists(_ROOT_._SRC_.'Cv/'.self::$coreFilePath.'.ctp')){

			//ページ通りのCTP

			//変数を展開'EXTR_SKIP'により既にある変数を上書きしない
			extract(self::$viewVars,EXTR_SKIP);

			//ビューファイル読み込み
			include_once(_ROOT_._SRC_.'Cv/'.self::$coreFilePath.'.ctp');



		}

	}

	//各ctpからの呼び出し
	static public function element($path) {

		$path = str_replace('.','',$path);
		if(file_exists(_ROOT_._SRC_.'Template/Element/'.$path.'.ctp')){

			//変数を展開'EXTR_SKIP'により既にある変数を上書きしない
			extract(self::viewVars,EXTR_SKIP);

			include_once(_ROOT_._SRC_.'Template/Element/'.$path.'.ctp');

		}
	}

	//コントローラーからのSET要求
	static public function set($one,$two = null,$loc="view") {
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
		if($loc==="view"||$loc==="all"){
			self::$viewVars = $data + self::$viewVars;
		}
		if($loc==="vue"||$loc==="all"){
			self::$vueVars = $data + self::$vueVars;
		}

	}

	//コントローラーからのビュー変更要求
	static public function render($path) {
		$path = str_replace('.','',$path);
		self::$renderFilePath = $path;
	}


}


?>
