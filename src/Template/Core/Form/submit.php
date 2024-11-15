<?php

//サブミット-----------------------------------------
echo "<button class=\"btn btn-lg btn-primary btn-block error-check\" type=\"submit\">{$form_arr['value']}</button>".PHP_EOL;

/*
echo "<span id=\"wrap_{$form_arr['id']}\" class=\"wrap_input\">".PHP_EOL;
echo "<select id=\"{$form_arr['id']}\" name=\"{$form_arr['name']}\" {$form_arr['attribute']} style=\"background-image: url(".i(1)."select-arrow.png)\"/>".PHP_EOL;
echo "\t<option value=\"\" >---</option>".PHP_EOL;
if( isset($form_arr['option']) && is_array($form_arr['option']) ){
	foreach($form_arr['option'] as $key => $value){
		if((string)$key !== (string)$form_arr['value']){
			echo "\t<option value=\"{$key}\" >{$value}</option>".PHP_EOL;
		}else{
			echo "\t<option value=\"{$key}\" selected=\"selected\" >{$value}</option>".PHP_EOL;
		}
	}
}
echo "</select>".PHP_EOL;
echo "<span id=\"error_{$form_arr['id']}\" class=\"error_input\">{$form_arr['error']}</span>".PHP_EOL;
echo "</span>".PHP_EOL;
*/
?>
