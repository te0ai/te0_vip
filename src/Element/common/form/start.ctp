
<?php
echo "<form ";
echo "method=\"post\" ";
if(isset($_['action'])) echo "action=\"".h($_['action'],true)."\" ";
if(isset($_['id'])) echo "id=\"".h($_['id'],true)."\" ";
if(isset($_['name'])) echo "name=\"".h($_['name'],true)."\" ";
if(isset($_['class'])) echo "class=\"".h($_['class'],true)."\" ";
if(isset($_['data'])) foreach($_['data'] as $k => $v) echo "data-".h($k,true)."=\"".h($v,true)."\" ";
echo "novalidate=\"novalidate\" ";
echo "accept-charset=\"utf-8\" ";
echo "enctype=\"multipart/form-data\" ";
echo ">".PHP_EOL;
?>
