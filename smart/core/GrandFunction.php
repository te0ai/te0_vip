<?php

//エラーハンドラ
set_error_handler(
  function ($errno, $errstr, $errfile, $errline) {
    // エラーが発生した場合、ErrorExceptionを発生させる
    throw new ErrorException(
      $errstr, 0, $errno, $errfile, $errline
    );
  }
);

function a($str,$bool=false){

	//boolにboolean以外が指定された場合は展開後のvalueを返す
	if(!is_bool($bool)){
		$bkey = $bool;
		$bool = false;
	}

	if($bool===false){

		if(!is_string($str)) return false;

		//分解（文字列からアレイに）
		$str = str_replace("\\\\", "\x0c", $str); // \\ を書式送りに置換しておく
		$str = str_replace("\\,", "\x0b", $str); // \, を垂直タブに置換しておく

		$arr = array(); $arr2 = array();
		$arr = explode(",", $str);

		$i = 0;
		foreach($arr as $value){

			$value = str_replace("\x0b", ",", $value); // 垂直タブを , に戻す
			$value = str_replace("\\:", "\x0b", $value); // \: を垂直タブに置換しておく

			if (strpos($str,":") === false){

				$value = str_replace("\x0b", ":", $value); // 垂直タブを : に戻す
				$value = str_replace("\x0c", "\\", $value); // 書式送りを \ に戻す

				$arr2[$i] = $value;
				++$i;

			}else{

				list($key, $value) = explode(":", $value);

				$key = str_replace("\x0b", ":", $key); // 垂直タブを : に戻す
				$key = str_replace("\x0c", "\\", $key); // 書式送りを \ に戻す

				$value = str_replace("\x0b", ":", $value); // 垂直タブを : に戻す
				$value = str_replace("\x0c", "\\", $value); // 書式送りを \ に戻す

				$arr2[$key] = $value;

			}

		}

		if( isset($bkey) && isset($arr2[$bkey])){
			return $arr2[$bkey];
		}else if(isset($bkey) && !isset($arr2[$bkey])){
			return '';
		}else{
			return $arr2;
		}


	}else{

		if(is_array($str)){

			//アレイを結合（アレイから文字列に）
			$str2 = '';
			foreach($str as $k => $v){
				$str2 .= str_replace(",","\,",$k).':'.str_replace(",","\,",$v).',';
			}
			$str2 = substr($str2,0,-1);

		}else{

			//アレイでない場合は単純エスケープ処理
			$str2 = str_replace(",","\,",$str);

		}



		return $str2;

	}


}


function h($str, $bool = false, $array = array())
{

	//boolが省略されて装飾指示
	if (is_array($bool)) {
		$array = $bool;
		$bool = false;
	}

	if (isset($array['length']) && is_numeric($array['length'])) {
		$str = mb_strimwidth($str, 0, $array['length'], '...', 'UTF-8');
	}
	if (in_array('decode_ipn', $array, true)) {
		if (strpos($str, '+81') === 0) {
			$str = '0' . substr($str, 3, strlen($str) - 3);
		}
	}
	//これは最後に
	if (isset($array['allowed_tags']) && is_array($array['allowed_tags'])) {
		//許可するタグを指定する
		$tags = implode('|', $array['allowed_tags']);
		$pattern = "/<(?!(\/?\s*($tags)\b)[^>]*>)[^>]+>/i";
		$str = preg_replace_callback($pattern, function ($matches) {
			return htmlspecialchars($matches[0], ENT_QUOTES);
		}, $str);
		if ($bool) {
			return (string)$str;
		} else {
			echo (string)$str;
		}
	} else {
		$ret = htmlspecialchars((string)$str);
		if (in_array('nl2br', $array, true)) {
			$ret = nl2br($ret);
		}
		if ($bool) {
			return $ret;
		} else {
			echo $ret;
		}
	}
}

function j($str,$lv=NULL){
	if(is_json($str)){
		$arr = array();
		$arr = json_decode($str,true);
		if($lv===NULL){
			return $arr;
		}else{
			return $arr[$lv];
		}
	}else{
		return false;
	}
}

function r($obj,$mode=""){
	if($mode==="j"){
		Json::output(array(
			'operation' => 'message',
			'title' => 'デバッグ',
			'message' => print_r($obj,true),
		));
	}else{
		echo '<xmp>';
		print_r($obj);
		echo '</xmp>';
		echo '<hr>';
	}
}

function i($int=0){

	if($int===0){
		echo _OB_PATH_.'/';
	}else{
		return _OB_PATH_.'/';
	}

}

function t($str,$bool=false){
	if(strpos($str,'_')!==false){
		$tmp = array();
		$tmp = explode('_',$str);
		$parent = array();
		if(!$parent = Helper::parentCall($tmp[0])){return false;}
		if($bool){
			echo htmlspecialchars($parent[$tmp[1]]['title']);
		}else{
			return $parent[$tmp[1]]['title'];
		}
	}else{
		$group = array();
		if(!$group = Helper::groupCall($str)){return false;}
		if($bool){
			echo htmlspecialchars($group['title']);
		}else{
			return $group['title'];
		}
	}
}

function random($len=32){
  return substr(bin2hex(random_bytes($len)),0,$len);
}

function generate_password($length=32,$use='ans'){

  $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $numbers = '0123456789';
  $symbols = '!@#$%^&*()_+-=[]{};:,.?';

  $use_letters = strpos($use,'a')!==false ? true : false;
  $use_numbers = strpos($use,'n')!==false ? true : false;
  $use_symbols = strpos($use,'s')!==false ? true : false;

  // 使う文字のリストを生成
  $chars = '';
  if ($use_letters) {
    $chars .= $alphabet;
  }
  if ($use_numbers) {
    $chars .= $numbers;
  }
  if ($use_symbols) {
    $chars .= $symbols;
  }

  // 各種文字の数
  $num_letters = 0;
  $num_numbers = 0;
  $num_symbols = 0;

  // 数字を含むランダムな文字列を生成
  if ($use_numbers) {
    $num_numbers = 8;
  }

  // 英語の文字を含むランダムな文字列を生成
  if ($use_letters) {
    $num_letters = 8;
  }

  // 記号を含むランダムな文字列を生成
  if ($use_symbols) {
    $num_symbols = 8;
  }

  $password = '';

  // 残りのランダムな文字列を生成し、それをパスワードに追加
  $total_chars = $length - ($num_letters + $num_numbers + $num_symbols);
  for ($i = 0; $i < $total_chars; $i++) {
    $password .= $chars[random_int(0, strlen($chars) - 1)];
  }

  // 英語の文字を含むランダムな文字列を生成
  if ($use_letters) {
    for ($i = 0; $i < $num_letters; $i++) {
      $password .= $alphabet[random_int(0, strlen($alphabet) - 1)];
    }
  }

  // 数字を含むランダムな文字列を生成
  if ($use_numbers) {
    for ($i = 0; $i < $num_numbers; $i++) {
      $password .= strval(mt_rand(0, 9));
    }
  }

  // 記号を含むランダムな文字列を生成
  if ($use_symbols) {
    for ($i = 0; $i < $num_symbols; $i++) {
      $password .= $symbols[random_int(0, strlen($symbols) - 1)];
    }
  }

  // ランダムな順序でパスワードをシャッフル
  $password_array = str_split($password);
  shuffle($password_array);
  $password = implode('', $password_array);

  return $password;
}

function tax($num,$precision=0){
  $value = 0;
  if(is_numeric($num)){
    //切り捨て
    $value = $num + ($num * ( _TAX_ * 0.01 ));
    if(_TAX_ROUND_===3){
      if($precision<=0){
        return (float)floor($value);
      }else{
        $reg = $value - 0.5 / (10 ** $precision);
        return round($reg, $precision, $reg > 0 ? PHP_ROUND_HALF_UP : PHP_ROUND_HALF_DOWN);
      }
    }else if(_TAX_ROUND_===2){
      //切り上げ
      if($precision<=0){
        return (float)ceil($value);
      }else{
        $reg = $value + 0.5 / (10 ** $precision);
        return round($reg, $precision, $reg > 0 ? PHP_ROUND_HALF_DOWN : PHP_ROUND_HALF_UP);
      }
    }else{
      //四捨五入
      return round($value,$precision);
    }
  }else{
    return $value;
  }
}

function decimalpoint($num,$show_zerozero=false,$precision=2){
  $value = $num;
  if(is_numeric($num)){
    if ($show_zerozero || (strpos($value,'.') !== false)) {
      // 小数点以下の.00を表示
      $value = number_format($value,$precision);
    } else {
      $value = number_format($value);
    }
  }
  return $value;
}

function icon($dtb_group_str,$bool=false){
	$cache = Session::cache('group/'.$dtb_group_str);
	if(!$bool){
		echo '<span class="'.$cache['icon'].'" aria-hidden="true">&nbsp;</span>';
	}else{
		return '<span class="'.$cache['icon'].'" aria-hidden="true">&nbsp;</span>';
	}
}

function api($operation,$body,$option=array()){

	//オペレーション
	$arr = explode('.',preg_replace('/^[^0-9a-z\.\_]$/u','',$operation));
	if(isset($arr[1])){
		$dir = $arr[0];
		$op  = $arr[1];
	}else{
		//省略された場合dirはbasic
		$dir = 'basic';
		$op  = $arr[0];
	}

	//オプション
	$v = isset($option['v']) ? preg_replace('/^[^0-9]$/u','',$option['v']) : '0.01';//バージョン
	if(isset($option['m'])){
		//母艦（全サーバ統括）API
		$url = _API_MHOME_;
		$token = _API_MTOKEN_;
	}else{
		$url = _API_HOME_;
		$token = isset($option['token']) ? $option['token'] : Session::read('auth.token');
	}

	//ヘッダ
	$header = array(
    'Authorization: '.$token,
    'Content-Type: application/json',
  );

	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL,$url.$dir.'/v'.$v.'/'.$op.'/');
	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
	curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($body));//jsonデータを送信
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);//証明書の検証を行わない
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);//curl_execの結果を文字列で返す
	curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
	$response = curl_exec($curl);
	if(is_json($response)){
		$result = json_decode($response, true);
	}else{
		if(_DEBUG_){
			echo 'URL:'.$url.'<br />';
			echo '[getinfo]<br />';
			r(curl_getinfo($curl));
			echo '[errno]<br />';
			r(curl_errno($curl));
			echo '[error]<br />';
			r(curl_error($curl));
			var_dump($response);
		}
		$result = $response;
	}
	curl_close($curl);

	return $result;

}

//分を時間表記に変換
function min2str($min,$bool=false){
	if(!$bool){
		echo sprintf("%02d時間 %02d分", floor($min/60), $min%60);
	}else{
		return sprintf("%02d時間 %02d分", floor($min/60), $min%60);
	}
}

//X次元先のキー名称を検索して値を返します
function array_key_search($needle,$arr){
	while($i<100){
		if(isset($arr[$needle])){
			return $arr[$needle];
		}else if(is_array($arr)){
			$arr = get_first_element($arr);
		}else{
			return NULL;
		}
		++$i;
	}
	return false;
}

//2次元目の配列の値でソート
function sortArrayByKey( &$array, $sortKey, $sortType = SORT_ASC ) {

	if(!is_array($array)) return false;

  $sort = array();$stash =array();
  foreach ( $array as $key => $value ) {
		if(isset($value[$sortKey])){
			//ある場合はソートキーとして記録
			$sort[$key] = $value[$sortKey];
		}else{
			//ない場合は関係ないので一時退避
			$stash[$key] = $value;
			unset($array[$key]);
		}
  }
  array_multisort( $sort, $sortType, $array );
	$array = array_merge($array,$stash);
  unset($sort);
	unset($stash);

}

//配列の一番最初の値を取得
function get_first_element($arr) {
  // 引数として受け取った時点で常に内部ポインタは初期化されている！
  return current((array)$arr);
}

//曜日を取得
function get_week($str,$lang='jp'){
	if(!strptime($str,'%Y-%m-%d')) return false;
	$datetime = new DateTime($str);
	if($lang==='jp'){
		$week = array("日", "月", "火", "水", "木", "金", "土");
	}else{
		$week = array("Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat");
	}
	$w = (int)$datetime->format('w');
	return $week[$w];
}

function mcal($str,$search_type=2){

	//空だったら返す
	if($str==="") return "";

	//全文検索にハイフンは使えない
	$str = str_replace('-','',$str);

	if((string)$search_type==='2'){

		//2:あいまい(Ngram)
		$str_list = array();
    $str_len = mb_strlen($str,'UTF-8');

    for ($i = 0; $i < $str_len; ++$i) {
        $str_list[] = mb_substr($str,$i,2,'UTF-8');
    }

		return implode($str_list, ",");

	}else{

		//3:高度なあいまい(Mecab)
		$mcal = "";

		$mecab = new MeCab_Tagger();
		$nodes = $mecab->parseToNode($str);

		foreach ($nodes as $n) {
		  $tmp = $n->getSurface();
		  if(isset($tmp) && !empty($tmp)){
		    $mcal .= $tmp .',';
		  }
		}

		if($mcal === "") return "";

		return substr($mcal,0,-1);

	}

}


function curl_get_contents($url,$arr=array()){

	$timeout = isset($arr['timeout']) && is_numeric($arr['timeout']) ? $arr['timeout'] : 120;
	$detail = isset($arr['detail']) && is_bool($arr['detail']) ? $arr['detail'] : false;
	$header = isset($arr['header']) && is_array($arr['header']) ? $arr['header'] : array();

	$ch = curl_init ();
	curl_setopt( $ch, CURLOPT_URL,$url);
	curl_setopt( $ch, CURLOPT_TIMEOUT,$timeout);
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER,true);
	curl_setopt( $ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:9.0.1) Gecko/20100101 Firefox/9.0.1");
	curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
	//ポスト
	if(isset($arr['post'])){
		curl_setopt($ch,CURLOPT_POST, TRUE);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$arr['post']);
	}
	//取得方法
	if($detail){
		//詳細取得
		curl_setopt($ch, CURLOPT_HEADER,true);
		$result = array();
		$r = curl_exec ($ch);
		$info = curl_getinfo ($ch);
		$result = $info;
		$result['header'] = substr ($r,0,$info["header_size"]);
		$result['body'] = substr($r,$info["header_size"]);
	}else{
		//単純取得
		curl_setopt( $ch, CURLOPT_HEADER,false);
		$result = '';
		$result = curl_exec( $ch );
		curl_close( $ch );
	}
	return $result;
}

//改良版fget_csv
function fgetcsv2( &$fh, $delimiter = "," ) {

	if ( $fh === false || feof( $fh ) ) return false ;

	$csv = '' ;

	while ( ! feof( $fh ) ) {
		$csv .= mb_convert_encoding( fgets( $fh ), 'UTF-8', 'SJIS-win' ) ;
		if ( ( ( preg_match_all( '/"/', $csv, $matches ) ) % 2 ) == 0 ) break ;
	}

	if ( ( $csv == '' ) and ( feof( $fh ) ) ) return false ;  // <-- ここを追加

	$values = array() ;

	$temp = preg_replace( "/(?:\r\n|[\r\n])?$/", $delimiter, $csv, 1 ) ;

	preg_match_all( '/("[^"]*(?:""[^"]*)*"|[^'.$delimiter.']*)'.$delimiter.'/', $temp, $matches ) ;

	for ( $i = 0 ; $i < count( $matches[ 1 ] ) ; $i++ ) {
		if ( preg_match( '/^"(.*)"$/s', $matches[ 1 ][ $i ], $m ) ) {
			$matches[ 1 ][ $i ] = preg_replace( '/""/', '"', $m[ 1 ] ) ;
		}

		$values[] = $matches[ 1 ][ $i ] ;
	}

	return $values ;
}

//CSV
function csv($str){
	$str = str_replace('"','""',$str);
	return $str;
}

//CSV
function tel($str,$option='jpnicwithhyphen'){

  //option JPNIC or E.164

  $option = preg_replace('/[^0-9a-z]/su','',mb_strtolower($option));
  $str = trim($str);

  if(strpos($option,'jpnic')!==false){
    //日本形式 03-3780-5628
    if(strpos($str,'+81')===0){
      $str = substr($str,3,strlen($str)-3);
    }
    if(strpos($str,'0')!==0){
      $str = '0'.$str;
    }
  }else if(strpos($option,'e164')!==false){
    //国際形式 +813-3780-5628
    if(strpos($str,'0')===0){
      $str = substr($str,1,strlen($str)-1);
    }
    if(strpos($str,'+81')!==0){
      $str = '+81'.$str;
    }
  }

  if(strpos($option,'withhyphen')!==false){
    //ハイフンあり
    if(strpos($str,'-')===false){
		// 0800番号の場合の特別な処理を追加
		if (substr($str, 0, 4) === '0800') {
			$str = substr($str, 0, 4) . '-' . substr($str, 4, 3) . '-' . substr($str, 7);
		} else {
			// 通常の電話番号の処理
			$str_arr = array_reverse(str_split($str));
			$str = '';
			foreach ($str_arr as $i => $v) {
				$str = $v . $str;
				if ($i === 3 || $i === 7) {
					$str = '-' . $str;
				}
			}
		}
    }
  }else if(strpos($option,'withouthyphen')!==false){
    //ハイフン抜き
    $str = str_replace('-','',$str);
  }
	return $str;
}

//与えられた文字列を指定された長さで分割し、その結果を配列として返します。(PHP 7 >= 7.4.0, PHP 8)
if (!function_exists('mb_str_split')){
	function mb_str_split($string,$length,$encoding = null)
	{
		$array = array();
		$strLength = mb_strlen($string);
		for ($i = 0; $i < $strLength; $i += $length) {
			$array[] = mb_substr($string, $i, $length);
		}
		return $array;
	}
}


//改良版empty
function empty2($str){
	if(empty($str)) return true;
	if($str==='0000-00-00') return true;
	if($str==='0000-00-00 00:00:00') return true;
	return false;
}

//explode して インプロード
function eximplode($str=NULL,$delimiter=',',$glue=',',$front='',$back=''){
	if($str===''||$str===NULL) return '';
	$arr = array();$arr2 = array();
	$arr = explode($delimiter,$str);
	foreach($arr as $v){
		$arr2[] = $front.$v.$back;
	}
	return implode($glue,$arr2);
}

//複数版implode
function implode2($arr){
	$str = '';
	foreach((array)$arr as $v){
		foreach((array)$v as $v2){
			$str .= $v2.',';
		}
		$str = substr($str,0,-1);
		$str .= '|';
	}
	$str = substr($str,0,-1);
	return $str;
}

//ログ
function lg($desc,$arr=array()){

	/*---------------------------------
	//エラーを記録(例)
	$error = array();
	$error['str'] = 'coefont';
	$error['key'] = 'curlerror';
	$error['title'] = 'coefontでエラー';
	$error['desc']  = "[text]\n";
	$error['desc'] .= $_POST['talk'] . "\n";
	$error['desc'] .= "[coefont]\n";
	$error['desc'] .= $text2talk[$_POST['text2talk']]['coeid'] . "\n";
	$error['desc'] .= "[responce body]\n";
	$error['desc'] .= "curl error\n";
	$error['env'] = curl_getinfo($ch);
	lg($error['desc'], $error);
	---------------------------------- */

	$desc = is_string($desc) ? $desc : print_r($desc,true);
	$type = isset($arr['type']) ? $arr['type'] : 'info';
	$str = isset($arr['str']) ? $arr['str'] : '';
	$key = isset($arr['key']) ? $arr['key'] : '';
  	$title = isset($arr['title']) ? $arr['title'] : '';
  	$uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
	if(isset($arr['uri'])) $uri = $arr['uri'];


	$env = array();
	if(isset($arr['env'])) $env[] = $arr['env'];
	$env[] = $_SERVER;
	if(isset($_SESSION)) $env[] = $_SESSION;
	$env[] = debug_backtrace();

	$rcd = My::insert(array(
    'HOST' => _DB_LOG_HOST_,
    'DB' => _DB_LOG_DBN_,
    'USER' => _DB_RW_USER_,
    'PASSWORD' => _DB_RW_PASSWORD_,
		'TABLE' => 'tmp_log',
		'SET' => array(
			'type' => $type,
			'str' => $str,
			'key' => $key,
			'uri' => $uri,
      		'title' => $title,
			'desc' => $desc,
			'env' => json_encode($env),
		),
	));

  if($type==='fatal'){
    $rcd = Mail::send(array(
      'to' => _MAIL_SYS_ADDR_,
      'title' => "【"._TITLE_."】システムエラー情報",
      'set' => array(
        'type' => $type,
        'str' => $str,
        'key' => $key,
        'uri' => $uri,
        'desc' => $desc,
        'env' => print_r($env,true),
      ),
      'template' => 'sys/error',
    ));
  }


}

//DIMMか否か
function is_dimm($str,$str2=NULL){

	if($str2===NULL){
		$arr = array();
		$arr = explode('_',$str);
		$str  = $arr[0];
		$str2 = $arr[1];
	}

	if(isset($_SESSION['cache']['is_dimm'][$str][$str2])){
		if((string)$_SESSION['cache']['is_dimm'][$str][$str2]==="1"){
			return true;
		}else{
			return false;
		}
	}else{
		$parent = array();
		$parent = Session::cache('parent/'.$str);

		if((string)$parent[$str2]['dimm_num']==="1"){
			$_SESSION['cache']['is_dimm'][$str][$str2] = "1";
			return true;
		}else{
			$_SESSION['cache']['is_dimm'][$str][$str2] = "0";
			return false;
		}
	}

}

//jsonか否か
function is_json($string){
   return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
}

function inner_post($uri,$post){

	//ヘッダー製作
	$header = array();
	$header[] = 'Cookie: ' . $_SERVER['HTTP_COOKIE'];
	$header[] = 'Content-type: multipart/form-data';
	$header[] = 'Content-Length: '.strlen(implode('',$post));
	if(_BASIC_AUTH_){
		$header[] = 'Authorization: Basic '.base64_encode(_BASIC_AUTH_USER_.":"._BASIC_AUTH_PW_);
	}

	//オプション指定
	$opts = array(
		'http' => array(
			'header'=> implode("\r\n",$header),
			'content'=> $post,
			'timeout'=> "30",
			'ignore_errors' => true,
		)
	);

	$context = stream_context_create($opts);
	$http_response_header = NULL;
	session_write_close();
	$response = file_get_contents(_HOME_.$uri, false,$context);
	@session_start();

	if($response!==false){
		return $response;
	}else{
		if($http_response_header===NULL){
			return array(
				'info' => '',
				'errno' => 0,
				'error' => '通信に失敗して接続できませんでした',
			);
		}else{
			preg_match('/HTTP\/1\.[0|1|x] ([0-9]{3})/', $http_response_header[0], $matches);
			$status_code = $matches[1];
			return array(
				'info' => $http_response_header,
				'errno' => $status_code,
				'error' => '接続できませんでした',
			);
		}
	}

}

function inner_json($page,$arr){



	//ヘッダー製作
	$header = array();
	$header[] = 'Cookie: ' . $_SERVER['HTTP_COOKIE'];
	$header[] = 'Content-type: application/json';
	$header[] = 'Content-Length: '.strlen(json_encode($arr));
	if(_BASIC_AUTH_){
		$header[] = 'Authorization: Basic '.base64_encode(_BASIC_AUTH_USER_.":"._BASIC_AUTH_PW_);
	}


	//オプション指定
	$opts = array(
		'http' => array(
			'header'=> implode("\r\n",$header),
			'content'=> json_encode($arr),
			'timeout'=> "86400",
			'ignore_errors' => true,
		)
	);

	$context = stream_context_create($opts);
	$http_response_header = NULL;
	session_write_close();
	$response = file_get_contents(_HOME_.$page, false,$context);
	session_start();


	if($response!==false){
		$ret = json_decode($response,true);
		if($ret===NULL){
			$http_response_header[] = $response;
			return $http_response_header;
		}else{
			return $ret;
		}
	}else{
		if($http_response_header===NULL){
			return array(
				'info' => '',
				'errno' => 0,
				'error' => '通信に失敗して接続できませんでした',
			);
		}else{
			preg_match('/HTTP\/1\.[0|1|x] ([0-9]{3})/', $http_response_header[0], $matches);
			$status_code = $matches[1];
			return array(
				'info' => $http_response_header,
				'errno' => $status_code,
				'error' => '接続できませんでした',
			);
		}
	}

}

//動作時間記録
//$_SESSION['debug_time_desc'] = array();
function debug_time(){

    $debug = current(debug_backtrace());

    static $start_time = 0;
    static $pre_debug = null;
    static $pre_time = 0;

    $time = microtime(true);
    if(!$start_time) $start_time = $time;

    if($pre_time){
        $_SESSION['debug_time_desc'][] = sprintf('[%s(%d) - %s(%d)]: %d ms(ttl:%d ms)',
            $pre_debug['file'], $pre_debug['line'],
            $debug['file'], $debug['line'],
            ($time * 1000 - $pre_time * 1000),
            ($time * 1000 - $start_time * 1000)
        );
    }

    $pre_debug = $debug;
    $pre_time = $time;
}


?>
