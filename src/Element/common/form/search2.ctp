<div id="wrap_<?php h($_['id']) ?>" class="form-group">
  <div class="input-group">
    <?php
      echo "<select ";
      echo "id=\"". h($_['id'],true) ."\" ";
      echo "class=\"form-control ";

      //多次元処理
      if( isset($_['dimm_line']) ){
        echo "form-dimm-".h($_['input_data'],true)."[".$_['dimm_line']."] ";
      }else{
        echo "form-dimm-".h($_['input_data'],true)." ";
      }

      if((string)$_['not_edit_flg']==='1'){
        echo "form-disabled ";
      }else if((string)$_['not_edit_flg']==='2' && Session::get('thread-id') !== '0' ){
        echo "form-disabled ";
      }
      echo h($_['class'],true)."\" ";
      echo "name=\"". h($_['name'],true) ."\" ";
      echo "value=\"". h($_['default_setting'],true) ."\" ";
      echo "data-required-flg=\"". h($_['required_flg'],true) ."\" ";

      //多次元処理
      if( isset($_['dimm_line']) ){
        echo "data-dimm-str=\"". h($_['input_data'],true)."[".$_['dimm_line']."]\" ";
      }else{
        echo "data-dimm-str=\"". h($_['input_data'],true) ."\" ";
      }

      echo "/>";
    ?>
      <option value="<?php h($_['default_setting']) ?>" >---</option>
    </select>
  </div>
  <div id="error_<?php h($_['id']) ?>"><?php h($_['error']) ?></div>
</div>
