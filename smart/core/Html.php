<?php

class Html{

	//メタ情報
	static public function meta($str){

		switch ($str) {
			case 'icon':
				echo '<link href="'._HOME_.'favicon.ico" type="image/x-icon" rel="icon" />',PHP_EOL;
				echo '<link href="'._HOME_.'favicon.ico" type="image/x-icon" rel="shortcut icon" />',PHP_EOL;
				echo '<link href="'._HOME_.'favicon.ico" rel="icon" type="image/vnd.microsoft.icon" />',PHP_EOL;
				echo '<link href="'._HOME_.'apple-touch-icon-precomposed.png" rel="apple-touch-icon" />',PHP_EOL;
				break;
		}


	}

	//CSS操作
	static public function css($url=''){
		if($url===''){
			//デフォルト
			if(isset($_REQUEST['URI'][0])) $url .= $_REQUEST['URI'][0].'/';
			if(isset($_REQUEST['URI'][1])) $url .= $_REQUEST['URI'][1].'/';
			if(isset($_REQUEST['URI'][2])) $url .= $_REQUEST['URI'][2].'/';
			echo '<link rel="stylesheet" type="text/css" href="'._HOME_.'css.css?page='.$url.'&v='._V_.'" />',PHP_EOL;
		}else if(strpos($url, '//') === false){
			//ローカル
			echo '<link rel="stylesheet" type="text/css" href="'._HOME_.'css/'.$url.'.css?v='._V_.'" />',PHP_EOL;
		}else{
			//WEB
			echo '<link rel="stylesheet" type="text/css" href="'.$url.'" />',PHP_EOL;
		}

	}

	//追加CSS
	static public $addCss = array();
	static public function addCss(){

		if(!empty(self::$addCss)){
			foreach(self::$addCss as $url){
				if (strpos($url, '//') === false){

					//ローカル
					echo '<link rel="stylesheet" type="text/css" href="'._HOME_.'css/'.$url.'.css?v='._V_.'" />',PHP_EOL;

				}else{

					//WEB
					echo '<link rel="stylesheet" type="text/css" href="'.$url.'" />',PHP_EOL;

				}
			}
		}

	}

	//JS操作
	static public function script($url=''){
		if($url===''){
			//デフォルト
			if(isset($_REQUEST['URI'][0])) $url .= $_REQUEST['URI'][0].'/';
			if(isset($_REQUEST['URI'][1])) $url .= $_REQUEST['URI'][1].'/';
			if(isset($_REQUEST['URI'][2])) $url .= $_REQUEST['URI'][2].'/';
			echo '<script type="text/javascript" src="'._HOME_.'js.js?page='.$url.'&v='._V_.'"></script>',PHP_EOL;
		}else if (strpos($url, '//') === false){
			//ローカル
			echo '<script type="text/javascript" src="'._HOME_.'js/'.$url.'.js?v='._V_.'"></script>',PHP_EOL;
		}else{
			//WEB
			echo '<script type="text/javascript" src="'.$url.'"></script>',PHP_EOL;
		}
	}

	//追加JS
	static public $addScript = array();
	static public function addScript(){
		if(!empty(self::$addScript)){
			self::$addScript = array_unique(self::$addScript);
			foreach(self::$addScript as $url){
				if (strpos($url, '//') === false){
					//ローカル
					if(is_file(_ROOT_.'webroot/js/'.$url.'.js')){
						echo '<script type="text/javascript" src="'._HOME_.'js/'.$url.'.js?v='._V_.'"></script>',PHP_EOL;
					}
				}else{

					//WEB
					echo '<script type="text/javascript" src="'.$url.'.js"></script>',PHP_EOL;

				}
			}
		}
	}

	//フェッチ
	static public $fetch = array();
	static public function fetch($str,$bool=false){
		if(isset(self::$fetch[$str])){
			if(!$bool){
				echo self::$fetch[$str];
			}else{
				return self::$fetch[$str];
			}

		}
	}

	//タイトル
	static public $title = '';
	static public function title(){

		return self::$title;

	}

	//イメージ
	static public $img = '';
	static public function img(){
		return self::$img;
	}

	//パンくずナビ
	static public $addCrumb = array();
	static public function addCrumb(){

		$arr = array();
		if( $_REQUEST['URI'][0] === 'index' || $_REQUEST['URI'][1] === 'index'){

			//index踏んだらリセット
			$arr['crumb'] = array();
			Session::reset($arr);

		}

		//現在の情報を記録
		$arr['crumb'][$_REQUEST['URI'][0]][$_REQUEST['URI'][1]]['title'] = self::$title;
		$arr['crumb'][$_REQUEST['URI'][0]][$_REQUEST['URI'][1]]['url'] = $_REQUEST['URL'];
		Session::write($arr);

		//パンくずナビ構築
		$html = '';
		if($_REQUEST['URI'][0] === 'index') return $html;

		$html  = "<ul class=\"breadcrumb\">\n";
		$html .= "<li><a href=\""._HOME_."\">"._TITLE_."</a></li>\n";
		if($_REQUEST['URI'][1] !== 'index') {
			$arr = array();
			$arr = Session::read('crumb');
			foreach($arr[$_REQUEST['URI'][0]] as $k => $v){
				if($k !== $_REQUEST['URI'][1]){
					$html .= "<li><a href=\"".$v['url']."\">".$v['title']."</a></li>\n";
				}
			}
		}
		$html .= "<li class=\"active\">".self::$title."</li>\n";
		$html .= "</ul>\n";

		echo $html;


	}

	//コンフィグとJavascriptを連携
	static public $configScript = array();
	static public function configScript(){

		if(!empty(self::$configScript)){

			echo '<script>'.PHP_EOL;
			foreach(self::$configScript as $define){
				if(defined($define)){
					echo '   const '.$define.' = \''.constant($define).'\' ;'.PHP_EOL;
				}
			}
			echo '</script>'.PHP_EOL;
		}

	}

	//iamとJavascriptを連携
	static public function iamScript($arr=array()){
		echo '<script>'.PHP_EOL;
		echo '   const iam = {'.PHP_EOL;
		if(isset($_SESSION['auth']) && is_array($_SESSION['auth'])){
			foreach( $_SESSION['auth'] as $k => $v ){
				if(is_string($v)){
					if(in_array($k,$arr,true)){
						echo '   '.$k.' : \''.str_replace("'","\\'",htmlspecialchars($v)).'\' ,'.PHP_EOL;
					}
				}else if(is_array($v)){
					if(isset($arr[$k])){
						echo '   '.$k.' : { '.PHP_EOL;
						foreach( $v as $k2 => $v2 ){
							if(is_string($v2)){
								if(in_array($k2,$arr[$k],true)){
									echo '      '.$k2.' : \''.str_replace("'","\\'",htmlspecialchars($v2)).'\' ,'.PHP_EOL;
								}
							}else if(is_array($v2)){
								if(in_array($k2,$arr[$k],true)){
									echo '   '.$k2.' : { '.PHP_EOL;
										foreach( $v2 as $k3 => $v3 ){
											if(is_string($v3)){
												if(in_array($k3,$arr[$k][$k2],true)){
													echo '   '.$k3.' : \''.str_replace("'","\\'",htmlspecialchars($v3)).'\' ,'.PHP_EOL;
												}
											}
										}
									echo '      }, '.PHP_EOL;
								}
							}
						}
						echo '   }, '.PHP_EOL;
					}
				}
			}
		}
		echo '   };'.PHP_EOL;
		echo '</script>'.PHP_EOL;
	}

	//SETとJavascriptを連携
	static public $setScript = array();
	static public function setScript(){
		if(!empty(self::$setScript)){
			echo '  let data = {'.PHP_EOL;
			foreach(self::$setScript as $str => $value){
				if(is_string($str)){
					echo '      '.$str.': '.json_encode($value).','.PHP_EOL;
				}
			}
			echo '  }'.PHP_EOL;
		}

	}

	//アクティブ
	static public $active = '';
	static public function active($str,$num=NULL,$bool=false){
		if( $num === NULL && self::$active === $str ){
			if($bool){
				return 'active';
			}else{
				echo 'active';
			}
		}else if( is_numeric($num) && self::$active[$num] === $str){
			if($bool){
				return 'active';
			}else{
				echo 'active';
			}
		}
	}

	//リダイレクト
	static public $redirect = array();
	static public function redirect(){

		if( isset(self::$redirect['sec']) && is_numeric(self::$redirect['sec'])){

			if(strpos(self::$redirect['url'], 'http') === false){
				$url = _HOME_.self::$redirect['url'];
			}else{
				$url = self::$redirect['url'];
			}

			//自動ループを防ぐ
			$myurl = (empty($_SERVER["HTTPS"]) ? "http://" : "https://") . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
			if($url!==$myurl){
				//転送
				if((string)self::$redirect['sec']==='0'){
					header('Location: '.$url);
				}
				echo "<meta http-equiv=\"refresh\" content=\"".self::$redirect['sec']."; url=".$url."\">\n";
				echo "<script>\n";
				echo "\t<!--\n";
				echo "\t\tvar mnt = ".self::$redirect['sec'].";\n";
				echo "\t\tvar url = \"".$url."\";\n";
				echo "\t\tfunction jumpPage() {\n";
				echo "\t\t  location.href = url;\n";
				echo "\t\t}\n";
				echo "\t\tsetTimeout(\"jumpPage()\",mnt*1000);\n";
				echo "\t//-->\n";
				echo "</script>\n";
				if((string)self::$redirect['sec']==='0'){
					echo "</head>\n";
					echo "<body>\n";
					echo "</body>\n";
					echo "</html>\n";
					exit();
				}

			}

		}

	}

}


?>
