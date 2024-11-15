<div id="wrap_<?php h($_['id']) ?>" class="form-group">
  <div class="input-group">
    <?php
    echo "<input ";
    echo "type=\"text\" ";
    echo "id=\"". h($_['id'],true) ."\" ";
    echo "class=\"form-control datepicker-ym ";
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
  </div>
  <div id="error_<?php h($_['id']) ?>"><?php h($_['error']) ?></div>
</div>
