<?php

include dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR."modules.php";
include dirname(__FILE__).DIRECTORY_SEPARATOR."config.php";

echo <<<EOD
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/> 
<head>
<style>
html, body {
margin:0;
padding:0;
height: 100%;
width: 100%;
overflow-x:hidden;
overflow-y:hidden;
}
</style>

</head>
<body>
<iframe src="http://www.facebook.com/connect/connect.php?id={$pageId}&name={$pageName}&connections={$connections}&stream=1" width="100%" height="100%" scrolling="no" frameborder="0"></iframe>
</body>
</html>
EOD;
?>