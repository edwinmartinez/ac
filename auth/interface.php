<?PHP
// ------ create document-location variable ------
if ( ereg("php\.exe", $_SERVER['PHP_SELF']) || ereg("php3\.cgi", $_SERVER['PHP_SELF']) || ereg("phpts\.exe", $_SERVER['PHP_SELF']) ) {
	$documentLocation = $_ENV['PATH_INFO'];
} else {
	$documentLocation = $_SERVER['PHP_SELF'];
}
if ( $_ENV['QUERY_STRING'] ) {
	$documentLocation .= "?" . $_ENV['QUERY_STRING'];
}

$js_top_load = '
<script language="javascript">
function checkData() {
	var f1 = document.forms[0];
	//var wm = "<?PHP echo $strJSHello; ?>\n\r\n";
	var wm = "Estimado visitante,\nindique los siguientes datos:\n\r\n";
	var noerror = 1;

	// --- entered_login ---
	var t1 = f1.entered_login;
	if (t1.value == "" || t1.value == " ") {
		//wm += "<?PHP echo $strLogin; ?>\r\n";
		wm += "Login\r\n";
		noerror = 0;
	}

	// --- entered_password ---
	var t1 = f1.entered_password;
	if (t1.value == "" || t1.value == " ") {
		//wm += "<?PHP echo $strPassword; ?>\r\n";
		wm += "Tu clave\r\n";
		noerror = 0;
	}

	// --- check if errors occurred ---
	if (noerror == 0) {
		alert(wm);
		return false;
	}
	else return true;
}

</script>';

require_once('../includes/smarty_setup.php');
	$smarty = new Smarty_su;
	$smarty->compile_check = true;
	$smarty->assign("js_top_load",$js_top_load);	
	$smarty->assign("title","Login para Conocer Gente, personas en internet gratis, Amigos, Fotos, Chicas");

if(isset($message) && $message != '')
	$message = '<div style="display:block; background:#c00; color:#fff; text-align:center;"><strong>'.$message.'</strong></div>';
	
$content ='
<form action='.$documentLocation.' method="post" onsubmit="return checkData()">
<div style="display:block; background:#fff; text-align:center;">
	<div style="float:left; background:#EEEEEE; width:300px; padding:10px; border:#ccc solid 1px; text-align:left;">
	<strong>&Uacute;nete a AmigoCupido</strong><br />
		Es GRATIS y es facil. Solo tienes que llenar el formulario que se encuentra <strong><a href="/registrate/">aqui</a></strong>.<br />
 <p>
<strong>Que es AmigoCupido?</strong><br />
Es sitio divertido donde podras<br />
- <strong>Conocer Gente</strong><br />
- Conocer Amigos <br />
- Cargar tus fotos y compartirlas con quien quieras<br /><br />

</p> 

	</div>
	
	<div style="float:right; background:#fff; margin:auto; padding:auto;">
		<table cellpadding="4" width="270" height="100%" border="0">
		<tr><td align="left"><h1>'.LA_LOGIN.'</h1></td></tr>
		<tr>
		<td align="right" valign="bottom">
		'.$message.'
			<table cellpadding="4" cellspacing="1" >
			<tr><td>'.LA_USERNAME.': </td>
			<td> <input type="text" name="entered_login" /></td></tr>
			<tr><td>'.LA_PASSWORD.': </td>
			<td> <input type="password" name="entered_password" /></td></tr>
			</table>
			<input type="submit" hspace="7" vspace="4" alt="'.$strEnter.'"   &gt;&gt;&gt;" value="'.$strEnter.'"   &gt;&gt;&gt;" />
		</td></tr>
		</table>
		Se me olvido mi: <a href="/olvide_contrasena/">Contrase&ntilde;a</a> | <a href="/olvide_apodo/">Apodo</a>
	</div>
	<div style="clear:both;"></div>
</div>
</form>';

$smarty->assign("content",$content);
$smarty->display('index.html');
