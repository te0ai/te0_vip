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
    echo "class=\"form-control date-week datepicker-default ";
    if((string)$_['not_edit_flg']==='1'){
      echo "form-disabled ";
    }else if((string)$_['not_edit_flg']==='2' && Session::get('thread-id') !== '0' ){
      echo "form-disabled ";
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
    //week
    echo "<div class=\"input-group-prepend\">\n";
    echo "<span id=\"week_".h($_['id'],true)."\" class=\"input-group-text\">--</span>\n";
    echo "</div>\n";
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
    echo "<input ";
    echo "type=\"text\" ";
    echo "id=\"". h($_['id'],true) ."\" ";
    echo "class=\"form-control date-week datepicker-default ";
    if((string)$_['not_edit_flg']==='1'){
      echo "form-disabled ";
    }else if((string)$_['not_edit_flg']==='2' && Session::get('thread-id') !== '0' ){
      echo "form-disabled ";
    }
    echo h($_['class'],true)."\" ";
    echo "name=\"". h($_['name'],true) ."\" ";
    echo "value=\"". h($_['default_setting'],true) ."\" ";
    echo "data-required-flg=\"". h($_['required_flg'],true) ."\" ";
    echo "data-regex=\"". h($_['regex'],true) ."\" ";
    echo "data-regex-error=\"". h($_['regex_error'],true) ."\" ";
    echo "/> ";
    ?>
    <span id="week_<?php h($_['id']) ?>" class="input-group-addon">--</span>
  </div>
  <div id="error_<?php h($_['id']) ?>"><?php h($_['error']) ?></div>
</div>
*/
?>
