<?php
if (isset($_SERVER['QUERY_STRING']) && preg_match('/^[a-zA-Z0-9=\/&\_\-]+$/', $_SERVER['QUERY_STRING'])) {
  header('Content-Type: application/javascript; charset=utf-8');
  //コンフィグファイル読み込み
  include_once('../src/config/default.php');
  //クエリ文字列をパース
  $result = array();
  parse_str($_SERVER['QUERY_STRING'],$result);
  $exp = explode('/', $result['page']);
  $file = _ROOT_ . 'src/Cv/' . $exp[0];
  if (isset($exp[1]) && $exp[0] !== 'index') $file .= '/' . $exp[1];
  $file .= '.js';
  if (isset($result['page']) && is_file($file)) {
    include_once($file);
  }
} else {
  header("HTTP/1.0 404 Not Found");
  echo "JS not found.";
}

//モデルの読み込み
function model_use($arr = NULL)
{
  if (isset($arr) && is_array($arr)) {
    include_once(_ROOT_ . 'smart/core/Model.php');
    include_once(_ROOT_ . 'src/Model/common.php');
    foreach ($arr as $v) {
      $path_arr = array();
      $path = str_replace('.', '', $v);
      $path_arr = explode('/', $path);
      if ($path_arr[0] !== 'index' && file_exists(_ROOT_ . 'src/Model/' . $path_arr[0] . '/common.php')) include_once(_ROOT_ . 'src/Model/' . $path_arr[0] . '/common.php');
      if (file_exists(_ROOT_ . 'src/Model/' . $path . '.php')) {
        include_once(_ROOT_ . 'src/Model/' . $path . '.php');
      }
    }
  }
}
?>