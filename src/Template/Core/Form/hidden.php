<?php

//一行テキスト
echo "<span id=\"wrap_{$form_arr['id']}\" class=\"wrap_input\">".PHP_EOL;
echo "<input type=\"hidden\" id=\"{$form_arr['id']}\" name=\"{$form_arr['name']}\" value=\"{$form_arr['value']}\" {$form_arr['attribute']} />".PHP_EOL;
echo "<span id=\"error_{$form_arr['id']}\" class=\"error_input\"></span>".PHP_EOL;
echo "</span>".PHP_EOL;

?>