<div id="wrap_<?php h($_['id']) ?>" class="form-group <?php h(eximplode($_['input_class'],',',' ','wrap_')); ?>">
  <div class="input-group">
    <?php
    if(isset($_['decoration']['front'])) echo "<span id=\"dec_front_".h($_['id'],true)."\" class=\"input-group-addon\">".h($_['decoration']['front'],true)."</span>\n";
    echo "<input ";
    echo "type=\"text\" ";
    echo "id=\"". h($_['id'],true) ."\" ";
    echo "class=\"form-control form-hiragana ";
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
    if(isset($_['decoration']['back'])) echo "<span id=\"dec_back_".h($_['id'],true)."\" class=\"input-group-addon\">".h($_['decoration']['back'],true)."</span>\n";
    ?>
    <span id="button_<?php h($_['id']) ?>" class="input-group-btn">
    </span>
  </div>
  <div id="error_<?php h($_['id']) ?>"><?php h($_['error']) ?></div>
</div>
