<?php

//一行テキスト
echo "<div id=\"wrap_{$form_arr['id']}\" class=\"wrap_input form-group\">".PHP_EOL;
echo "<div class=\"input-group\">".PHP_EOL;
echo "<input type=\"text\" id=\"{$form_arr['id']}\" name=\"{$form_arr['name']}\" value=\"{$form_arr['value']}\" {$form_arr['attribute']} />".PHP_EOL;
echo "</div>".PHP_EOL;
//echo "<div id=\"error_{$form_arr['id']}\" class=\"alert alert-warning\">{$form_arr['error']}</div>".PHP_EOL;
echo "<div id=\"error_{$form_arr['id']}\">{$form_arr['error']}</div>".PHP_EOL;
echo "</div>".PHP_EOL;

?>
