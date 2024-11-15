<?php

class Conf{


	public static function read($str){

		if (strpos($str, '.') === false){

			//点が存在しない配列要求

			//ディレクトリトラバーサル
			$str = str_replace('.','',$str);

			$config = array();
			if(file_exists(_ROOT_._SRC_.'config/'.$str.'.php')){
				//まずはユーザーconfig
				include(_ROOT_._SRC_.'config/'.$str.'.php');//include_onceだと二度目空になる
			}else if(file_exists(_ADM_ROOT_.'src/config/'.$str.'.php')){
				//なければadminconfig
				include(_ADM_ROOT_.'src/config/'.$str.'.php');//include_onceだと二度目空になる
			}else if(_DEBUG_){
				$dbg = debug_backtrace();
				echo _ROOT_._SRC_.'config/'.$str.'.php is not exists (class:'.$dbg[1]['class'].',function:'.$dbg[1]['function'].',line:'.$dbg[1]['line'].')';
			}

			return $config[$str];

		}else{


			//点が存在する直接参照

			//直接アクセス
			$str_arr = array();
			$str_arr = explode('.',$str);

			$config = array();
			if(file_exists(_ROOT_._SRC_.'config/'.$str_arr[0].'.php')){
				//まずはユーザーconfig
				include(_ROOT_._SRC_.'config/'.$str_arr[0].'.php');//include_onceだと二度目空になる
			}else if(file_exists(_ADM_ROOT_.'src/config/'.$str_arr[0].'.php')){
				//なければadminconfig
				include(_ADM_ROOT_.'src/config/'.$str_arr[0].'.php');//include_onceだと二度目空になる
			}else if(_DEBUG_){
				$dbg = debug_backtrace();
				echo _ROOT_._SRC_.'config/'.$str.'.php is not exists (class:'.$dbg[1]['class'].',function:'.$dbg[1]['function'].',line:'.$dbg[1]['line'].')';
			}


			foreach ($str_arr as $k => $v){

				if (!isset($config[$v])) {
					return NULL;
				}
				$config = $config[$v];

			}

			return $config;

		}


	}

	public static function get_db_val($coop_id,$config_key,$gid=''){
    $tmp_arr = array();
    $tmp_arr = self::get_db(array(
      'coop_id' => $coop_id,
      'config_key' => $config_key,
      'gid' => $gid,
    ));
    $value = '';
    if($tmp_arr['status']==='success'){
			$value = $tmp_arr['data']['config_value'] ?: $tmp_arr['data']['default_value'];
    }
    return $value;
  }

	public static function get_db_json($coop_id, $config_key, $gid = '')
	{
		$tmp_arr = array();
		$tmp_arr = self::get_db(array(
			'coop_id' => $coop_id,
			'config_key' => $config_key,
			'gid' => $gid,
		));
		$value = '';
		if ($tmp_arr['status'] === 'success') {
			$value = $tmp_arr['data']['config_value'] ?: $tmp_arr['data']['default_value'];
		}
		return json_decode($value, true);
	}

  public static function get_db($arr=array()){

    $gid = !isset($arr['gid']) || $arr['gid'] ==='' ? $_SESSION['auth']['gid'] : $arr['gid'];
	$coop_id = $arr['coop_id'];
	$config_key = $arr['config_key'];

	//データ取得
	$parent_confs = array();
	if (is_file(_ROOT_ . 'src/config/coop_config_parent_' . $coop_id . '.php')) {
		$coop_conf = array();
		$coop_conf = Conf::read('coop_config_parent_' . $coop_id);
		if (isset($coop_conf[$config_key])) {
			$parent_confs = $coop_conf[$config_key];
		} else {
			return array(
				'status' => 'error',
				'message' => '該当のキーは存在しないか削除されています',
			);
		}
	} else {
		return array(
			'status' => 'error',
			'message' => '該当の連携は存在しないか削除されています',
		);
	}
	//DBから取得
	$add_config = array();
	$add_config = My::select(array(
		'TABLE' => 'add_config',
		'WHERE' => array(
			'gid' => $gid,
			'coop_id' => $arr['coop_id'],
			'config_key' => $arr['config_key'],
			'delete' => 0,
		),
		'LIMIT' => 1,
	));
	if ((string)$add_config['count'] === '1') {
		$parent_confs['config_value'] = $add_config['data'][0]['config_value'];
	} else {
		$parent_confs['config_value'] = '';
	}

	return array(
		'status' => 'success',
		'data' => $parent_confs,
	);

  }


}

?>
