<?php
class commonM extends coreM{

  public static function ren($status='',$message=''){
    if(!_DEBUG_){
      return array(
        'status' => $status,
        'message' => $message,
      );
    }else{
      return array(
        'status' => $status,
        'message' => $message,
        'debug' => "場所：".$_SERVER['PHP_SELF'].'('.__LINE__.')',
      );
    }
  }

}
?>
