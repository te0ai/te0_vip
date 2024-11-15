<?php

//チェックボックス-----------------------------------------

//バリューを分解
$value_arr = array();
$value_arr = explode(',',$form_arr['value']);

echo "<span id=\"wrap_{$form_arr['id']}\" class=\"wrap_input\">".PHP_EOL;
if( isset($form_arr['option_str']) ) $form_arr['option'] = a($form_arr['option_str']);
if( isset($form_arr['option']) && is_array($form_arr['option']) ){

	foreach($form_arr['option'] as $key => $value){

		echo "\t<input type=\"checkbox\" id=\"{$form_arr['id']}_{$key}\" name=\"{$form_arr['name']}\" value=\"{$key}\" {$form_arr['attribute']}";
		//チェックされているかどうか
		if(!in_array($key,$value_arr,true)){
			echo "/>".PHP_EOL;
		}else{
			echo "checked=\"checked\" />".PHP_EOL;
		}
		echo "<label for=\"{$form_arr['id']}_{$key}\">".h($value,true)."</label>";

	}


}
echo "\t<input type=\"hidden\" id=\"{$form_arr['id']}\" name=\"{$form_arr['name']}\" value=\"{$form_arr['value']}\" {$form_arr['attribute']} />".PHP_EOL;
echo "<span id=\"error_{$form_arr['id']}\" class=\"error_input\">{$form_arr['error']}</span>".PHP_EOL;
echo "</span>".PHP_EOL;

?>
