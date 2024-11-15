<?php

//パスワード-----------------------------------------

echo "<span id=\"wrap_{$form_arr['id']}\" class=\"wrap_input form-group\">".PHP_EOL;
echo "<input type=\"password\" id=\"{$form_arr['id']}\" name=\"{$form_arr['name']}\" value=\"\" {$form_arr['attribute']} />".PHP_EOL;
echo "<span id=\"error_{$form_arr['id']}\" class=\"error_input\">{$form_arr['error']}</span>".PHP_EOL;
echo "</span>".PHP_EOL;

?>