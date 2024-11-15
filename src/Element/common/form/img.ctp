<div id="wrap_<?php h($_['id']) ?>" class="form-group">
  <div class="form-file-area well">
    <input class="hide" type="file" multiple="multiple">
    <?php
    echo "<input ";
    echo "type=\"hidden\" ";
    //echo "type=\"text\" ";
    echo "id=\"". h($_['id'],true) ."\" ";
    echo "class=\"form-file-input ";
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
    <div class="media">
      <a class="form-file-img-href media-left" href="<?php h(_OB_HOME_.$_['img']['uuid']) ?>" target="_blank">
        <img class="form-file-img img-rounded" src="<?php h(_OB_HOME_.$_['img']['uuid']) ?>" />
    	</a>
      <div class="media-body">
        <h4 class="media-heading form-file-head"><?php h($_['img']['name']) ?></h4>
        <div class="input-group">
        	<input type="text" class="form-file-area-url form-control" placeholder="ファイルのURL">
          <span class="input-group-btn">
        		<button type="button" class="form-file-area-btn btn btn-default">
              <i class="fa fa-upload" aria-hidden="true"></i>
            </button>
            <button type="button" class="form-file-area-delete-btn btn btn-default">
              <i class="fa fa-trash-o" aria-hidden="true"></i>
            </button>
        	</span>
        </div>
      </div>
    </div>
  </div>
  <div id="error_<?php h($_['id']) ?>"><?php h($_['error']) ?></div>
</div>
