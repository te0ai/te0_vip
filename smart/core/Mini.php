<?php

class Mini{

	//変数定義
	static public function v($unique='',$arr = array()){
		//変数の基本形
		return '(?P<var'.$unique.'>[a-zA-Z\_][a-zA-Z0-9\_]*)';
	}

	//パース
	static public function parse($str,$_=array(),$eval=true,$exp=array('{{','}}')){

		//整合性
		if( !isset($exp[0]) || !$exp[0] || !isset($exp[1]) || !$exp[1]) exit('fatal: Expansion is illegal.');

		//定数
		if(!isset($_['NOW'])) $_['NOW'] = date("Y-m-d H:i:s");
		if(!isset($_['IAM'])) $_['IAM'] = Session::iam('id');


		//改行コードを除去
		$str = preg_replace(array('/\n/','/\t/','/\r/'),'',$str);

		//展開変数があれば展開する
		if(preg_match('/'.preg_quote($exp[0]).'.*?'.preg_quote($exp[1]).'/su',$str)){

			//扱いにくいのでカッコ内にある 連続する半角空白 展開変数に隣接する空白 を除去 これを一番初めに行うのが重要
			$str = preg_replace_callback('/'.preg_quote($exp[0]).'(.*?)'.preg_quote($exp[1]).'/su',
					function($match) use ($exp) {
						return $exp[0].preg_replace('/[ ]{1,}/',' ',trim($match[1])).$exp[1];
			},$str);

			//まず不正なPHPの呼び出しを一掃
			$str = str_replace('<?','',$str);
			$str = str_replace('?>','',$str);

			//変数の処理
			$str = preg_replace_callback(
			'/'.preg_quote($exp[0]).'(?:'.self::v(0).'|'.self::v(1).'\|(?P<mod>[^\}]+))'.preg_quote($exp[1]).'/su',
				function($match) use ($exp){

					if(isset($match['mod'])){
						//修飾子がある
						$mod = array('','');
						$mod = self::mod($match['mod']);
						return '<?php echo '.$mod[0].' $_[\''.$match['var1'].'\'] '.$mod[1].' ?>';
					}else{
						//修飾子なし
						return '<?php echo '.$mod[0].' $_[\''.$match['var0'].'\'] '.$mod[1].' ?>';
					}
			},$str);

			//mathの処理 need Math core
			if(class_exists('Math')){
				$str = preg_replace_callback('/'.preg_quote($exp[0]).'(?P<math>math) (?P<calc>[a-z0-9 \+\-\/\*\% \_\(\)\.]+)'.preg_quote($exp[1]).'/su',
					function($match) use ($_){
						if($match['math']==='math'){
							//単純計算---------------------------------
							$match['calc'] = preg_replace_callback('/'.self::v(0).'/su',
								function($match2){
									//三項演算子で変数がない、数字でない場合は0がセットされるように
									return $match2['var0'] ? '\'.((isset($_[\''.$match2['var0'].'\'])&&is_numeric($_[\''.$match2['var0'].'\']))?$_[\''.$match2['var0'].'\']:0).\'' : '';
								},$match['calc']);
							return '<?php echo Math::calc(\''.$match['calc'].'\'); ?>';
						}
					},$str);
			}

			//ifの処理 「%%」は余剰「%」を利用
			//ifの処理 「@@」は「strpos」を利用して言葉が含まれている場合の処理を行う「!@」はその逆
			$str = preg_replace_callback('/'.preg_quote($exp[0]).'(?P<if>if|elseif) (?:'.self::v(0).'|\"(?P<str0>.*?)\") (?P<operator>==|!=|>|<|>=|<=|%%|@@|!@) (?:'.self::v(1).'|\"(?P<str1>.*?)\")'.preg_quote($exp[1]).'/su',
				function($match) use ($exp){
					//ストリングは関数を実行されないようにシングルクォーテーションをエスケープ
					$match['str0'] = str_replace('\'','\\\'',$match['str0']);
					$match['str1'] = str_replace('\'','\\\'',$match['str1']);
					//左辺と右辺
					$left = $match['var0'] ? '$_[\''.$match['var0'].'\']' : '\''.$match['str0'].'\'';
					$right = $match['var1'] ? '$_[\''.$match['var1'].'\']' : '\''.$match['str1'].'\'';
					//オペレーター別処理
					if($match['operator']==='%%'){
						//「%%」は余剰「%」を利用
						return '<?php '.$match['if'].'( intval('.$left.') % intval('.$right.') === 0 ): ?>';
					}else if($match['operator']==='@@'){
						//「@@」は「strpos」を利用して言葉が含まれている場合の処理を行う
						return '<?php '.$match['if'].'( strpos('.$left.' , '.$right.') !== false ): ?>';
					}else if($match['operator']==='!@'){
						//「!@」は「strpos」を利用して言葉が含まれていない場合の処理を行う
						return '<?php '.$match['if'].'( strpos('.$left.' , '.$right.') === false ): ?>';
					}else{
						return '<?php '.$match['if'].'( '.$left.' '.$match['operator'].' '.$right.'): ?>';
					}
			},$str);

			//ifのエイリアス存在しないまたは空でないか調べる関数
			$str = preg_replace_callback('/'.preg_quote($exp[0]).'(?P<in>in|\!in) (?:'.self::v(0).')'.preg_quote($exp[1]).'/su',
				function($match) use ($exp){
					//エイリアス別処理
					if($match['in']==='in'){
						return '<?php if( isset($_[\''.$match['var0'].'\']) && $_[\''.$match['var0'].'\'] !== "" ): ?>';
					}else if($match['in']==='!in'){
						return '<?php if( !isset($_[\''.$match['var0'].'\']) || $_[\''.$match['var0'].'\'] === "" ): ?>';
					}
			},$str);

			//elseの処理
			$str = preg_replace('/'.preg_quote($exp[0]).'(else\:|in\:)'.preg_quote($exp[1]).'/su','<?php else: ?>',$str);

			//endifの処理
			$str = preg_replace('/'.preg_quote($exp[0]).'(endif;|\/if|endis;|\/in|\/\!in)'.preg_quote($exp[1]).'/su','<?php endif; ?>',$str);

			//foreachの処理
			$str = preg_replace_callback('/'.preg_quote($exp[0]).'for(?P<num>[0-9]*) (?:'.self::v(0).')'.preg_quote($exp[1]).'(?P<inner>.*?)'.preg_quote($exp[0]).'(endforeach|\/for)'.preg_quote($exp[1]).'/su',
				function($match) use ($_){
					$rand = md5(uniqid());
					$match['inner'] = preg_replace_callback('/\$_\[\''.self::v(0).'\'\](?:[^\[])/su',
						function($match2) use ($_,$rand){
							if( isset($_[$match2['var0']]) && is_array($_[$match2['var0']])){
								return '$_[\''.$match2['var0'].'\'][$key'.$rand.'] ';
							}else{
								return '$_[\''.$match2['var0'].'\'] ';
							}
						},$match['inner']);
					$match['num'] = is_numeric($match['num']) ? $match['num'] : '0';
					return '<?php for($i'.$rand.'=0;$i'.$rand.'<max(count((array)$_[\''.$match['var0'].'\']),'.$match['num'].');++$i'.$rand.'): $key'.$rand.' = key(array_slice((array)$_[\''.$match['var0'].'\'],$i'.$rand.',1,true)); ?>'.$match['inner'].'<?php endfor; ?>';

				},$str);

			//最後にeval
 			if($eval===true){
 				ob_start();
 				eval('?>'.$str);
 				$str = ob_get_contents();
 				ob_end_clean();
 			}


			return $str;

		}else{
			return $str;
		}

	}

	//修飾子のパース
	static public function mod($str){

		//修飾子用
		$front = '';
		$back = '';

		$arr = array();
		$arr = explode('|',$str);

		foreach($arr as $value ){

			//計算式
			if( $value == 'number_format' ){

				$front = 'Mini::number("number_format",'.$front;
				$back = $back.')';

			}else if( $value == 'floor' ){

				$front = 'Mini::number("floor",'.$front;
				$back = $back.')';

			}else if( $value == 'ceil' ){

				$front = 'Mini::number("ceil",'.$front;
				$back = $back.')';

			}else if(preg_match('/^round\:(\-*[0-9]+)$/',$value,$result)){

				$front = 'Mini::number("round",'.$front;
				$back = $back.', '.$result[1].')';

			//ゼロパディング
			}else if(preg_match('/^zeropad\:([0-9]+)$/',$value,$result)){

				$front = 'sprintf(\'%0'.$result[1].'d\','.$front;
				$back = $back.')';

			//改行をbrに変換
			}else if( $value == 'nl2br' ){

				$front = 'nl2br('.$front;
				$back = $back.')';

			//タグを取り除く
			}else if(preg_match('/^strip\_tags\:*([a-zA-Z\,]*)$/',$value,$result)){

				$tags = '';
				if( isset($result[1]) && !empty($result[1])){
					$tmp_arr = array();
					$tmp_arr = explode(',',$result[1]);
					if(!empty($tmp_arr)){
						$tags = ',\'';
						foreach($tmp_arr as $v){
							if(!empty($v)) $tags .= '<'.$v.'>';
						}
						$tags .= '\'';
					}
				}

				$front = 'strip_tags('.$front;
				$back = $back.$tags.')';

			//文字列操作
			}else if(preg_match('/^mb\_strimwidth\:([0-9]+)\:([0-9]+)$/',$value,$result)){

				$front = 'mb_strimwidth('.$front;
				$back = $back.', '.$result[1].', '.$result[2].',\'\',\'utf-8\')';

			//ユニークID生成
			}else if(preg_match('/^uniqueid$/',$value,$result)){

				$front = 'Mini::uniqueid('.$front;
				$back = $back.')';

			}else if(preg_match('/^urlencode\:([a-zA-Z0-9\-]+)$/',$value,$result)){

				$tmp1='';
				$result[1] = str_replace('-','',mb_strtolower($result[1]));
				if($result[1]=='shiftjis'){
					$tmp1='Shift-JIS';
				}else if($result[1]=='eucjp'){
					$tmp1='EUC-JP';
				}else{
					$tmp1='UTF-8';
				}

				$front = 'urlencode(mb_convert_encoding('.$front;
				$back = $back.', \''.$tmp1.'\',\'utf-8\'))';

			}else if(preg_match('/^urldecode\:([a-zA-Z0-9\-]+)$/',$value,$result)){

				$tmp1='';
				$result[1] = str_replace('-','',mb_strtolower($result[1]));
				if($result[1]=='shiftjis'){
					$tmp1='Shift-JIS';
				}else if($result[1]=='eucjp'){
					$tmp1='EUC-JP';
				}else{
					$tmp1='UTF-8';
				}

				$front = 'urldecode(mb_convert_encoding('.$front;
				$back = $back.', \''.$tmp1.'\',\'utf-8\'))';

			}else if(preg_match('/^str\_replace\:([^\:]+)\:*(.*)$/u',$value,$result)){

				//関数を実行されないようにシングルクォーテーションをエスケープ
				$result[1] = str_replace('\'','\\\'',$result[1]);
				$result[2] = str_replace('\'','\\\'',$result[2]);

				$tmp_arr1 = array();$tmp1='';
				$tmp_arr2 = array();$tmp2='';

				$tmp_arr1 = explode(',',$result[1]);
				$tmp_arr2 = explode(',',$result[2]);

				//特殊文字変換
				$bf = array();     $af = array();
				$bf[0] = '&nbsp;'; $af[0] = ' ';//スペース
				$bf[1] = '&#x2c;'; $af[1] = ',';//カンマ
				$bf[2] = '&#x7c;'; $af[2] = '|';//パイプ
				$bf[3] = '&#x3a;'; $af[3] = ':';//コロン
				foreach($tmp_arr1 as $v) $tmp1 .= "'".str_replace($bf,$af,$v)."',";
				foreach($tmp_arr2 as $v) $tmp2 .= "'".str_replace($bf,$af,$v)."',";

				$tmp1 = substr($tmp1, 0, -1);
				$tmp2 = substr($tmp2, 0, -1);

				$front = 'str_replace(array('.$tmp1.'),array('.$tmp2.'),' . $front;
				$back = $back.')';

			}else if(preg_match('/^date\_format\:"([^\"]+)":*"*([\+\-\, 0-9a-z]*)"*$/u',$value,$result)){

				//関数を実行されないようにシングルクォーテーションをエスケープ
				$tmp1 = '';$tmp1 = str_replace('\'','\\\'',$result[1]);
				$tmp2 = '';$tmp2 = str_replace('\'','\\\'',$result[2]);

				$front = 'Mini::date_calc(\''.$tmp1.'\','.$front;
				$back = $back.',\''.$tmp2.'\')';

			}else if(preg_match('/^check\_digit\:([a-zA-Z0-9\-]+)$/',$value,$result)){

				$tmp1='';
				$result[1] = str_replace('-','',mb_strtolower($result[1]));
				if($result[1]=='modulus16'){
					//モジュラス16
					$tmp1='modulus16';
				}else if($result[1]=='modulus43'){
					//モジュラス43
					$tmp1='modulus43';
				}else{
					//モジュラス10ウェイト3-1
					$tmp1='modulus10w31';
				}

				$front = 'check_digit('.$front;
				$back = $back.', \''.$tmp1.'\')';

			}

		}

		return array($front,$back);

	}

	static public function number($op,$str){
		if($op==='number_format'){
			$str = preg_replace_callback(
							'/([0-9]{4,})/su',
							function($matches){
								return number_format($matches[1]);
							},
							$str
						 );
		}else if($op==='floor'){
			$str = preg_replace_callback(
							'/(0|[1-9]\d*)(\.\d+|)/su',
							function($matches){
								return floor($matches[0]);
							},
							$str
						 );
		}else if($op==='ceil'){
			$str = preg_replace_callback(
							'/(0|[1-9]\d*)(\.\d+|)/su',
							function($matches){
								return ceil($matches[0]);
							},
							$str
						 );
		}else if($op==='round'){
			$str = preg_replace_callback(
							'/(0|[1-9]\d*)(\.\d+|)/su',
							function($matches){
								return round($matches[0]);
							},
							$str
						 );
		}
		return $str;
	}

	//拡張関数date_calc
	//2014-03-31 + 1 month が 2014-05-01 になってしまうバグを回避する関数
	static public function date_calc($format="Y-m-d H:i:s",$date_str="",$calc_str=""){

		//計算指定を算出（あとでカンマのエスケープメソッドも入れる必要あるかも）
		$arr = array();
		$arr = explode(",",$calc_str);

	  //入力がない場合は現在
	  $date_str = $date_str ?: date("Y-m-d H:i:s");

	  if(empty($arr)){

	    //計算指定がない場合は終了
	    return date($format,strtotime($date_str));

	  }else{

			//計算
	    $t = array();$tmp = "";
			$tmp = date("Y,m,d,H,i,s",strtotime($date_str));
			list(
				$t['Y'],//年。4 桁の数字。
				$t['n'],//月。数字。先頭にゼロをつけない。
				$t['j'],//日。先頭にゼロをつけない。
				$t['H'],//時。数字。24 時間単位。
				$t['i'],//分。先頭にゼロをつける。
				$t['s']//秒。先頭にゼロをつける。
			) = explode(",",$tmp);

	    foreach($arr as $calc){
	      $calc = mb_strtolower($calc);
				$calc = str_replace(' ','',$calc);
	      if(strpos($calc,'month')!==false){
	        //monthがあった場合
	        $t['Y'] = date("Y",strtotime($t['Y'].'-'.$t['n'].'-01 00:00:00'.$calc));
	        $t['n'] = date("n",strtotime($t['Y'].'-'.$t['n'].'-01 00:00:00'.$calc));
	        while(!checkdate($t['n'],$t['j'],$t['Y']) || $t['j'] < 1 ) --$t['j'];
				}else if(strpos($calc,'correctdate')!==false){
					//日付補正指定があった場合
					$calc = (int)str_replace('correctdate','',$calc);
					$t['j'] = $calc;
					while(!checkdate($t['n'],$t['j'],$t['Y']) || $t['j'] < 1 ) --$t['j'];
	      }else{
	        //それ以外
					$tmp = "";
	        $tmp = date("Y,m,d,H,i,s",strtotime($t['Y'].'-'.$t['n'].'-'.$t['j'].' '.$t['H'].':'.$t['i'].':'.$t['s'].' '.$calc));
	        list($t['Y'],$t['n'],$t['j'],$t['H'],$t['i'],$t['s']) = explode(",",$tmp);
	      }
	    }

	    return date($format,strtotime($t['Y'].'-'.$t['n'].'-'.$t['j'].' '.$t['H'].':'.$t['i'].':'.$t['s']));
	  }


	}


	//カスタム関数(uniqueid)
	static public function uniqueid($str){
		//ヘルパークラスが存在する場合に有効な関数
		if(class_exists('Helper')){
			return Helper::assign('default_setting',$str);
		}else{
			return $str;
		}
	}

	//PHPの文法チェック
	function php_lint($str){

		//フォルダ名が決まってなければ決める
		if(!isset($_SESSION['folder'])) $_SESSION['folder'] = md5(mt_rand());

		//ファイル保存
		$this->file_create( $str , 'lint.txt' , _TMP_ROOT_.$_SESSION['folder'] , 'utf-8' , 'utf-8' );

		//文法チェック
		$result = '';
		$result = exec ('php -c '._DATA_ROOT_.'helper/no_error.ini -l '._TMP_ROOT_.$_SESSION['folder'].'/lint.txt',$result);

		//結果出力
		if(strpos($result,'Errors parsing') === false){

			return true;

		}else{

			return false;

		}


	}



}


?>
