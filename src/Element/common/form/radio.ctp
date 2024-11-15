<div id="wrap_<?php h($_['id']) ?>" class="form-group equalize-width">
  <?php
  if( isset($_['option']) && is_array($_['option']) ){
    $_['option'][''] = 'void';//必ず空のラジオボタンを作る フォームNULL送信対策 & JQUERY選択対策
    foreach($_['option'] as $key => $value){
      if($key!=="" && $value!=="void"){
        echo "<label ";
        echo "class=\"radio-inline nowrap ";
        if((string)$_['not_edit_flg']==='1'){
          echo "form-disabled ";
        }else if((string)$_['not_edit_flg']==='2' && Session::get('thread-id') !== '0' ){
          echo "form-disabled ";
        }
        echo h($_['class'],true)."\" ";
        echo ">";
      }
      echo "<input ";
      echo "type=\"radio\" ";
      echo "id=\"".h($_['id'],true);
      if($key!=='') echo "_".h($key,true);
      echo "\" ";
      echo "name=\"".h($_['name'],true)."\" ";
      echo "value=\"".h($key,true)."\" ";
      //echo "data-required-flg=\"".h($_['required_flg'],true)."\" ";
      if((string)$key === (string)$_['default_setting']){
        echo "checked=\"checked\" ";
      }
      echo "class=\"";
      if((string)$_['not_edit_flg']==='1'){
        echo "form-disabled ";
      }else if((string)$_['not_edit_flg']==='2' && Session::get('thread-id') !== '0' ){
        echo "form-disabled ";
      }
      echo "\" ";
      if($key==="" && $value==="void") echo "style=\"display:none;\" ";
      echo "/>";
      if($key!=="" && $value!=="void"){
        echo h($value,true);
        echo "</label>";
      }
    }
  }
  ?>
  <div id="error_<?php h($_['id']) ?>"><?php h($_['error']) ?></div>
</div><!-- wrap_<?php h($_['id']) ?> -->
