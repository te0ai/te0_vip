<div id="wrap_<?php h($_['id']) ?>" class="custom-control custom-checkbox">
  <?php
  if(is_array($_['default_setting'])) $_['default_setting'] = implode(",",$_['default_setting']);
  if( isset($_['option']) && is_array($_['option']) ){
    if(!empty($_['option'])){
      foreach($_['option'] as $key => $value){
        echo "<div class=\"form-check form-check-inline btn btn-sm\">";
        echo "\t<input ";
        echo "type=\"checkbox\" ";
        echo "id=\"".h($_['id'],true)."_".h($key,true)."\" ";
        echo "name=\"".h($_['name'],true)."[]\" ";
        echo "value=\"".h($key,true)."\" ";
        if(strpos( ','.$_['default_setting'].',' , ','.$key.',' ) !== false) echo "checked=\"checked\" ";
        echo "class=\"custom-control-input\" ";
        echo "/>";
        echo "<label class=\"custom-control-label\" ";
        echo "for=\"".h($_['id'],true)."_".h($key,true)."\" ";
        echo "title=\"".h($key,true)."\" ";
        echo ">";
        echo h($value);
        echo "</label>";
        echo "</div>".PHP_EOL;
    	}
    }else{
      echo "\t<input ";
      echo "type=\"checkbox\" ";
      echo "id=\"".h($_['id'],true)."\" ";
      echo "name=\"".h($_['name'],true)."[]\" ";
      echo "value=\"\" ";
      echo "checked=\"checked\" ";
      echo "class=\"hide\" ";
      echo "/>";
    }
  }
  ?>
  <div id="error_<?php h($_['id']) ?>"><?php h($_['error']) ?></div>
</div>
