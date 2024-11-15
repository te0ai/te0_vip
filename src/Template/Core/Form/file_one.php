<?php

echo "<div id=\"wrap_{$form_arr['id']}\" class=\"wrap_input form-group\">".PHP_EOL;
echo "<input type=\"text\" id=\"{$form_arr['id']}\" name=\"{$form_arr['name']}\" value=\"{$form_arr['value']}\" {$form_arr['attribute']} />".PHP_EOL;
echo "	<div class=\"drag-area\">".PHP_EOL;
echo "		<input type=\"file\" multiple=\"multiple\" data-id=\"{$form_arr['id']}\" />".PHP_EOL;
echo "		<div id=\"file_{$form_arr['id']}\">";
if($form_arr['value']){
	echo "<img class=\"img-responsive wiredxsyn_file_input upload_button\" src=\"/img/wiredsyn/sp.png\" data-token=\"".$form_arr['value']."\" style=\"max-width:300px;max-height:300px;\"/>";
}else{
	echo "<a class=\"btn btn-default upload_button\">アップロード</a>".PHP_EOL;
}
echo "</div>".PHP_EOL;
echo "	</div>".PHP_EOL;
echo "	<span id=\"error_{$form_arr['id']}\" class=\"error_input\">{$form_arr['error']}</span>".PHP_EOL;
echo "</div>".PHP_EOL;

?>
