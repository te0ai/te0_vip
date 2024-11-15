<div id="wrap_<?php h($_['id']) ?>" class="form-group">
  <?php
  echo "<input ";
  echo "type=\"text\" ";
  echo "id=\"". h($_['id'],true) ."\" ";
  echo "class=\"form-control ". h($_['class'],true) ."\" ";
  echo "name=\"". h($_['name'],true) ."\" ";
  echo "value=\"". h($_['default_setting'],true) ."\" ";
  echo "data-required-flg=\"". h($_['required_flg'],true) ."\" ";
  echo "data-regex=\"". h($_['regex'],true) ."\" ";
  echo "data-regex-error=\"". h($_['regex_error'],true) ."\" ";
  if((string)$_['not_edit_flg']==='1') echo "disabled=\"disabled\" ";
  echo "/> ";
  ?>
  <div id="error_<?php h($_['id']) ?>"><?php h($_['error']) ?></div>
</div>