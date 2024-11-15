<?php

class Json{

	//JSONOUTPUT
	static public function input(){

		$json = file_get_contents('php://input');

		//デコード
		$input = array();
		if(!$input = json_decode($json,true)) self::error(400,'E00003','JSONデータのパースに失敗しました。');

		return $input;

	}

	//JSONheaders
	static public function headers(){

		$headers = getallheaders();
		if(!$headers) self::error(400,'E00003','JSONデータのヘッダ取得に失敗しました。');

		return $headers;

	}



	//JSONOUTPUT
	static public function output($out,$arr=array()){

		$output = array();
		foreach($arr as $k => $v){
			$output[$k] = $v;
		}
		if(!isset($output['result'])) $output['result'] = 'success';
		$output['data'] = $out;

		//オプション
		$options = JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_PRETTY_PRINT;

		//ステータスヘッダ
		header('Content-Type: application/json', true, 200);

		echo json_encode($output,$options);
		exit();

	}

	//JSONエラー送出
	static public function error($num,$error_code='',$error_msg=''){

		if(!is_numeric($error_code)&&$error_msg===''){
			$error_msg = $error_code;
			$error_code = $num;
		}

		//オプション
		$options = JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_PRETTY_PRINT;

		//ステータスヘッダ
		header('Content-Type: application/json', true, $num);

		$status = array();
		$status[200] = 'OK';
		$status[201] = 'Created';
		$status[400] = 'Bad Request';
		$status[403] = 'Forbidden';
		$status[404] = 'Not Found';

		//失敗
		$out = array();
		$out['result'] = 'error';
		$out['error']['code'] = $num;
		$out['error']['name'] = $status[$num];
		$out['error']['ecode'] = $error_code;
		$out['error']['message'] = $error_msg;
		//if(_DEBUG_) $out['error']['debug_data'] = debug_backtrace();

		echo json_encode($out,$options);
		exit();


	}

	//deserializeArray
	static public function deserializeArray($arr){
		$is_duplicate = array();
		foreach((array)$arr as $v){
			if(isset($is_duplicate[$v['name']])){
				$is_duplicate[$v['name']] = 1;
			}else{
				$is_duplicate[$v['name']] = 0;
			}
		}
		$arr2 = array();
		foreach((array)$arr as $v){
			if($is_duplicate[$v['name']]===1){
				$arr2[$v['name']][] = $v['value'];
			}else{
				$arr2[$v['name']] = $v['value'];
			}
		}
		return $arr2;
	}


}
