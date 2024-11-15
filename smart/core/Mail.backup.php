<?php
/*
使い方は以下の通り
if( ! Mail::create()
 ->name( "送信者名" )
 ->from( "送信元アドレス" )
 ->to( "送信先アドレス" )
 ->title( "件名" )
 ->body( "本文" )
 ->header( "追加ヘッダ" )
 ->param( "追加パラメータ" )
 ->cc( array("アドレス１","アドレス２") )
 ->bcc( array("アドレス１","アドレス２") )
 ->files( array( "添付ファイル表示名"=>"添付ファイルパス" ) )
 ->send()
){Session::error('メールの送信に失敗しました。');return;}
*/
class Mail{

	const ENCODING   = "UTF-8";

	private $name    = "";
	private $from    = "";
	private $to     = "";
	private $title   = "";
	private $body    = "";
	private $cc     = array();
	private $bcc    = array();
	private $header   = "";
	private $param   = "";
	private $files   = array();
	private $boundary  = "";
	private $viewVars = array();

	public function reset(){
		$this->name    = "";
		$this->from    = "";
		$this->to     = "";
		$this->title   = "";
		$this->body    = "";
		$this->cc     = array();
		$this->bcc    = array();
		$this->header   = "";
		$this->param   = "";
		$this->files   = array();
		$this->boundary  = "";
		$this->viewVars = array();
	}

	/* コンストラクタ
	--------------------------------------------------------------------------*/
	public function construct( $to="", $subject="", $message="", $additional_headers="", $additional_parameters="" ){

		$this->to    = $to;
		$this->title  = $subject;
		$this->body   = $message;
		$this->header  = $additional_headers;
		$this->param  = $additional_parameters;

	}

	/* 生成
	--------------------------------------------------------------------------*/
	public static function create( $to="", $subject="", $message="", $additional_headers="", $additional_parameters="" ){

		return new self( $to, $subject, $message, $additional_headers, $additional_parameters );

	}

	/* メールアドレスの形式チェック
	--------------------------------------------------------------------------*/
	public static function mailAddressValidation( $addr ){

		if( strlen( $addr ) <=0 ) return false;
		if(_DEBUG_){
			//デバッグの場合エイリアスを許可
			$result = preg_match( "/^([a-zA-Z0-9])+([\+a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $addr );
		}else{
			//↓エイリアス許可してる
			$result = preg_match( "/^([a-zA-Z0-9])+([\+a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $addr );
			//$result = preg_match( "/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $addr );
		}
		if( $result === false || $result === 0 ) return false;
		return true;

	}

	/* 送信者名
	--------------------------------------------------------------------------*/
	public function name( $name=null ){

		if( is_null( $name ) ){
			return $this->name;
		}else{
			$this->name = $name;
			return $this;
		}
	}

	/* 送信元アドレス
	--------------------------------------------------------------------------*/
	public function from( $addr=null, $is_valid=true ){
		if( is_null( $addr ) ){
			return $this->from;
		}else{

		if( $is_valid === true ){
			if( self::mailAddressValidation( $addr ) === false ){
				throw new Exception( 'Format of the e-mail address is invalid' );
				return false;
			}
		}

		$this->from = $addr;
			return $this;
		}
	}

	/* 送信先アドレス
	--------------------------------------------------------------------------*/
	public function to($addr=null,$is_valid=true){
		if(is_null($addr)){
			return $this->to;
		}else{
			if(
				!defined('_MAIL_DEBUG_') ||
				_MAIL_DEBUG_ === ''
			){
				if($is_valid===true){
					if( self::mailAddressValidation($addr)===false){
						throw new Exception('Format of the e-mail address is invalid');
						return false;
					}
				}
				$this->to = $addr;
				return $this;
			}else{
				//デバッグの場合
				$this->to = _MAIL_DEBUG_;
				return $this;
			}
		}
	}

	/* 件名
	--------------------------------------------------------------------------*/
	public function title( $title=null ){
		if( is_null( $title ) ){
			return $this->title;
		}else{
			if(
				!defined('_MAIL_DEBUG_') ||
				_MAIL_DEBUG_ === ''
			){
				$this->title = $title;
			}else{
				//デバッグの場合
				$this->title = 'DEBUG)'.$title;
			}
			return $this;
		}
	}

	/* 変数のセット要求
	--------------------------------------------------------------------------*/
	public function set($one, $two = null) {

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
			return $this;
		}
		$this->viewVars = $data + $this->viewVars;
		return $this;

	}

	/* テンプレートの選択
	--------------------------------------------------------------------------*/
	public function template($template = null) {

		if( !is_null( $template ) ){

			$path = str_replace('.','',$template);

			if(file_exists(_ROOT_.'src/Template/Email/'.$path.'.ctp')){

				extract($this->viewVars,EXTR_SKIP);

				ob_start();
				include(_ROOT_.'src/Template/Email/'.$path.'.ctp');
				$this->body = ob_get_contents();
				ob_end_clean();

			}
		}

		return $this;
	}

	/* 本文
	--------------------------------------------------------------------------*/
	public function body( $body=null ){

		if( is_null( $body ) ){
			return $this->body;
		}else{
			$this->body = $body;
			return $this;
		}
	}

	/* ヘッダ
	--------------------------------------------------------------------------*/
	public function header( $header=null ){
		if( is_null( $header ) ){
			return $this->header;
		}else{
			$this->header = $header;
			return $this;
		}
	}

	/* パラメータ(オプション)
	--------------------------------------------------------------------------*/
	public function param( $param=null )
	{
	if( is_null( $param ) ){
	return $this->param;
	}
	else{
	$this->param = $param;
	return $this;
	}
	}

	/* 添付ファイル array("ファイル名"=>"ファイルパス")
	--------------------------------------------------------------------------*/
	public function files( $files=null, $is_valid=true )
	{
	if( is_null( $files ) ){
	return $this->files;
	}
	else{

	if( $is_valid === true ){
	foreach( $files as $key => $path ){
	if( ! file_exists( $path ) ){
	throw new Exception( 'The specified file does not exist' );
	return false;
	}
	}
	}

	$this->files = $files;
	return $this;
	}
	}

	/* CC array(addr,addr)
	--------------------------------------------------------------------------*/
	public function cc( $cc=null, $is_valid=true ){
		if(is_null($cc)){
			return $this->cc;
		}else{
			if(
				!defined('_MAIL_DEBUG_') ||
				_MAIL_DEBUG_ === ''
			){
				if( $is_valid === true ){
					foreach( $cc as $index => $addr ){
						if( self::mailAddressValidation( $addr ) === false ){
							throw new Exception( 'Format of the e-mail address is invalid' );
							return false;
						}
					}
				}
				$this->cc = $cc;
				return $this;
			}else{
				//デバッグの場合
				$this->cc = array();
				return $this;
			}
		}
	}

	/* BCC array(addr,addr)
	--------------------------------------------------------------------------*/
	public function bcc( $bcc=null, $is_valid=true ){
		if(is_null($bcc)){
			return $this->bcc;
		}else{
			if(
				!defined('_MAIL_DEBUG_') ||
				_MAIL_DEBUG_ === ''
			){
				if( $is_valid === true ){
					foreach( $bcc as $index => $addr ){
						if( self::mailAddressValidation( $addr ) === false ){
							throw new Exception( 'Format of the e-mail address is invalid' );
							return false;
						}
					}
				}
				$this->bcc = $bcc;
				return $this;
			}else{
				//デバッグの場合
				$this->bcc = array();
				return $this;
			}
		}
	}

	/* boundary
	--------------------------------------------------------------------------*/
	public function boundary( $boundary=null )
	{
	if( is_null( $boundary ) ){
	if( strlen( $this->boundary ) <= 0 ){
	$this->boundary = md5( uniqid( rand(), true ) );
	}
	return $this->boundary;
	}
	else{
	$this->boundary = $boundary;
	return $this;
	}
	}

	/* 送信
	--------------------------------------------------------------------------*/
	public function send(){
		mb_language( "ja" );
		mb_internal_encoding( self::ENCODING );
		$rcd = mail(
			$this->to(),
			mb_encode_mimeheader( $this->title(), "ISO-2022-JP", "B" ),
			$this->buildBody(),
			$this->buildHeader(),
			$this->buildParam()
		);
		$this->reset();
		return $rcd;
	}

	/* 本文の構築
	--------------------------------------------------------------------------*/
	private function buildBody()
	{
	$body = mb_convert_encoding( $this->body(), 'JIS', self::ENCODING );
	if( count( $this->files() ) <= 0 ){
	return $body;
	}
	else{
	return $this->appendFiles( $body );
	}
	}

	/* ファイルを添付
	--------------------------------------------------------------------------*/
	private function appendFiles( $body )
	{
	$in_file_body = "";
	$in_file_body .= "--" . $this->boundary() . "\n";
	$in_file_body .= "Content-Type: text/plain; charset=\"iso-2022-jp\"\n";
	$in_file_body .= "Content-Transfer-Encoding: 7bit\n";
	$in_file_body .= "\n";
	$in_file_body .= $body . "\n";

	foreach( $this->files() as $file_name => $file_path ){
	if( ! file_exists( $file_path ) ){
	trigger_error( 'I was not able to attach the file', E_USER_NOTICE );
	continue;
	}

	$info   = pathinfo( $file_path );
	$content  = "application/octet-stream";
	$filename  = mb_encode_mimeheader( $file_name, "ISO-2022-JP", "B" );
	$in_file_body .= "\n";
	$in_file_body .= "--" . $this->boundary() . "\n";
	$in_file_body .= "Content-Type: " . $content . "; charset=\"iso-2022-jp\" name=\"" . $filename . "\"\n";
	$in_file_body .= "Content-Transfer-Encoding: base64\n";
	$in_file_body .= "Content-Disposition: attachment; filename=\"" . $filename . "\"\n";
	$in_file_body .= "\n";
	$in_file_body .= chunk_split( base64_encode( file_get_contents( $file_path ) ) ) . "\n";
	}
	$in_file_body .= '--' . $this->boundary() . '--';
	return $in_file_body;
	}

	/* Fromの構築
	--------------------------------------------------------------------------*/
	private function buildFrom()
	{
	$from = "";
	if( strlen( $this->name() ) <= 0 ){
	$from .= $this->from();
	}
	else{
	$from .= mb_encode_mimeheader( $this->name(), "ISO-2022-JP", "B" ) . " <" . $this->from() . ">";
	}
	return $from;
	}

	/* Ccの構築
	--------------------------------------------------------------------------*/
	private function buildCc(){
		$cc = "";
		if( count( $this->cc() ) > 0 ){
			$cc .= "Cc: " . implode( ",", $this->cc() ) . "\n";
		}
		return $cc;
	}


	/* Bccの構築
	--------------------------------------------------------------------------*/
	private function buildBcc(){
		$bcc = "";
		if( count( $this->bcc() ) > 0 ){
			$bcc .= "Bcc: " . implode( ",", $this->bcc() ) . "\n";
		}
		return $bcc;
	}

	/* ヘッダの構築
	--------------------------------------------------------------------------*/
	private function buildHeader(){
		$header = "";
		// デフォルト
		$header .= "X-Mailer: PHP5\n";
		$header .= "From: " . $this->buildFrom() . "\n";
		$header .= "Return-Path: " . $this->buildFrom() . "\n";
		$header .= $this->buildCc();
		$header .= $this->buildBcc();
		$header .= "MIME-Version: 1.0\n";
		$header .= "Content-Transfer-Encoding: 7bit\n";
		if(count($this->files())<=0){
			$header .= "Content-Type: text/plain; charset=\"iso-2022-jp\"\n";
		}else{
			$header .= "Content-Type: multipart/mixed; boundary=\"" . $this->boundary() . "\"\n";
		}
		// ユーザ定義
		$header .= $this->header();
		return $header;
	}

	/* パラメータ構築
	--------------------------------------------------------------------------*/
	private function buildParam()
	{
	$param = "";

	// デフォルト
	$param .= "-f " . $this->from();

	// ユーザ定義
	$param .= $this->param();

	return $param;
	}
}

?>
