<?php
if(isset($_SERVER['QUERY_STRING'])&& preg_match('/^[a-zA-Z0-9=\/&\_\-]+$/',$_SERVER['QUERY_STRING'])){
  header('Content-Type: text/css; charset=utf-8');
  echo '@charset "UTF-8";'. PHP_EOL;
  //コンフィグファイル読み込み
  include_once('../src/config/default.php');
  //クエリ文字列をパース
  $result = array();
  parse_str($_SERVER['QUERY_STRING'], $result);
  $exp = explode('/', $result['page']);
  $file = _ROOT_ . 'src/Cv/' . $exp[0];
  if (isset($exp[1]) && $exp[0] !== 'index') $file .= '/' . $exp[1];
  $file .= '.css';
  if (isset($result['page']) && is_file($file)) {
    include_once($file);
  }
}else{
  header("HTTP/1.0 404 Not Found");
  echo "CSS not found.";
}

?>
