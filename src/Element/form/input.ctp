<?php

echo '<input ';
echo 'type="text" ';
echo 'id="form_kana_name" ';
echo 'class="form-control form-control-sm api-update" ';
echo 'value="" ';
echo '<label ';
echo 'for="form_kana_name" ';
echo 'class="" ';
echo '名前（カナ）';
echo '</label>';

// //wrap st
// echo "<div id=\"wrap_".htmlspecialchars($_['id'])."\" ";
// //wrap -> class
// echo "class=\"";
// echo isset($_['input_hidden']) && (string)$_['input_hidden'] === '1' ? "hide " : "";
// echo h(eximplode($_['input_class'],',',' ','wrap_'))."\">\n";
//   //input-group
//   echo "<div class=\"input-group\">\n";
//     //prepend text
//     if(isset($_['decoration']['prepend'])){
//       echo "<div class=\"input-group-prepend\">\n";
//       echo "<span id=\"dec_prepend_".h($_['id'],true)."\" class=\"input-group-text\">".h($_['decoration']['prepend'],true)."</span>\n";
//       echo "</div>\n";
//     }
//     //input
//     echo " <input ";
//     //input -> id
//     echo isset($_['id']) ? "id=\"".htmlspecialchars($_['id'])."\" " : "";
//     //input -> name
//     echo isset($_['name']) ? "name=\"".htmlspecialchars($_['name'])."\" " : "";
//     //input -> title
//     echo isset($_['name']) ? "title=\"".htmlspecialchars($_['name'])."\" " : "";
//     //input -> type
//     echo "type=\"text\" ";
//     //input -> class
//     echo "class=\"form-control form-check ";
//     if((string)$_['not_edit_flg']==='1'){
//       echo "form-disabled ";
//     }else if((string)$_['not_edit_flg']==='2'){
//       echo "form-not-update ";
//       if(Session::get('thread-id') !== '0') echo "form-disabled ";
//     }
//     echo h($_['class'],true)."\" ";
//     //input -> value
//     echo "value=\"". h($_['default_setting'],true) ."\" ";
//     //input -> data-required-flg
//     echo "data-required-flg=\"". h($_['required_flg'],true) ."\" ";
//     //input -> data-regex
//     echo "data-regex=\"". h($_['regex'],true) ."\" ";
//     //input -> data-regex-error
//     echo "data-regex-error=\"". h($_['regex_error'],true) ."\" ";
//     //input -> placeholder
//     echo isset($_['decoration']['placeholder']) ? "placeholder=\"".htmlspecialchars($_['decoration']['placeholder'])."\" " : "";
//     echo ">\n";
//     //append text
//     if(isset($_['decoration']['prepend'])){
//       echo "<div class=\"input-group-prepend\">\n";
//       echo "<span id=\"dec_prepend_".h($_['id'],true)."\" class=\"input-group-text\">".h($_['decoration']['prepend'],true)."</span>\n";
//       echo "</div>\n";
//     }
//   //input-group en
//   echo "</div>\n";
//   echo "<div id=\"error_".h($_['id'],true)."\" class=\"bg-danger text-white form-error\"></div>\n";
// //wrap en
// echo "</div>\n";
?>
