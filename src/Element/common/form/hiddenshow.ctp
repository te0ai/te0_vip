<div id="wrap_<?php h($_['id']) ?>">
  <?php
  echo "<input ";
  echo "type=\"hidden\" ";
  echo "id=\"". h($_['id'],true) ."\" ";
  echo "class=\"";
  echo h($_['class'],true)."\" ";
  echo "name=\"". h($_['name'],true) ."\" ";
  echo "value=\"". h($_['default_setting'],true) ."\" ";
  echo "data-required-flg=\"". h($_['required_flg'],true) ."\" ";
  echo "data-regex=\"". h($_['regex'],true) ."\" ";
  echo "data-regex-error=\"". h($_['regex_error'],true) ."\" ";
  echo "/> ";
  ?>
  <p>
    <?php h($_['default_setting']) ?>
  </p>
  <div id="error_<?php h($_['id']) ?>"><?php h($_['error']) ?></div>
</div>
