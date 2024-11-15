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
    //select st
    echo " <select ";
    //select -> id
    echo isset($_['id']) ? "id=\"".htmlspecialchars($_['id'])."\" " : "";
    //select -> name
    echo isset($_['name']) ? "name=\"".htmlspecialchars($_['name'])."\" " : "";
    //select -> type
    echo "type=\"text\" ";
    //select -> class
    echo "class=\"form-control form-check ";
    if((string)$_['not_edit_flg']==='1'){
      echo "form-disabled ";
    }else if((string)$_['not_edit_flg']==='2'){
      echo "form-not-update ";
      if(Session::get('thread-id') !== '0') echo "form-disabled ";
    }
    echo h($_['class'],true)."\" ";
    //select -> data-required-flg
    echo "data-required-flg=\"". h($_['required_flg'],true) ."\" ";
    //select -> style
    echo "style=\"min-width:10rem;\" ";
    echo ">\n";
      //select -> option
      echo "<option value=\"\" >---</option>\n";
      if( isset($_['option']) && is_array($_['option']) ){
      	foreach($_['option'] as $key => $value){
      		if((string)$key !== (string)$_['default_setting']){
      			echo "\t<option value=\"{$key}\" >{$value}</option>".PHP_EOL;
      		}else{
      			echo "\t<option value=\"{$key}\" selected=\"selected\" >{$value}</option>".PHP_EOL;
      		}
      	}
      }
    //select en
    echo "</select>\n";
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
