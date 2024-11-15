<?php
//wrap st
echo "<div id=\"wrap_".htmlspecialchars($_['id'])."\" ";
//wrap -> class
echo "class=\"";
echo isset($_['input_hidden']) && (string)$_['input_hidden'] === '1' ? "hide " : "";
echo h(eximplode($_['input_class'],',',' ','wrap_'))."\">\n";
  //input-group
  echo "<div class=\"input-group\">\n";
    //prepend text
    if(isset($_['decoration']['prepend'])){
      echo "<div class=\"input-group-prepend\">\n";
      echo "<span id=\"dec_prepend_".h($_['id'],true)."\" class=\"input-group-text\">".h($_['decoration']['prepend'],true)."</span>\n";
      echo "</div>\n";
    }
    //input
    echo " <input ";
    //input -> id
    echo isset($_['id']) ? "id=\"".htmlspecialchars($_['id'])."\" " : "";
    //input -> name
    echo isset($_['name']) ? "name=\"".htmlspecialchars($_['name'])."\" " : "";
    //input -> title
    echo isset($_['name']) ? "title=\"".htmlspecialchars($_['name'])."\" " : "";
    //input -> type
    echo "type=\"text\" ";
    //input -> class
    echo "class=\"form-control form-check ";
    if((string)$_['not_edit_flg']==='1'){
      echo "form-disabled ";
    }else if((string)$_['not_edit_flg']==='2'){
      echo "form-not-update ";
      if(Session::get('thread-id') !== '0') echo "form-disabled ";
    }
    echo h($_['class'],true)."\" ";
    //input -> value
    echo "value=\"". h($_['default_setting'],true) ."\" ";
    //input -> data-required-flg
    echo "data-required-flg=\"". h($_['required_flg'],true) ."\" ";
    //input -> data-regex
    echo "data-regex=\"". h($_['regex'],true) ."\" ";
    //input -> data-regex-error
    echo "data-regex-error=\"". h($_['regex_error'],true) ."\" ";
    //input -> placeholder
    echo isset($_['decoration']['placeholder']) ? "placeholder=\"".htmlspecialchars($_['decoration']['placeholder'])."\" " : "";
    echo ">\n";
    //append text
    if(isset($_['decoration']['prepend'])){
      echo "<div class=\"input-group-prepend\">\n";
      echo "<span id=\"dec_prepend_".h($_['id'],true)."\" class=\"input-group-text\">".h($_['decoration']['prepend'],true)."</span>\n";
      echo "</div>\n";
    }
  //input-group en
  echo "</div>\n";
  echo "<div id=\"error_".h($_['id'],true)."\" class=\"bg-danger text-white form-error\"></div>\n";
//wrap en
echo "</div>\n";
?>

<?php
/*
<div id="wrap_<?php h($_['id']) ?>" class="form-group">
  <div class="input-group">
    <?php
      if(isset($_['decoration']['front'])) echo "<span id=\"dec_front_".h($_['id'],true)."\" class=\"input-group-addon\">".h($_['decoration']['front'],true)."</span>\n";
      //カンマなし本体
      echo "<input ";
      echo "type=\"number\" ";
      echo "id=\"".h($_['id'],true)."\" ";
      echo "class=\"form-control form-numeric-input hide ";
      if((string)$_['not_edit_flg']==='1'){
        echo "form-disabled ";
      }else if((string)$_['not_edit_flg']==='2' && Session::get('thread-id') !== '0' ){
        echo "form-disabled ";
      }
      echo h($_['class'],true)."\" ";
      echo "name=\"".h($_['name'],true)."\" ";
      echo "value=\"".h($_['default_setting'],true)."\" ";
      echo "data-required-flg=\"".h($_['required_flg'],true)."\" ";
      echo "data-regex=\"".h($_['regex'],true)."\" ";
      echo "data-regex-error=\"".h($_['regex_error'],true)."\" ";
      if((string)$_['minlength'] !== '') echo "min =\"".h($_['minlength'],true)."\" ";
      if((string)$_['maxlength'] !== '0') echo "max =\"".h($_['maxlength'],true)."\" ";
      if((string)$_['step'] !== '0') echo "step =\"".h($_['step'],true)."\" ";
      echo "/>";
      //カンマアリ表示用
      echo "<div ";
      echo "id=\"numeric_".h($_['id'],true)."\" ";
      echo "class=\"form-control form-numeric-format ";
      if((string)$_['not_edit_flg']==='1'){
        echo "form-disabled ";
      }else if((string)$_['not_edit_flg']==='2' && Session::get('thread-id') !== '0' ){
        echo "form-disabled ";
      }
      echo "\" ";
      echo "data-id=\"".h($_['id'],true)."\" ";
      echo ">";
      if(is_numeric($_['default_setting'])){
        if(strpos($_['default_setting'],'.')===false){
          echo number_format($_['default_setting']);
        }else{
          $decimalnotnumberformat = explode('.',$_['default_setting']);
          echo number_format($decimalnotnumberformat[0]).'.'.$decimalnotnumberformat[1];
        }
      }else{
        echo $_['default_setting'];
      }
      echo "</div>";
      if(isset($_['decoration']['back'])) echo "<span id=\"dec_back_".h($_['id'],true)."\" class=\"input-group-addon\">".h($_['decoration']['back'],true)."</span>\n";
    ?>
    <span id="button_<?php h($_['id']) ?>" class="input-group-btn">
    </span>
  </div>
  <div id="error_<?php h($_['id']) ?>"><?php h($_['error']) ?></div>
</div>
*/
?>
