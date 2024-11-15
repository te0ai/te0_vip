<?php

echo "<span id=\"wrap_{$form_arr['id']}\" class=\"wrap_input\">".PHP_EOL;
echo "<input type=\"text\" id=\"{$form_arr['id']}\" name=\"{$form_arr['name']}\" value=\"{$form_arr['value']}\" {$form_arr['attribute']} />".PHP_EOL;
echo "<span id=\"tag_{$form_arr['id']}\" class=\"tag_input\"></span>".PHP_EOL;

//echo "<button type=\"button\" ";
//echo "class=\"btn btn-default wiredxsyn_search\" ";
//echo "data-next-url=\"common/search/\" ";
//echo "data-transition-str=\"{$form_arr['id']}\" ";
//echo "data-input-group-id=\"{$form_arr['meta']['view']['group_id']}\" ";
//echo ">検索する</button>\n";

echo '<a id="search_bt_'.$form_arr['id'].'" class="btn btn-default wibox" data-url="';
echo r().'common/search/';
echo '?transition-str='.$form_arr['id'];
echo '&group-id='.$form_arr['input_group_id'];
echo '&frame=popup';
echo '" data-width="100%" data-height="100%">検索する</a>';

echo "<span id=\"error_{$form_arr['id']}\" class=\"error_input\"></span>".PHP_EOL;
echo "</span>".PHP_EOL;




?>
