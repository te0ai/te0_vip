<?php

//リキャプチャ-----------------------------------------
if(_RECAPTCHA_===true){
	
	echo "<span id=\"wrap_{$form_arr['id']}\" class=\"wrap_input\">".PHP_EOL;
	echo "<div id=\"{$form_arr['id']}\" name=\"{$form_arr['name']}\" {$form_arr['attribute']} data-sitekey=\"" . _RECAPTCHA_SITE_KEY_ . "\"></div>".PHP_EOL;
	if(isset($form_arr['errors'])) echo $form_arr['errors'];
	echo "</span>".PHP_EOL;
	
}

?>