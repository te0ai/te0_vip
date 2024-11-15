<?php
class Md{

	public static function parse($text){
		return self::transform($text);
	}

	public static function transform($text){

		$parsed = '';

		//Remove UTF-8 BOM and marker character in input, if present.
		$text = preg_replace('{^\xEF\xBB\xBF|\x1A}', '', $text);

		//Standardize line endings:
		//DOS to Unix and Mac to Unix
		$text = preg_replace('{\r\n?}', "\n", $text);

		//Convert all tabs to spaces.
		$text = preg_replace("{\t}","   ", $text);

		//分解
		$text_arr = array();
		$text_arr = explode("\n",$text);
		foreach($text_arr as $t){
			$parsed .= self::line2parse($t);
		}

		return $parsed;

	}

	public static function line2parse($str){
		if(strpos($str,'##')===0){
			//見出しh2
			$str = '<h2>'.ltrim($str,'#').'</h2>'.'<br />'.PHP_EOL;
		}else if(strpos($str,'#')===0){
			//見出しh1
			$str = '<h1>'.ltrim($str,'#').'</h1>'.'<br />'.PHP_EOL;
		}else{
			$str = $str.'<br />'.PHP_EOL;
		}
		return $str;
	}

}
?>
