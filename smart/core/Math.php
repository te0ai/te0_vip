<?php
/*------------------------------

小数（浮動小数点数型）の計算ズレ解消クラス
Mathクラス
製作者：株式会社ワイアード＆エコ

echo '税込'.ceil(30000 * 1.08).'円<br>';
$math = new Math();
echo '税込'.ceil(Math::calc("30000 * (1 + 0.08)")).'円<br>';

------------------------------*/
class Math {

  static public $illegalString = array();
  static public $exps = array();

  static public function calc($str){

    //括弧削除の正規表現
    $numberWithParentheses = "\((-?(?:\d+(?:\.\d+)?|\.\d+))\)";
    //除算または乗算の正規表現
    $multiplyOrDivide = "-?(?:\d+(?:\.\d+)?|\.\d+)(?:[*\/]-?(?:\d+(?:\.\d+)?|\.\d+))+";
    //加算または減算の正規表現
    $addOrSubtract = "(-?(?:\d+(?:\.\d+)?|\.\d+))([+-])(-?(?:\d+(?:\.\d+)?|\.\d+))";

    //空白の削除
    $str = str_replace(' ','',$str);

    //式の評価
    if(preg_match('/^(?!-?(?:\d+(?:\.\d+)?|\.\d+)|\()[\s\S]/u',$str,$illegalString)){
      exit('An expression starts with an unexpected token: ' . htmlspecialchars($str));
    }
    if(preg_match('/[^\d)]$/u',$str,$illegalString)){
      exit('An expression ends with an unexpected token: ' . htmlspecialchars($str));
    }
    if(preg_match('/\d*(?:\.\d*){2,}/u',$str,$illegalString)){
      exit('Illegal number: ' . htmlspecialchars($str));
    }

    do {

      //括弧の削除
      $str = preg_replace_callback('/'.$numberWithParentheses.'/',function($match){
        return $match[1];
      },$str);

      //除算または乗算の計算
      $str = preg_replace_callback('/'.$multiplyOrDivide.'/',function($match){
        return self::calcMultiplyOrDivide($match[0]);
      },$str);

      //加算または減算の計算
      $str = preg_replace_callback('/'.$addOrSubtract.'/',function($match){
        return self::calcAddOrSubtract($match[0]);
      },$str);

    }while(
      preg_match('/'.$numberWithParentheses.'/',$str) ||
      preg_match('/'.$multiplyOrDivide.'/',$str) ||
      preg_match('/'.$addOrSubtract.'/',$str)
    );

    //数値の評価
    if(preg_match('/[^\d.+-]/u',$str,$illegalString)){
      exit('An expression with an unexpected token: ' . htmlspecialchars($str));
    }
    return $str;
  }

  //加算または減算
  static public function calcAddOrSubtract($str){

    //初期化
    self::$exps = array();

    $addOrSubtract = "\+(-?(?:\d+(?:\.\d+)?|\.\d+))|\-(-?(?:\d+(?:\.\d+)?|\.\d+))|(-?(?:\d+(?:\.\d+)?|\.\d+))";
    $exps = array();
    $num = 0;

    $str = preg_replace_callback('/'.$addOrSubtract.'/',function($token){
      if ($token[1] || (string)$token[1] === '0') {
        self::$exps['add'][] = array('+',$token[1]);
      } else if ($token[2] || (string)$token[2] === '0') {
        self::$exps['substruct'][] = array('-',$token[2]);
      } else if ( $token[3] || (string)$token[3] === '0') {
        self::$exps['number'] = $token[3];
      } else {
        exit('Unknown exception.');
      }
      return '';
    },$str);

    if( isset(self::$exps['add']) && isset(self::$exps['substruct']) ){
      $exps = array_merge(self::$exps['add'],self::$exps['substruct']);
    }else if(isset(self::$exps['add'])){
      $exps = self::$exps['add'];
    }else if(isset(self::$exps['substruct'])){
      $exps = self::$exps['substruct'];
    }

    $number = self::$exps['number'] ?: 0;
    foreach($exps as $token){
      //echo $number;
      $number = self::calcDyadicOperator('', $number, $token[0], $token[1]);
      //echo $token[0].$token[1].'='.$number.'<br>';
    }

    return $number;

  }

  //除算または乗算
  static public function calcMultiplyOrDivide($str){

    //初期化
    self::$exps = array();

    $multiplyOrDivide = "\*(-?(?:\d+(?:\.\d+)?|\.\d+))|\/(-?(?:\d+(?:\.\d+)?|\.\d+))|(-?(?:\d+(?:\.\d+)?|\.\d+))";
    $exps = array();
    $num = 0;

    $str = preg_replace_callback('/'.$multiplyOrDivide.'/',function($token){
      if ($token[1] || (string)$token[1] === '0') {
        self::$exps['multiply'][] = array('*',$token[1]);
      } else if ($token[2] || (string)$token[2] === '0') {
        self::$exps['divide'][] = array('/',$token[2]);
      } else if ($token[3] || (string)$token[3] === '0') {
        self::$exps['number'] = $token[3];
      } else {
        exit('Unknown exception');
      }
      return '';
    },$str);

    //print_r(self::$exps);
    //echo '<br>';

    if( isset(self::$exps['multiply']) && isset(self::$exps['divide']) ){
      $exps = array_merge(self::$exps['multiply'],self::$exps['divide']);// multiply before divide
    }else if(isset(self::$exps['multiply'])){
      $exps = self::$exps['multiply'];
    }else if(isset(self::$exps['divide'])){
      $exps = self::$exps['divide'];
    }

    $number = self::$exps['number'] ?: 0;
    foreach($exps as $token){
      //echo $number;
      $number = self::calcDyadicOperator('', $number, $token[0], $token[1]);
      //echo $token[0].$token[1].'='.$number.'<br>';
    }

    return $number;

  }

  //計算コア
  static public function calcDyadicOperator($matched,$number1,$operator,$number2){
    switch ($operator) {
      case '+':
        $powerNumber1 = pow(10, max(self::getDecimalPartLength($number1), self::getDecimalPartLength($number2)));
        $result = ($powerNumber1 * $number1 + $powerNumber1 * $number2) / $powerNumber1;
        break;
      case '-':
        $powerNumber1 = pow(10, max(self::getDecimalPartLength($number1), self::getDecimalPartLength($number2)));
        $result = ($powerNumber1 * $number1 - $powerNumber1 * $number2) / $powerNumber1;
        break;
      case '*':
        $powerNumber1 = pow(10, self::getDecimalPartLength($number1));
        $powerNumber2 = pow(10, self::getDecimalPartLength($number2));
        $result = ($number1 * $powerNumber1) * ($number2 * $powerNumber2) / ($powerNumber1 * $powerNumber2);
        break;
      case '/':
        $powerNumber1 = pow(10, max(self::getDecimalPartLength($number1), self::getDecimalPartLength($number2)));
        $result = ($number1 * $powerNumber1) / ($number2 * $powerNumber1);
        break;
      default:
        exit('expression: ' + $number1 + $operator + $number2);
    }
    return $result;
  }

  //小数点の位置計算
  static public function getDecimalPartLength ($numberString) {
    $result = explode('.',$numberString);
    return isset($result[1]) ? strlen($result[1]) : 0;
  }

}

?>
