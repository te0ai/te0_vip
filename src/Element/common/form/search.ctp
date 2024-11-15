<?php
//wrap st
echo "<div id=\"wrap_".htmlspecialchars($_['id'])."\" ";
//wrap -> class
echo "class=\"";
echo isset($_['input_hidden']) && (string)$_['input_hidden'] === '1' ? "hide " : "";
echo h(eximplode($_['input_class'],',',' ','wrap_'))."\">\n";
  //input-group
  echo "  <div class=\"input-group\">\n";
    //input
    echo "   <input ";
      //input -> id
      echo isset($_['id']) ? "id=\"".htmlspecialchars($_['id'])."\" " : "";
      //input -> name
      echo isset($_['name']) ? "name=\"".htmlspecialchars($_['name'])."\" " : "";
      //input -> type
      echo "type=\"text\" ";
      //input -> class
      echo "class=\"form-control form-search ";
      if(
        (string)$_['not_edit_flg']==='1' ||
        ((string)$_['not_edit_flg']==='2' && Session::get('thread-id') !== '0' )
      ){
        echo "form-disabled ";
      }
      echo h($_['class'],true)."\" ";
      //input -> value
      echo "value=\"". h($_['default_setting'],true) ."\" ";
      //input -> data-required-flg
      echo "data-required-flg=\"". h($_['required_flg'],true) ."\" ";
      //input -> data-regex
      echo "data-regex=\"". h($_['regex'],true) ."\" ";
      //input -> data-regex-error
      echo "data-regex-error=\"". h($_['regex_error'],true) ."\" ";
      //input -> placeholder
      echo isset($_['decoration']['placeholder']) ? "placeholder=\"".htmlspecialchars($_['decoration']['placeholder'])."\" " : "";
      //input -> data-group-strdata-group-str
      echo isset($_['input_data']) && is_string($_['input_data']) ? "data-group-str=\"".htmlspecialchars($_['input_data'])."\" " : "";
    echo ">\n";
  echo "    <div class=\"input-group-append\">\n";
  echo "      <a class=\"btn btn-light btn-search wibox\" data-url=\"\">\n";
  echo "        <i class=\"far fa-hand-pointer\"></i>\n";
  echo "      </a>\n";
  echo "    </div>\n";
  echo "  </div>\n";
  //history
  echo isset($_['id']) ? "<div id=\"history_".htmlspecialchars($_['id'])."\" class=\"form-search-history list-group\"></div>" : "";
//wrap en
echo "</div>\n";
?>

<?php /*
<div id="wrap_<?php h($_['id']) ?>" class="form-group">
  <div class="input-group">
    <div id="success_<?php h($_['id']) ?>" <?php
    echo "class=\"form-control form-search-success ";
    if(
      (string)$_['not_edit_flg']==='1' ||
      ((string)$_['not_edit_flg']==='2' && Session::get('thread-id') !== '0' )
    ){
      echo "form-disabled ";
    }
    ?>" data-id="<?php h($_['id']) ?>" data-group-str="<?php h($_['input_data'][0]) ?>"></div>
    <input
    type="hidden"
    id="<?php h($_['id']) ?>"
    <?php
    echo "class=\"form-control form-search ";
    if(
      (string)$_['not_edit_flg']==='1' ||
      ((string)$_['not_edit_flg']==='2' && Session::get('thread-id') !== '0' )
    ){
      echo "form-disabled ";
    }
    echo h($_['class'],true)."\" ";
    ?>
    name="<?php h($_['name']) ?>"
    value="<?php h($_['default_setting']) ?>"
    data-meta="<?php echo AES::enc($_['default_setting']) ?>"
    data-required-flg="<?php h($_['required_flg']) ?>"
    data-regex="<?php h($_['regex']) ?>"
    data-regex-error="<?php h($_['regex_error']) ?>"
    data-id="<?php h($_['id']) ?>"
    data-group-str="<?php h($_['input_data'][0]) ?>"
    />

    <span id="button_<?php h($_['id']) ?>" class="input-group-btn">

      <?php
        if(
          (string)$_['not_edit_flg']==='0' ||
          ((string)$_['not_edit_flg']==='2' && Session::get('thread-id') === '0' )
        ):
      ?>
        <a href="javascript:void(0)" class="btn btn-primary wibox" data-width="100%" data-height="100%" data-url="<?php
        echo _HOME_
        .$_['input_data'][0].'/list/?'
        .'&parent-insert-id='.h($_['id'],true)
        .'&frame=popup';
        if(isset($_['input_data'][1])) h('&'.$_['input_data'][1]);
        ?>">
          <i class='far fa-hand-pointer'></i>
        </a>
      <?php endif; ?>

      <?php
        if(Session::get('thread-id') !== '0'):
      ?>
        <button type="button" class="btn btn-default dropdown-toggle form-search-toggle" data-toggle="dropdown" aria-expanded="false">
    			<span class="caret"></span>
    		</button>
    		<ul class="dropdown-menu" role="menu">
    			<li><a href="javascript:void(0)" class="form-search-link">詳細</a></li>
    		</ul>
      <?php endif; ?>

    </span>
  </div>
  <div id="error_<?php h($_['id']) ?>"><?php h($_['error']) ?></div>
  <div id="history_<?php h($_['id']) ?>" class="form-search-history list-group"></div>
</div>

*/ ?>
