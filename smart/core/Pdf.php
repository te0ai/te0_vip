<?php
/* -------------------
CentOS7 x86_64
$ arch
x86_64 <<< OK!
$ yum -y install libXrender libXext xorg-x11-fonts-75dpi xorg-x11-fonts-Type1 openssl openssl-devel ipa-gothic-fonts ipa-mincho-fonts ipa-pgothic-fonts ipa-pmincho-fonts
$ wget --no-check-certificate https://dev.wiredxeco.com/packs/wkhtmltopdf/wkhtmltox-0.12.6-1.centos7.x86_64.rpm
$ rpm -Uvh wkhtmltox-0.12.6-1.centos7.x86_64.rpm
$ wkhtmltopdf -V
---------------------- */

class Pdf {

	//基本機能------------------------
	static public $dir = '';
	static public $count = 0;

	//リセット
	static public function reset(){
		//初期化
		self::$dir = '';
		self::$count = '';
	}

	//スタート
	static public function first($arr=array()){
		//ない場合はフォルダを製作
		if(self::$dir===''){
			self::$dir = md5(uniqid(rand(),1));
		}
		return $arr;
	}

	//制作
	static public function write($arr){

		//初期設定
		$arr = self::first($arr);

		//テンプレート
		$html = "";
    $html = self::template($arr['template'],$arr['data']);

		//作業用フォルダがない場合は制作
		if(!is_dir(_TMP_ROOT_.self::$dir)) mkdir(_TMP_ROOT_.self::$dir,0777,true);

		//ファイルを一時保存
		file_put_contents(_TMP_ROOT_.self::$dir.'/'.self::$count.'.html',$html);

		//カウントアップ
		++self::$count;

	}

	//書き出し
	static public function out($arr){

		$exec = '';
		for($i=0;$i<self::$count;++$i){
			if(is_file(_TMP_ROOT_.self::$dir.'/'.$i.'.html')){
				$exec .= escapeshellcmd(_TMP_ROOT_.self::$dir.'/'.$i.'.html ');
			}
		}
		if($exec!==''){
			$exec_head = '';
			$exec_head .= 'wkhtmltopdf ';
			$exec_head .= '--print-media-type ';
			$exec_head .= '--disable-smart-shrinking ';
			$exec_head .= '--margin-bottom 0 ';
			$exec_head .= '--margin-left 0 ';
			$exec_head .= '--margin-right 0 ';
			$exec_head .= '--margin-top 0 ';
			if( isset($arr['orientation']) && $arr['orientation']==='landscape'){
				$exec_head .= '--orientation landscape ';
			}else{
				$exec_head .= '--orientation portrait ';
			}
			$exec_head .= '--page-size A4 ';
			$exec .= escapeshellcmd(_TMP_ROOT_.self::$dir.'/'.$arr['title'].'.pdf');
			$rcd = exec($exec_head.$exec);
			if( isset($arr['path']) && $arr['path']==='absolute'){
				$home = _TMP_ROOT_.self::$dir.'/'.$arr['title'].'.pdf';
			}else{
				$home = _TMP_HOME_.self::$dir.'/'.$arr['title'].'.pdf';
			}
			//初期化
			self::reset();
			return $home;
		}else{
			//初期化
			self::reset();
			return false;
		}

	}

	public static function template($template,$viewVars){
    $body = "";
    $path = str_replace('.','',$template);
    if(file_exists(_ROOT_.'src/Template/Doc/'.$path.'.ctp')){
      extract($viewVars,EXTR_SKIP);
      ob_start();
      include(_ROOT_.'src/Template/Doc/'.$path.'.ctp');
      $body = ob_get_contents();
      ob_end_clean();
    }else if(_DEBUG_){
			$body = '(DEBUG MODE)fatal: not file exsists "'._ROOT_.'src/Template/Doc/'.$path.'.ctp"';
		}
		return $body;
	}





}

?>
