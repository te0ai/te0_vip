<?php

class Ic{
	
	//ヘルプ
	public static function help(){
		
		$h = array();
		
		$h['a']['key'] = 'a';
		$h['a']['name'] = '英数字を半角に、半角カナを全角に';
		$h['a']['desc'] = '「全角」英数字を「半角」に変換します。 「半角カタカナ」を「全角カタカナ」に変換します。濁点付きの文字を一文字に変換します。';
		
		$h['g']['key'] = 'g';
		$h['g']['name'] = '全角ひらがなを全角カタカナに変換';
		$h['g']['desc'] = '「全角」ひらがなを「全角」カタカナに変換します。';
		
		$h['l']['key'] = 'l';
		$h['l']['name'] = '英文字を小文字に';
		$h['l']['desc'] = 'アルファベット部分をすべて小文字にして返します｡A ウムラウト (Ä) のような文字は変換されません。';
		
		$h['m']['key'] = 'm';
		$h['m']['name'] = '数字とカンマだけにします';
		$h['m']['desc'] = '0-9,以外の文字を消して返します';
		
		$h['n']['key'] = 'n';
		$h['n']['name'] = '数字だけにします';
		$h['n']['desc'] = '0-9以外の文字を消して返します';
		
		$h['o']['key'] = 'o';
		$h['o']['name'] = '英数字だけにします';
		$h['o']['desc'] = 'A-Za-z0-9以外の文字を消して返します';
		
		$h['p']['key'] = 'p';
		$h['p']['name'] = 'HTMLタグを削除します';
		$h['p']['desc'] = '全ての NUL バイトと HTML および PHP タグを取り除きます。不完全または壊れたタグにより予想以上に多くのテキスト/データが削除される可能性に注意してください。 ';
		
		$h['t']['key'] = 't';
		$h['t']['name'] = '前後の不要な文字を削除します';
		$h['t']['desc'] = '最初および最後から空白文字を取り除き、 取り除かれた文字列を返します。取り除かれる対象となる文字は（通常の空白）（タブ）（リターン）（改行）（NULバイト）（垂直タブ）です。';
		
		
		return $h;
		
		
	}
	
	public static function r($str,$lv){
		
		$vl_arr = array();
		$vl_arr = str_split($lv);
		
		foreach($vl_arr as $v){
			
			$fnc = '';
			$fnc = '_'.$v;
			
			if(method_exists(__CLASS__,$fnc)){
			
				$str = self::$fnc($str);
			
			}
				
		}
		
		return $str;
	
	}
	
	public static function _a($str){
	
		//英数字を半角に、半角カナを全角に
		$str = mb_convert_kana($str,"aKV","UTF-8");
		
		return $str;
	
	}
	
	public static function _g($str){
	
		//ひらがなをカタカナに変換する
		$str = mb_convert_kana($str,"C","UTF-8");
		
		return $str;
	
	}
	
	public static function _l($str){
	
		//小文字にする
		$str = strtolower($str);
		
		return $str;
	
	}
	
	public static function _m($str){
	
		//数字とカンマだけにします
		$str = preg_replace('/[^0-9\,]/su','',$str);
		
		return $str;
	
	}
	
	public static function _n($str){
	
		//数字だけにする
		$str = preg_replace('/[^0-9]/su','',$str);
		
		return $str;
	
	}
	
	public static function _o($str){
	
		//英数字だけにする
		$str = preg_replace('/[^A-Za-z0-9]/su','',$str);
		
		return $str;
	
	}
	
	public static function _p($str){
	
		//HTMLタグを削除します
		$str = strip_tags($str);
		
		return $str;
	
	}
	
	public static function _t($str){
	
		//トリム
		$str = trim($str);
		
		return $str;
	
	}
	
	
	
}

?>