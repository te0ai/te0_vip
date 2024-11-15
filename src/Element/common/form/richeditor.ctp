<div id="wrap_<?php h($_['id']) ?>" class="form-group form-richeditor">
  <div class="btn-group" role="group">
  	<button type="button" class="btn btn-default form-richeditor-file-btn">ファイル添付</button>
  </div>
  <input class="hide" type="file" multiple="multiple">
  <?php
  echo "<textarea ";
  echo "type=\"text\" ";
  echo "id=\"". h($_['id'],true) ."\" ";
  echo "class=\"form-control form-richeditor-input ";
  if((string)$_['not_edit_flg']==='1'){
    echo "form-disabled ";
  }else if((string)$_['not_edit_flg']==='2' && Session::get('thread-id') !== '0' ){
    echo "form-disabled ";
  }
  echo h($_['class'],true)."\" ";
  echo "name=\"". h($_['name'],true) ."\" ";
  echo "data-required-flg=\"". h($_['required_flg'],true) ."\" ";
  echo "data-regex=\"". h($_['regex'],true) ."\" ";
  echo "data-regex-error=\"". h($_['regex_error'],true) ."\" ";
  echo "/>";
  echo h($_['default_setting'],true);
  echo "</textarea>";
  ?>
  <div id="error_<?php h($_['id']) ?>"><?php h($_['error']) ?></div>
</div>
