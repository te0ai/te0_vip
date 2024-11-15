<?php

//複数行テキスト-----------------------------------------
echo "<span id=\"wrap_{$form_arr['id']}\" class=\"wrap_input\">".PHP_EOL;
echo "<textarea type=\"text\" id=\"{$form_arr['id']}\" name=\"{$form_arr['name']}\" {$form_arr['attribute']}>{$form_arr['value']}</textarea>".PHP_EOL;
if(isset($form_arr['errors'])) echo $form_arr['errors'];
echo "</span>".PHP_EOL;

?>