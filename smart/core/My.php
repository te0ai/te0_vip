<?php

class My {

	//基本機能------------------------
	static public $db = array();
	static public $debug = array();

	//リセット
	static public function reset(){
		self::$db = array();
		self::$debug = array();
	}

	//最初に起動される
	static public function first($arr){

		$subs = array();
		if(defined("_DB_SUBS_")){
			$subs = Conf::read(_DB_SUBS_);
		}
		//DBが格納されていない場合はサブテーブル確認、デフォルトを格納
		if(!isset($arr['DB'])){
			if(isset($arr['TABLE'])&&isset($subs[$arr['TABLE']])){
				$arr['DB'] = $subs[$arr['TABLE']]['DB'];
			}else if(defined('_DBN_')){
				$arr['DB'] = _DBN_;
			}else{
				$arr['DB'] = _DB_COMMON_RO_DBN_;
			}
		}
		//USERが格納されていない場合はサブテーブル確認、デフォルトを格納
		if(!isset($arr['USER'])){
			if(isset($arr['TABLE'])&&isset($subs[$arr['TABLE']])){
				$arr['USER'] = $subs[$arr['TABLE']]['USER'];
			}else if(defined('_DB_USER_')){
				$arr['USER'] = _DB_USER_;
			}else{
				$arr['USER'] = _DB_RO_USER_;
			}
		}
		//DBインスタンスが存在しない場合は起動
		if(!isset(self::$db[$arr['DB'].'-'.$arr['USER']])){

			//HOSTが格納されていない場合はサブテーブル確認、デフォルトを格納
			if(!isset($arr['HOST'])){
				if(isset($arr['TABLE'])&&isset($subs[$arr['TABLE']])){
					$arr['HOST'] = $subs[$arr['TABLE']]['HOST'];
				}else if(defined('_DB_HOST_')){
					$arr['HOST'] = _DB_HOST_;
				}else{
					$arr['HOST'] = _DB_COMMON_RO_HOST_;
				}
			}
			//PASSWORDが格納されていない場合はサブテーブル確認、デフォルトを格納
			if(!isset($arr['PASSWORD'])){
				if(isset($arr['TABLE'])&&isset($subs[$arr['TABLE']])){
					$arr['PASSWORD'] = $subs[$arr['TABLE']]['PASSWORD'];
				}else if(defined('_DB_PASSWORD_')){
					$arr['PASSWORD'] = _DB_PASSWORD_;
				}else{
					$arr['PASSWORD'] = _DB_RO_PASSWORD_;
				}
			}
			self::start($arr);

		}

		return $arr;

	}

	//スタート
	static public function start($arr){

		//接続
		self::$db[$arr['DB'].'-'.$arr['USER']] = NULL;
		try{
		  self::$db[$arr['DB'].'-'.$arr['USER']] = new mysqli($arr['HOST'],$arr['USER'],$arr['PASSWORD'],$arr['DB']);
		}catch(Exception $e){
		  echo $e->getMessage();
			if(_DEBUG_){
				echo "<hr>";
				echo "HOST:".$arr['HOST']."<br>";
				echo "DB:".$arr['DB']."<br>";
				echo "USER:".$arr['USER']."<br>";
				//echo "PASSWORD:".$arr['PASSWORD']."<br>";
			}
			exit();
		}


		//接続の状態を確認
		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}

		//文字セットをUTF-8に固定します（セキュリティ対策）
		if (!self::$db[$arr['DB'].'-'.$arr['USER']]->set_charset("utf8")) {
			printf("Error loading character set utf8: %s\n", self::$db[$arr['DB'].'-'.$arr['USER']]->error);
			exit();
		}

	}

	//呼び出し
	static public function show($query,$arr=array(),$prx=""){

		//初期設定
		$arr = self::first($arr);
		$db = $arr['DB'];

		//結果
		$return_arr = array();

		//時間計測
		$start_time = microtime(true);

		/*
		if( !empty($arr) && is_array($arr) ){
			foreach( $arr as $k => $v ){
				if(self::is_num($v)){
					//数字
					$query = str_replace('{$'.$k.'}',$v,$query);
				}else{
					//それ以外
					$v = self::$db[$arr['DB'].'-'.$arr['USER']]->real_escape_string($v);
					$v = str_replace('%','\%',$v);
					$v = str_replace('_','\_',$v);
					$query = str_replace('{$'.$k.'}','\''.$v.'\'',$query);
				}
			}
		}
		*/

		try {
			$result = self::$db[$arr['DB'] . '-' . $arr['USER']]->query($query);
			if (is_object($result)) {
				$return_arr['count'] = (int)$result->num_rows;

				while ($row = $result->fetch_assoc()) {
					$return_arr['data'][] = $row;
				}
				$result->free();

				$return_arr['status'] = true;
				$return_arr['query'] = $query;
			} else {
				$return_arr['status'] = false;
				$return_arr['count'] = 0;
				$return_arr['query'] = $query;
			}
		} catch (Exception $e) {
			// クエリエラー発生時の処理
			$return_arr['status'] = false;
			$return_arr['count'] = 0;
			$return_arr['query'] = $query;
			$return_arr['error'] = $e->getMessage(); // エラーメッセージを格納
		}

		$run_time = microtime(true) - $start_time;

		if(
			defined('_DB_DEBUG_') &&
			_DB_DEBUG_ === true
		){

			//セッションに出力
			$tmp_arr = array();
			$tmp_arr['query'] = $query;
			$tmp_arr['time'] = $run_time;
			if(method_exists('Session','read')) $tmp_arr['gid'] = Session::read('Auth.group.id');

			$dbg = array();
			$dbg = debug_backtrace();

			self::$db[$arr['DB'].'-'.$arr['USER']] = NULL;
			self::$db[$arr['DB'].'-'.$arr['USER']] = new mysqli(_DB_INFO_HOST_,_DB_INFO_USER_,_DB_INFO_PASSWORD_,_DB_INFO_DBN_);

			$query  = "INSERT INTO `"._DB_INFO_DBN_."`.`tmp_allquerys` ";
			$query .= "(`id`, `gid`, `query`, `result`, `time`, `path`, `line`, `debug`, `add_date`, `delete`) VALUES (NULL, ";
			$query .= "'".self::$db[$arr['DB'].'-'.$arr['USER']]->real_escape_string($tmp_arr['gid'])."',";
			$query .= "'".self::$db[$arr['DB'].'-'.$arr['USER']]->real_escape_string($tmp_arr['query'])."',";
			$query .= "'".self::$db[$arr['DB'].'-'.$arr['USER']]->real_escape_string(print_r($return_arr,true))."',";
			$query .= "'{$tmp_arr['time']}', '{$dbg[1]['file']}', '{$dbg[1]['line']}', ";
			//$query .= isset(self::$debug) ? "'".self::$db[$arr['DB'].'-'.$arr['USER']]->real_escape_string(self::$debug)."'," : "'', ";
			$query .= "'', ";
			$query .= "NOW(), '0');";
			self::$db[$arr['DB'].'-'.$arr['USER']]->query($query);

		}


		return $return_arr;

	}

	//挿入
	static public function edit($query,$arr=array(),$prx=""){

		//初期設定
		$arr = self::first($arr);
		$db = $arr['DB'];

		//結果
		$return_arr = array();

		//時間計測
		$start_time = microtime(true);

		if(!isset($arr['DB'])) $arr['DB'] = $db;

		try {
			$result = self::$db[$arr['DB'] . '-' . $arr['USER']]->query($query);
			if ($result === true) { // 成功した非SELECTクエリ
				$return_arr['status'] = true;
				$return_arr['count'] = self::$db[$arr['DB'] . '-' . $arr['USER']]->affected_rows;
				$return_arr['query'] = $query;
			} else {
				$return_arr['status'] = false;
				$return_arr['count'] = 0;
				$return_arr['query'] = $query;
			}
		} catch (Exception $e) {
			// クエリエラー発生時の処理
			$return_arr['status'] = false;
			$return_arr['count'] = 0;
			$return_arr['query'] = $query;
			$return_arr['error'] = $e->getMessage(); // エラーメッセージを格納
		}

		//実行速度
		$run_time = microtime(true) - $start_time;
		$return_arr['run_time'] = $run_time;

		//ラストインサートID
		$return_arr['last_id'] = self::$db[$arr['DB'].'-'.$arr['USER']]->insert_id;

		if(
			defined('_DB_DEBUG_') &&
			_DB_DEBUG_ === true
		){

			//セッションに出力
			$tmp_arr = array();
			$tmp_arr['query'] = $query;
			$tmp_arr['time'] = $run_time;
			if(method_exists('Session','read')) $tmp_arr['gid'] = Session::read('Auth.group.id');

			$dbg = array();
			$dbg = debug_backtrace();

			self::$db[$arr['DB'].'-'.$arr['USER']] = NULL;
			self::$db[$arr['DB'].'-'.$arr['USER']] = new mysqli(_DB_INFO_HOST_,_DB_INFO_USER_,_DB_INFO_PASSWORD_,_DB_INFO_DBN_);

			$query  = "INSERT INTO `"._DB_INFO_DBN_."`.`tmp_allquerys` ";
			$query .= "(`id`, `gid`, `query`, `result`, `time`, `path`, `line`, `debug`, `add_date`, `delete`) VALUES (NULL, ";
			$query .= "'".self::$db[$arr['DB'].'-'.$arr['USER']]->real_escape_string($tmp_arr['gid'])."',";
			$query .= "'".self::$db[$arr['DB'].'-'.$arr['USER']]->real_escape_string($tmp_arr['query'])."',";
			$query .= "'".self::$db[$arr['DB'].'-'.$arr['USER']]->real_escape_string(print_r($return_arr,true))."',";
			$query .= "'{$tmp_arr['time']}', '{$dbg[1]['file']}', '{$dbg[1]['line']}', ";
			//$query .= isset(self::$debug) ? "'".self::$db[$arr['DB'].'-'.$arr['USER']]->real_escape_string(self::$debug)."'," : "'', ";
			$query .= "'', ";
			$query .= "NOW(), '0');";
			self::$db[$arr['DB'].'-'.$arr['USER']]->query($query);

		}

		return $return_arr;

	}

	static public function where( $arr ){

		//使用データベース------
		$db = $arr['DB'];
		$arr['TABLE'] = $arr['TABLE'];
		$userid = class_exists('Session') && Session::iam('uid') ? Session::iam('uid') : 0;
		$query = '';

		//空だったら返す
		if(empty($arr['WHERE'])) return $query;

		//通常
		if(isset($arr['WHERE']) && is_array($arr['WHERE'])){

			$query  = "WHERE ";
			$query .= self::where_parse( $arr );
			$query .= " AND ";

		}

		//JOIN
		if(isset($arr['JOIN']) && is_array($arr['JOIN'])){
			foreach( $arr['JOIN'] as $k => $v ){
				$k = self::$db[$arr['DB'].'-'.$arr['USER']]->real_escape_string($k);
				if(isset($v['WHERE']) && is_array($v['WHERE'])){
					if($query==='') $query = "WHERE ";
					$query .= self::where_parse(array(
						'DB' => $arr['DB'],
						'TABLE' => $k,
						'WHERE' => $v['WHERE'],
					));
					$query .= " AND ";
				}
			}
		}

		//整形
		if($query!=='') $query = substr($query,0,-4);

		return $query;

	}

	static public function where_parse( $arr ){

		//初期設定
		$arr = self::first($arr);

		if(!$arr['DB']) exit('fatal:DB '.$arr['DB'].' is none!');
		if(!$arr['USER']) exit('fatal:USER '.$arr['USER'].' is none!');

		//ワイルドカードを切る
		if(!isset($arr['NOTWC'])) $arr['NOTWC'] = true;

		$query = '';
		foreach( $arr['WHERE'] as $k => $v ){

			if(!is_array($v)){

				$colmn = '';$tmp_arr = array();
				$k = self::$db[$arr['DB'].'-'.$arr['USER']]->real_escape_string($k);
				if(strpos($k,',')===false){
					$colmn = '`'.$arr['TABLE'].'`.`'.$k.'`';
				}else{
					$tmp_arr = explode(',',$k);
					$colmn = 'CONCAT( ';
					foreach($tmp_arr as $tmp){
						$colmn .= '`'.$arr['TABLE'].'`.`'.$tmp.'`,';
					}
					$colmn = substr($colmn,0,-1).')';
				}
				if(self::is_num($v)){
					//数字
					$query .= $colmn.' = \''.$v.'\' AND ';
				}else if(DateTime::createFromFormat('Y-m-d',$v)){
					//日付
					$query .= $colmn.' = \''.$v.'\' AND ';
				}else if($v===NULL){
					//NULL
					$query .= $colmn.' IS NULL AND ';
				}else{
					$v = self::$db[$arr['DB'].'-'.$arr['USER']]->real_escape_string($v);
					if(!isset($full_flg) || !$full_flg){
						//フルテキスト検索ではない
						if(isset($not_wc) && $not_wc){
							$v = str_replace('%','\%',$v);
							$v = str_replace('_','\_',$v);
						}
						$query .= $colmn.' LIKE ';
						if(isset($bin_flg) && $bin_flg) $query .= 'BINARY ';
						$query .= ' \''.$v.'\' AND ';
					}else{
						//フルテキスト検索
						$query .= 'MATCH(`'.$arr['TABLE'].'`.`'.$k.'`) AGAINST \''.$v.'\' AND ';
					}

				}

			}else{

				//否定語を抜く
				$not_flg = false;
				if(isset($v['NOT'])){
					$not_flg = $v['NOT'];
					unset($v['NOT']);
				}

				//ロジカルオペレーションを抜く
				$or_flg = false;
				if(isset($v['OR'])){
					$or_flg = true;
					if(is_array($v['OR'])) $v[] = $v['OR'];
					unset($v['OR']);
				}

				//バイナリ演算子を抜く
				$bin_flg = false;
				if(isset($v['BIN'])){
					$bin_flg = true;
					unset($v['BIN']);
				}

				//ワイルドカードを抜く
				if(isset($v['WC'])){
					$not_wc = false;
					unset($v['WC']);
				}

				//配列検索を抜く
				$arr_flg = false;
				if(isset($v['ARR'])){
					$arr_flg = true;
					unset($v['ARR']);
				}

				//範囲検索を抜く
				$btw_flg = false;
				if(isset($v['BTW'])){
					$btw_flg = true;
					unset($v['BTW']);
				}

				//フルテキストを抜く
				$full_flg = false;
				$phrase_flg = false;
				$booleanmode_flg = false;
				if(isset($v['FULL'])){
					$full_flg = true;
					unset($v['FULL']);
				}
				if(isset($v['FULL+'])){
					$full_flg = true;
					$phrase_flg = true;//フレーズ検索ON
					unset($v['FULL+']);
				}
				if(isset($v['BOOL'])){
					$booleanmode_flg = true;//ブーリアンモードON
					unset($v['BOOL']);
				}

				//比較演算子を抜く
				$compare = '';
				if(isset($v['<='])){ $compare = '<='; $v[] = $v['<=']; unset($v['<=']);}
				if(isset($v['<'])){ $compare = '<'; $v[] = $v['<']; unset($v['<']);}
				if(isset($v['>='])){ $compare = '>='; $v[] = $v['>=']; unset($v['>=']);}
				if(isset($v['>'])){ $compare = '>'; $v[] = $v['>']; unset($v['>']);}

				//定義された状態で入れる
				$query .= '( ';
				foreach( $v as $k2 => $v2 ){
					if(is_numeric($k2) || !ctype_upper($k2)){

						if(!self::is_num($k2)){
							//数字じゃなかったらキーを使用
							$k2 = self::$db[$arr['DB'].'-'.$arr['USER']]->real_escape_string($k2);
						}else{
							//数字だったら一階層上のキーを使用
							$k2 = self::$db[$arr['DB'].'-'.$arr['USER']]->real_escape_string($k);
						}

						if(!is_array($v2)){

							if($full_flg){

								//フルテキスト検索の場合
								if($phrase_flg){
									if(mb_strlen($v2, 'UTF-8') > 1) $v2 = mb_substr($v2, 0, -1, 'UTF-8');
									$v2 = self::$db[$arr['DB'].'-'.$arr['USER']]->real_escape_string($v2);
									$query .= 'MATCH(`'.$arr['TABLE'].'`.`'.$k2.'`) AGAINST (\'+"'.$v2.'"\' ';
								}else{
									$v2 = self::$db[$arr['DB'].'-'.$arr['USER']]->real_escape_string($v2);
									$query .= 'MATCH(`'.$arr['TABLE'].'`.`'.$k2.'`) AGAINST (\''.$v2.'\' ';
								}
								if($booleanmode_flg) $query .= 'IN BOOLEAN MODE ';
								$query .= ') ';
								if($or_flg) {
									$query .= 'OR  ';
								}else{
									$query .= 'AND ';
								}

							}else if($arr_flg){

								//配列の場合
								$arr = array();
								$arr = explode(',',$v2);
								foreach($arr as $v4){
									$v4 = self::$db[$arr['DB'].'-'.$arr['USER']]->real_escape_string($v4);
									if(isset($not_wc)&&$not_wc){
										$v4 = str_replace('%','\%',$v4);
										$v4 = str_replace('_','\_',$v4);
									}

									//FIND IN SET は遅いので廃止
									//$query .= 'FIND_IN_SET(';
									//$query .= '\''.$v4.'\',';
									//$query .= '`'.$arr['TABLE'].'`.`'.$k2.'` ';
									//$query .= ') ';

									$query .= 'CONCAT(\',\',`'.$arr['TABLE'].'`.`'.$k2.'`,\',\') ';
									$query .= 'LIKE ';
									if($bin_flg) $query .= 'BINARY ';
									$query .= '( ';
									$query .= '\'%,'.$v4.',%\'';
									$query .= ')';

									if($or_flg) {
										$query .= 'OR  ';
									}else{
										$query .= 'AND ';
									}
								}

							}else if($btw_flg){

								//範囲検索の場合
								$btw = array();
								$btw = explode(',',$v2);

								$query .= '(';
								if( isset($btw[0]) && $btw[0] !== '' ){
									$query .= '`'.$arr['TABLE'].'`.`'.$k2.'` >= ';
									$v4 = self::$db[$arr['DB'].'-'.$arr['USER']]->real_escape_string($btw[0]);
									if(isset($not_wc)&&$not_wc){
										$v4 = str_replace('%','\%',$v4);
										$v4 = str_replace('_','\_',$v4);
									}
									$query .= '\''.$v4.'\' AND ';

								}
								if( isset($btw[1]) && $btw[1] !== '' ){
									$query .= '`'.$arr['TABLE'].'`.`'.$k2.'` <= ';
									$v4 = self::$db[$arr['DB'].'-'.$arr['USER']]->real_escape_string($btw[1]);
									if(isset($not_wc)&&$not_wc){
										$v4 = str_replace('%','\%',$v4);
										$v4 = str_replace('_','\_',$v4);
									}
									$query .= '\''.$v4.'\' AND ';

								}
								$query = substr($query,0,-4);
								$query .= ') ';
								if($or_flg) {
									$query .= 'OR  ';
								}else{
									$query .= 'AND ';
								}

							}else if($compare !== ''){
								//比較演算子が使われた場合
								$query .= '`'.$arr['TABLE'].'`.`'.$k2.'` ';
								$query .= $compare;
								$query .= ' \''.$v2.'\' ';
								if($or_flg) {
									$query .= 'OR  ';
								}else{
									$query .= 'AND ';
								}

							}else if(self::is_num($v2)){

								//数字の場合
								$query .= '`'.$arr['TABLE'].'`.`'.$k2.'` ';
								if($not_flg) $query .= '!';
								$query .= '= \''.$v2.'\' ';
								if($or_flg) {
									$query .= 'OR  ';
								}else{
									$query .= 'AND ';
								}

							}else{

								//文字列の場合
								$v2 = self::$db[$arr['DB'].'-'.$arr['USER']]->real_escape_string($v2);
								if(isset($not_wc)&&$not_wc){
									$v2 = str_replace('%','\%',$v2);
									$v2 = str_replace('_','\_',$v2);
								}

								$query .= '`'.$arr['TABLE'].'`.`'.$k2.'` ';
								if($not_flg) $query .= 'NOT ';
								$query .= 'LIKE ';
								if($bin_flg) $query .= 'BINARY ';
								$query .= '\''.$v2.'\' ';
								if($or_flg) {
									$query .= 'OR  ';
								}else{
									$query .= 'AND ';
								}
							}

						}else{


							//ORなどで複数の条件式
							foreach($v2 as $v3){

								//比較演算子を抜く
								$compare = '';
								if(isset($v2['<='])){ $compare = '<=';}
								if(isset($v2['<'])){ $compare = '<';}
								if(isset($v2['>='])){ $compare = '>=';}
								if(isset($v2['>'])){ $compare = '>';}

								if($arr_flg){

									//配列の場合
									$arr = array();
									$arr = explode(',',$v3);
									foreach($arr as $v4){
										$v4 = self::$db[$arr['DB'].'-'.$arr['USER']]->real_escape_string($v4);
										if(isset($not_wc)&&$not_wc){
											$v4 = str_replace('%','\%',$v4);
											$v4 = str_replace('_','\_',$v4);
										}

										//FIND IN SET は遅いので廃止
										//$query .= 'FIND_IN_SET(';
										//$query .= '\''.$v4.'\',';
										//$query .= '`'.$arr['TABLE'].'`.`'.$k2.'` ';
										//$query .= ') ';
										$query .= 'CONCAT(\',\',`'.$arr['TABLE'].'`.`'.$k2.'`,\',\') ';
										$query .= 'LIKE ';
										if($bin_flg) $query .= 'BINARY ';
										$query .= '( ';
										$query .= '\'%,'.$v4.',%\'';
										$query .= ')';

										if($or_flg) {
											$query .= 'OR  ';
										}else{
											$query .= 'AND ';
										}
									}

								}else if(self::is_num($v3)){

									//数字の場合
									$query .= '`'.$arr['TABLE'].'`.`'.$k2.'` ';
									if($not_flg) $query .= '!';
									$query .= '= \''.$v3.'\' ';
									if($or_flg) {
										$query .= 'OR  ';
									}else{
										$query .= 'AND ';
									}

								}else if(DateTime::createFromFormat('Y-m-d',$v3)){

									//日付
									$query .= '`'.$arr['TABLE'].'`.`'.$k2.'` ';
									if($compare !== ''){
										$query .= $compare;
									}else{
										if($not_flg) $query .= '!';
										$query .= '= ';
									}
									$query .= '\''.$v3.'\' ';
									if($or_flg) {
										$query .= 'OR  ';
									}else{
										$query .= 'AND ';
									}

								}else if($v3===NULL){

									//NULL
									$query .= '`'.$arr['TABLE'].'`.`'.$k2.'` ';
									$query .= 'IS ';
									if($not_flg) $query .= 'NOT ';
									$query .= 'NULL ';
									if($or_flg) {
										$query .= 'OR  ';
									}else{
										$query .= 'AND ';
									}

								}else{

									//文字列の場合
									$v3 = self::$db[$arr['DB'].'-'.$arr['USER']]->real_escape_string($v3);
									if(isset($not_wc)&&$not_wc){
										$v3 = str_replace('%','\%',$v3);
										$v3 = str_replace('_','\_',$v3);
									}
									$query .= '`'.$arr['TABLE'].'`.`'.$k2.'` ';
									if($not_flg) $query .= 'NOT ';
									$query .= 'LIKE ';
									if($bin_flg) $query .= 'BINARY ';
									$query .= '\''.$v3.'\' ';
									if($or_flg) {
										$query .= 'OR  ';
									}else{
										$query .= 'AND ';
									}
								}

							}

						}

					}
				}
				$query = substr($query,0,-4);
				$query .= ') AND ';
			}
		}
		$query = substr($query,0,-4);

		return $query;


	}

	static public function order($arr){
		$query = '';
		if(is_array($arr['ORDER'])){
			foreach( $arr['ORDER'] as $k => $v ){
				$k = self::r($k);
				if($v==="ASC"||$v==="asc"){
					$query .= '`'.$arr['TABLE'].'`.`'.$k.'` ASC , ';
				}else if($v==="DESC"||$v==="desc"){
					$query .= '`'.$arr['TABLE'].'`.`'.$k.'` DESC , ';
				}else if($v==="RAND()"||$v==="rand()"){
					$query .= 'RAND() , ';
				}
			}
		}else{
			$v = self::r($arr['ORDER']);
			if($v==="RAND()"||$v==="rand()"){
				$query .= 'RAND() , ';
			}else{
				$query .= '`'.$arr['TABLE'].'`.`'.$v.'` ASC , ';
			}
		}
		return $query;
	}


	//便利関数群------------------------

	static public function insert($arr){

		//デバッグコメント格納-----
		self::$debug = '';
		if(isset($arr['DEBUG'])) self::$debug = $arr['DEBUG'];

		$arr = self::first($arr);

		//使用データベース------
		$db = $arr['DB'];
		$arr['TABLE'] = $arr['TABLE'];
		$userid = class_exists('Session') && Session::iam('uid') ? Session::iam('uid') : 0;
		$now = date('Y-m-d H:i:s');

		//セットされていない基本項目を補完
		if(!isset($arr['SET']['add_user'])) $arr['SET']['add_user'] = $userid;
		if(!isset($arr['SET']['edit_user'])) $arr['SET']['edit_user'] = $userid;
		if(!isset($arr['SET']['add_date'])) $arr['SET']['add_date'] = $now;
		if(!isset($arr['SET']['edit_date'])) $arr['SET']['edit_date'] = $now;
		if(!isset($arr['SET']['delete'])) $arr['SET']['delete'] = 0;

		//なかったらインサート（非 ON DUPLICATE KEY UPDATE）
		if(isset($arr['WHERE'])&&!empty($arr['WHERE'])){
			$if_no_insert = true;
		}else{
			$if_no_insert = false;
		}

		//マルチプルインサートか否か
		if(!is_array(current($arr['SET']))){
			$multi_flg = false;
		}else{
			$multi_flg = true;
		}

		//クエリ生成
		$query = "INSERT INTO `{$db}`.`{$arr['TABLE']}` (`id`,";
		if($multi_flg===false){

			//単品
			foreach( $arr['SET'] as $k => $v ){

				//コンティニュー(下にもあるよ)
				if($k==='id') continue;

				$k = self::$db[$arr['DB'].'-'.$arr['USER']]->real_escape_string($k);
				$query .= "`{$k}`,";
			}

		}else{

			//マルチプルインサート
			$ins = current($arr['SET']);
			foreach( $ins as $k => $v ){
				$k = self::$db[$arr['DB'].'-'.$arr['USER']]->real_escape_string($k);
				$query .= "`{$k}`,";
			}
		}

		$query = substr($query,0,-1);
		$query .= ") ";

		if($if_no_insert){
			$query .= "SELECT * FROM (SELECT ";
		}else{
			$query .= "VALUES ";
		}


		if($multi_flg===false){

			//単品
			if($if_no_insert){
				//do nothing
			}else{
				$query .= "(";
			}
			if( isset($arr['SET']['id']) ){
				if(self::is_num($arr['SET']['id'])){
					$query .= "{$arr['SET']['id']}, ";
				}else{
					$arr['SET']['id'] = self::$db[$arr['DB'].'-'.$arr['USER']]->real_escape_string($arr['SET']['id']);
					$query .= "'{$arr['SET']['id']}', ";
				}
			}else{
				$query .= "NULL, ";
			}
			foreach( $arr['SET'] as $k => $v ){

				//コンティニュー(上にもあるよ)
				if($k==='id') continue;

				if( self::is_num($v) ){
					$query .= '\''.$v.'\' ';
				}else if( $v === 'NOW()' ){
					$query .= '\''.$now.'\' ';
				}else{
					$v = self::$db[$arr['DB'].'-'.$arr['USER']]->real_escape_string($v);
					$query .= '\''.$v.'\' ';
				}
				if($if_no_insert){
					$query .= "AS `".$k."`,";
				}else{
					$query .= ",";
				}
			}
			$query = substr($query,0,-1);

			if($if_no_insert){
				$query .= " ) AS TMP WHERE NOT EXISTS (SELECT * FROM `{$db}`.`{$arr['TABLE']}` ";
				$query .= self::where($arr);
				$query .= " );";
			}else{
				$query .= " );";
			}

			// if(isset($arr['WHERE']['id']) && $arr['WHERE']['id']){
			// 	if(self::is_num($arr['WHERE']['id'])){
			// 		$query .= "ON DUPLICATE KEY UPDATE id = ".$arr['WHERE']['id'].", ";
			// 	}else{
			// 		$arr['WHERE']['id'] = self::$db[$arr['DB'].'-'.$arr['USER']]->real_escape_string($arr['WHERE']['id']);
			// 		$query .= "ON DUPLICATE KEY UPDATE id = '".$arr['WHERE']['id']."', ";
			// 	}
			// 	foreach( $arr['SET'] as $k => $v ){
			// 		//コンティニュー(下にもあるよ)
			// 		if($k==='id') continue;
			// 		$k = self::$db[$arr['DB'].'-'.$arr['USER']]->real_escape_string($k);
			// 		$query .= "`{$k}`= ";
			// 		if( self::is_num($v) ){
			// 			$query .= '\''.$v.'\', ';
			// 		}else if( $v === 'NOW()' ){
			// 			$query .= '\''.$now.'\', ';
			// 		}else{
			// 			$v = self::$db[$arr['DB'].'-'.$arr['USER']]->real_escape_string($v);
			// 			$query .= '\''.$v.'\', ';
			// 		}
			// 	}
			// 	$query = substr($query, 0, -2);
			// }
			//$query .= ";";

		}else{

			//マルチプルインサート
			foreach( $arr['SET'] as $k => $v ){
				$query .= "(NULL, ";
				foreach($v as $v2){
					if( self::is_num($v2) ){
						$query .= '\''.$v2.'\', ';
					}else if( $v2 === 'NOW()' ){
						$query .= '\''.date('Y-m-d H:i:s').'\', ';
					}else{
						$v2 = self::$db[$arr['DB'].'-'.$arr['USER']]->real_escape_string($v2);
						$query .= '\''.$v2.'\', ';
					}
				}
				$query .= "'{$userid}', '{$userid}', '{$now}', '{$now}', '0') , ";
			}
			$query = substr($query,0,-2);
		}

		//更新
		return self::edit($query,$arr);

	}

	static public function update($arr){

		//デバッグコメント格納-----
		self::$debug = '';
		if(isset($arr['DEBUG'])) self::$debug = $arr['DEBUG'];

		$arr = self::first($arr);

		$db = $arr['DB'];
		$arr['TABLE'] = $arr['TABLE'];
		$userid = class_exists("Session") && Session::iam('uid') ? Session::iam('uid') : 0;
		$now = date('Y-m-d H:i:s');

		//単品
		$query  = "UPDATE `{$db}`.`{$arr['TABLE']}` SET ";
		if (isset($arr['SET']) && is_array($arr['SET'])) {
			foreach ($arr['SET'] as $k => $v) {
				if(!is_array($v)){
					//通常
					$k = self::$db[$arr['DB'] . '-' . $arr['USER']]->real_escape_string($k);
					if (self::is_num($v) && substr($v, 0, 1) != 0) {
						$query .= '`' . $k . '` = \'' . $v . '\' , ';
					} else if ($v === 'NOW()') {
						$query .= '`' . $k . '` = \'' . $now . '\' , ';
					} else {
						$v = self::$db[$arr['DB'] . '-' . $arr['USER']]->real_escape_string($v);
						$query .= '`' . $k . '` = \'' . $v . '\' , ';
					}
				}else{
					//特殊アップデート
					if(isset($v['JSON_APPEND'])){
						//JSON型に追加
						unset($v['JSON_APPEND']);
						foreach ($v as $k2 => $v2) {
							$parts = array();
							$parts = self::buildJsonFromNestedArray($v2,$arr);
							$query .= '`' . $k2 . '` =  JSON_ARRAY_APPEND(`'.$k2.'`,\'$\','.$parts.') , ';
						}
					}else if(isset($v['JSON_MERGE'])) {
						unset($v['JSON_MERGE']);
						foreach ($v as $k2 => $v2) {
							$parts = array();
							$parts = self::buildJsonFromNestedArray($v2, $arr);
							$query .= '`' . $k2 . '` =  JSON_MERGE_PATCH(`'.$k2.'`,'.$parts.') , ';
						}
					} else if (isset($v['JSON_SET'])) {
						unset($v['JSON_SET']);
						foreach ($v as $key => $value) {
							// キーを '.' で分割
							$keys = explode('.', $key);
							// メインキーを取り出し、残りの階層を解析
							$mainKey = array_shift($keys);
							// SQLインジェクション防止のためのエスケープ
							$mainKeyEscaped = self::$db[$arr['DB'] . '-' . $arr['USER']]->real_escape_string($mainKey);
							// JSONパスの作成
							$jsonPath = '$';
							foreach ($keys as $keyPart) {
								// サブキーをエスケープ
								$keyPartEscaped = self::$db[$arr['DB'] . '-' . $arr['USER']]->real_escape_string($keyPart);
								// 数字なら配列としてインデックス指定、文字列ならキー指定
								if (is_numeric($keyPartEscaped)) {
									$jsonPath .= '[' . $keyPartEscaped . ']';
								} else {
									$jsonPath .= '.'.$keyPartEscaped;
								}
							}
							// 値のエスケープ処理
							if(is_array($value)){
								$parts = array();
								$parts = self::buildJsonFromNestedArray($value, $arr);
								$query .= '`' . $mainKeyEscaped . '` = JSON_SET(`' . $mainKeyEscaped . '`, \'' . $jsonPath . '\', ' . $parts . '), ';
							}else{
								$jsonValue = "'" . self::$db[$arr['DB'] . '-' . $arr['USER']]->real_escape_string($value) . "'";
								$query .= '`' . $mainKeyEscaped . '` = JSON_SET(`' . $mainKeyEscaped . '`, \'' . $jsonPath . '\', ' . $jsonValue . '), ';
							}
						}
					}else if(isset($v['MATH'])){
						//四則計算
						unset($v['MATH']);
						foreach ($v as $k2 => $v2) {
							$result = array();
							if ($rcd = preg_match('/^ *(\+|\-) *([0-9\.]+) *$/', $v2, $result)) {
								$k2 = self::$db[$arr['DB'] . '-' . $arr['USER']]->real_escape_string($k2);
								$query .= '`' . $k2 . '` = `' . $k2 . '` ' . $result[1] . ' ' . $result[2] . ' , ';
							}
						}
					}
				}
				
			}
		}
		//$query = substr($query,0,-2);
		$query  .= "`edit_user` =  {$userid} , ";
		$query  .= "`edit_date` =  '" . $now . "' ";

		if (!is_array($arr['WHERE'])) exit('fatal: no where update!');
		$query .= self::where($arr);
		return self::edit($query, $arr);

	}

	static public function col($col_arr,$table,$arr){
		$call_option = '';
		foreach( $col_arr as $k => $v ){
			if(is_string($v)){
				if( $v !== '*' ){
					//通常コールオプション
					$v = self::$db[$arr['DB'].'-'.$arr['USER']]->real_escape_string($v);
					$call_option .= '`'.$table.'`.`'.$v.'` , ';
				}else{
					//全部
					$call_option .= '* , ';
				}
			}else if(is_array($v)){
				$col = self::$db[$arr['DB'].'-'.$arr['USER']]->real_escape_string($k);
				$call_option .= '`'.$table.'`.`'.$col.'` ';
				if(isset($v['AS'])){
					$asname = self::$db[$arr['DB'].'-'.$arr['USER']]->real_escape_string($v['AS']);
					$call_option .= 'AS `'.$asname.'` ';
				}
				$call_option .= ' , ';
			}
		}
		$call_option = substr($call_option,0,-2);
		return $call_option;
	}

	static public function select($arr){

		$arr = self::first($arr);

		$db = $arr['DB'];
		$arr['TABLE'] = $arr['TABLE'];

		$query  = "SELECT ";

		//コールオプション
		$query .= '_CALL_OPTION_HERE_ ';
		$call_option = '';
		if(isset($arr['COL']) && is_array($arr['COL'])){
			$call_option .= self::col($arr['COL'],$arr['TABLE'],$arr);
		}else{
			$call_option .= "* ";
		}
		//JOINする場合はIDを分配
		if( isset($arr['JOIN']) && is_array($arr['JOIN']) ){
			$table2 = self::$db[$arr['DB'].'-'.$arr['USER']]->real_escape_string(key($arr['JOIN']));
			if(isset($arr['JOIN'][$table2]['COL']) && is_array($arr['JOIN'][$table2]['COL'])){
				$call_option .= ", ".self::col($arr['JOIN'][$table2]['COL'],$table2,$arr);
			}
			$call_option .= ", `{$db}`.`{$arr['TABLE']}`.`id` AS id ";
			$call_option .= ", `{$db}`.`{$arr['TABLE']}`.`add_date` AS add_date ";
			$call_option .= ", `{$db}`.`{$arr['TABLE']}`.`edit_date` AS edit_date ";
			$call_option .= ", `{$db}`.`".$table2."`.`id` AS id2 ";
			$call_option .= ", `{$db}`.`".$table2."`.`add_date` AS add_date2 ";
			$call_option .= ", `{$db}`.`".$table2."`.`edit_date` AS edit_date2 ";
		}

		//FROM
		$query .= "FROM `{$db}`.`{$arr['TABLE']}` ";

		//LEFTJOIN
		if( isset($arr['JOIN']) && is_array($arr['JOIN']) ){
			foreach( $arr['JOIN'] as $k => $v ){

				//許可するタイプ
				$allow = array();
				$allow['LEFT'] = 1;
				$allow['LEFT OUTER'] = 1;

				$type = mb_strtoupper($v['TYPE']);
				if(!isset($allow[$type])) exit('JOIN ERROR!');

				$k = self::$db[$arr['DB'].'-'.$arr['USER']]->real_escape_string($k);
				$query .= $type." JOIN `{$db}`.`{$k}` ";

				$query .= "ON ";
				foreach( $v['ON'] as $k2 => $v2 ){
					$query .= "`{$db}`.";
					if(strpos($k2,'.')!==false){
						//点があったら別テーブル
						$tmp_arr = explode('.',$k2);
						$tmp_arr[0] = self::$db[$arr['DB'].'-'.$arr['USER']]->real_escape_string($tmp_arr[0]);
						$tmp_arr[1] = self::$db[$arr['DB'].'-'.$arr['USER']]->real_escape_string($tmp_arr[1]);
						$query .= "`{$tmp_arr[0]}`.`{$tmp_arr[1]}` = ";
					}else{
						//なかったら本テーブル
						$k2 = self::$db[$arr['DB'].'-'.$arr['USER']]->real_escape_string($k2);
						$query .= "`{$arr['TABLE']}`.`{$k2}` = ";
					}
					$query .= "`{$db}`.";
					if(strpos($v2,'.')!==false){
						//点があったら別テーブル
						$tmp_arr = explode('.',$v2);
						$tmp_arr[0] = self::$db[$arr['DB'].'-'.$arr['USER']]->real_escape_string($tmp_arr[0]);
						$tmp_arr[1] = self::$db[$arr['DB'].'-'.$arr['USER']]->real_escape_string($tmp_arr[1]);
						$query .= "`{$tmp_arr[0]}`.`{$tmp_arr[1]}` AND ";
					}else{
						//なかったら結合テーブル
						$v2 = self::$db[$arr['DB'].'-'.$arr['USER']]->real_escape_string($v2);
						$query .= "`{$k}`.`{$v2}` AND ";
					}
				}
				$query = substr($query,0,-4);

			}

		}

		//WHRER
		$query .= self::where($arr);

		//GROUP BY
		if( isset($arr['GROUP']) && !empty($arr['GROUP']) ){
			if(strpos($arr['GROUP'],'.')!==false){
				//点があったら別テーブル
				$tmp_arr = explode('.',$arr['GROUP']);
				$tmp_arr[0] = self::$db[$arr['DB'].'-'.$arr['USER']]->real_escape_string($tmp_arr[0]);
				$tmp_arr[1] = self::$db[$arr['DB'].'-'.$arr['USER']]->real_escape_string($tmp_arr[1]);
				$query .= "GROUP BY `{$db}`.`{$tmp_arr[0]}`.`{$tmp_arr[1]}` ";
			}else{
				//なかったら本テーブル
				$v = self::$db[$arr['DB'].'-'.$arr['USER']]->real_escape_string($arr['GROUP']);
				$query .= "GROUP BY `{$db}`.`{$arr['TABLE']}`.`{$v}` ";
			}
		}

		//ORDER
		$order_flg = false;
		if(isset($arr['ORDER']) && !empty($arr['ORDER'])){
			$order_flg = true;
			$query .= "ORDER BY ";
			$query .= self::order(array(
				'TABLE' => $arr['TABLE'],
				'ORDER' => $arr['ORDER'],
			));
			$query = substr($query,0,-2);
		}
		if( isset($arr['JOIN']) && is_array($arr['JOIN']) ){
			foreach( $arr['JOIN'] as $k => $v ){
				if(isset($v['ORDER']) && !empty($v['ORDER'])){
					if($order_flg===true){
						$query .= " , ";
					}else{
						$query .= "ORDER BY ";
					}
					$query .= self::order(array(
						'TABLE' => $k,
						'ORDER' => $v['ORDER'],
					));
					$query = substr($query,0,-2);
				}
			}
		}

		//SQL発行
		$return_arr = array();
		if(empty($arr['SP'])){

			//通常------

			//置換
			$query = str_replace('_CALL_OPTION_HERE_',$call_option,$query);

			//LIMIT
			if(isset($arr['LIMIT']) && self::is_num($arr['LIMIT'])){
				$query .= "LIMIT ".$arr['LIMIT'];
			}


			//発行
			$return_arr = self::show($query,$arr);

		}else{

			//ページネーション------

			//カウント
			$all_count = 0;$count_query = '';
			if(strpos($query,'GROUP BY')===false){
				//standard
				$count_query = str_replace('_CALL_OPTION_HERE_','COUNT(`'.$arr['DB'].'`.`'.$arr['TABLE'].'`.`id`) as `count`',$query);
			}else{
				$mresult = array();
				if(preg_match('/GROUP BY ([^ ]+) /su',$query,$mresult)){
					$count_query = str_replace('_CALL_OPTION_HERE_','COUNT(DISTINCT '.$mresult[1].') as `count`',$query);
					$count_query = str_replace($mresult[0],'',$count_query);
				}
			}

			$return_arr = self::show($count_query,$arr);
			$return_arr['paginator'] = $count_query;
			if(isset($return_arr['data'][0]['count'])) $all_count = $return_arr['data'][0]['count'];


			//リミットを製作

			//現在のページ数
			if(isset($arr['NP'])){
				$now_page = (int)$arr['NP'];
			}else{
				$now_page = 0;
			}

			//分割数
			$sp_num = $arr['SP'];

			//上限計算
			$max_num = floor( (int)$all_count / (int)$sp_num );

			//製作
			if($max_num >= $now_page){
				$query .= " LIMIT ". ((int)0 + ((int)$sp_num * (int)$now_page)) .",". (int)$sp_num .";";
			}else{
				$query .= " LIMIT 0,". (int)$sp_num .";";
			}

			//置換
			$query = str_replace('_CALL_OPTION_HERE_',$call_option,$query);

			//発行
			$return_arr = self::show($query,$arr);

			//付加情報追加
			$return_arr['count'] = (int)$all_count;
			$return_arr['mp'] = (int)$max_num;
			$return_arr['np'] = (int)$now_page;


			// //HTML製作--------
			// if(file_exists(_ROOT_.'src/Template/Core/My/paginator.php')){
			//
			// 	//読み込み
			// 	include(_ROOT_.'src/Template/Core/My/paginator.php');
			//
			// }else{
			//
			// 	echo 'Fatal error "'._ROOT_.'src/Template/Core/My/paginator.php" is not exists!';
			// 	exit();
			//
			// }




		}

		//KEYが指定されてたら加工
		if(isset($arr['KEY'])){

			if(
				$return_arr['status'] === true &&
				$return_arr['count'] !== 0 &&
				isset($return_arr['data'][0][$arr['KEY']])
			){

				$tmp_arr = array();
				$tmp_arr = $return_arr['data'];

				$return_arr['data'] = array();
				foreach($tmp_arr as $v){
					foreach($v as $k2 => $v2){

						$return_arr['data'][$v[$arr['KEY']]][$k2] = $v2;
					}
				}

			}

		}

		return $return_arr;

	}

	//データ挿入エスケープ
	static public function r($str,$arr=array()){

		//初期設定
		$arr = self::first($arr);
		$db = $arr['DB'];

		return self::$db[$arr['DB'].'-'.$arr['USER']]->real_escape_string($str);

	}

	//データ検索エスケープ
	static public function s($str,$arr=array()){

		//初期設定
		$arr = self::first($arr);
		$db = $arr['DB'];

		//SQL問い合わせ前用処理（キルワイルドカード）
		$str = str_replace('%','\%',$str);
		$str = str_replace('_','\_',$str);



		return self::$db[$arr['DB'].'-'.$arr['USER']]->real_escape_string($str);

	}

	//数値判別メソッド
	static public function is_num($num){

		/*--------------------
		is_numericでは以下のデータを数値と見なしてしまうので
		独自の数値判別処理が必要
		1.23
		.123
		1e2
		---------------------*/

		if(preg_match('/^[0-9]+$/u',(string)$num)){
			return true;
		}else{
			return false;
		}

	}

	//配列を再帰的にJSON型に変換する
	static public function buildJsonFromNestedArray($array,$arr=array())
	{
		$parts = [];
		foreach ($array as $key => $value) {
			$escapedKey = self::$db[$arr['DB'].'-'.$arr['USER']]->real_escape_string($key);
			if (is_array($value)) {
				// 再帰的に処理
				$nestedJson = self::buildJsonFromNestedArray($value,$arr);
				$parts[] = "'".$escapedKey."',".$nestedJson;
			} else {
				// シンプルなキーと値のペア
				$escapedValue = self::$db[$arr['DB'] . '-' . $arr['USER']]->real_escape_string($value);
				$parts[] = "'".$escapedKey."','". $escapedValue."'";
			}
		}
		return "JSON_OBJECT(" . implode(', ', $parts) . ")";
	}

	//多次元配列をフラットな配列に変換
	static public function convertToNestedArray($input)
	{
		$output = [];

		// 各キーと値を処理して多次元配列に変換
		foreach ($input as $key => $value) {
			// キーを '.' で分割
			$keys = explode('.', $key);
			// 参照用の変数を定義（$outputを基点に）
			$current = &$output;

			// 最後の要素以外をループして多次元配列を構築
			foreach ($keys as $subkey) {
				// 最後の要素でない場合、新しい配列を作成
				if (!isset($current[$subkey])) {
					$current[$subkey] = [];
				}
				$current = &$current[$subkey];
			}

			// 最後の要素に値を代入
			$current = $value;
		}

		return $output;
	}


}

?>
