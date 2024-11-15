<?php

//ラジオボタン-----------------------------------------
echo "<div id=\"wrap_{$form_arr['id']}\" class=\"wrap_input form-group\">".PHP_EOL;
if( isset($form_arr['option_str']) ) $form_arr['option'] = a($form_arr['option_str']);
if( isset($form_arr['option']) && is_array($form_arr['option']) ){
	$checked_flg = false;
	foreach($form_arr['option'] as $key => $value){

		echo "<label for=\"{$form_arr['id']}_{$key}\" class=\"radio-inline\">";

		echo "\t<input type=\"radio\" id=\"{$form_arr['id']}_{$key}\" name=\"{$form_arr['name']}\" value=\"{$key}\" data-id=\"{$form_arr['id']}\" {$form_arr['attribute']}";

		//セレクトされているかどうか
		if((string)$key !== (string)$form_arr['value']){
			echo "/>".PHP_EOL;
		}else{
			echo "checked=\"checked\" />".PHP_EOL;
			$checked_flg = true;
		}
		
		echo h($value,true)."</label>";

	}
	echo "\t<input type=\"hidden\" id=\"{$form_arr['id']}\" name=\"{$form_arr['name']}\" value=\"{$form_arr['value']}\" {$form_arr['attribute']} />".PHP_EOL;

}
echo "<span id=\"error_{$form_arr['id']}\" class=\"error_input\">{$form_arr['error']}</span>".PHP_EOL;
echo "</div>".PHP_EOL;


?>
