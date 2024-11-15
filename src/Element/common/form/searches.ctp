<div id="wrap_<?php h($_['id']) ?>" class="form-group">
  <ul id="success_<?php h($_['id']) ?>" <?php
    echo "class=\"list-group form-searches-success ";
    if(
      (string)$_['not_edit_flg']==='1' ||
      ((string)$_['not_edit_flg']==='2' && Session::get('thread-id') !== '0' )
    ){
      echo "form-disabled ";
    }
    ?>" data-id="<?php h($_['id']) ?>">
  </ul>
  <?php
  echo "<textarea ";
  echo "type=\"text\" ";
  echo "id=\"". h($_['id'],true) ."\" ";
  echo "class=\"form-control form-searches hide ";
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
  echo "data-group-str=\"". h($_['input_data'][0],true) ."\" ";
  echo "/>";
  echo h($_['default_setting'],true);
  echo "</textarea>";
  ?>
  <div class="btn-group btn-group-justified" role="group">
    <a href="javascript:void(0)" class="btn btn-default wibox" data-width="100%" data-height="100%" data-url="<?php
    echo _HOME_
    .$_['input_data'][0].'/list/?'
    .'&parent-insert-id='.h($_['id'],true)
    .'&frame=popup';
    if(isset($_['input_data'][1])) h('&'.$_['input_data'][1]);
    ?>">
      <i class='fa fa-hand-pointer-o'></i>
    </a>
  </div>
  <div id="error_<?php h($_['id']) ?>"><?php h($_['error']) ?></div>
</div>
