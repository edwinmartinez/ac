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

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/citasenlinea01.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="shortcut icon" href="/favicon.ico" />
<meta name="Description" content="Citas en linea un servicio de citas en linea para hispanos. Citas en linea is a Latin Dating Service" />
<meta name="description" lang="es" content="Servicio de citas en línea en donde podrás conocer, de una manera facil y simple, a otros solteros en Latinoamérica y el resto del mundo en busca de amor y amistad." />
<meta name="Keywords" content="citas, citas en linea, citas con hispanos, latin, hispanos, hispanics, latin datin, dating,online dating louisville online dating service, adult dating, free online dating, dating site, dating services, free dating, single dating, christian dating, christian dating service, dating web site, gay dating, internet dating, community dating general, jewish dating, hispanic dating service, dating personals,  latinas, personals dating services,  personals for single and dating, internet personals dating, International Dating  Services, latin personals, latin american personals, latin  international personals, personals latin america,  latin meet, hot single latinas, latin  single dating, sexy latin single, latin single, latin single  connection, single latin female, hispanic latin single, latin american single, latin single web  site, latin dating, latin dating services, latin dating " />
<!-- InstanceBeginEditable name="doctitle" -->
<title>AmigoCupido.com - Citas en Linea | Citas con hispanos</title>
<!-- InstanceEndEditable -->
<link href="../style_std.css" rel="stylesheet" type="text/css" />
<!-- InstanceBeginEditable name="head" --><!-- InstanceEndEditable --><!-- InstanceParam name="content" type="boolean" value="true" --><!-- InstanceParam name="contentWide" type="boolean" value="false" -->
</head>

<body>
<div id="headerWords">
  <h1>AmigoCupido.com - Servicio de Citas en linea y buscar pareja. Diviertete Encontrando tu amor. Latin Dating Service</h1>
</div>
<div id="container"><!-- #BeginLibraryItem "/Library/topNav.lbi" -->
		<div id="logo">
<a href="http://www.amigocupido.com/"><img src="/images/spacer.gif" alt="AmigoCupido.com" title="myspace en espanol" width="319" height="80" border="0" /></a></div>
		<div id="topNav">
		<a href="/">Inicio</a> | 
		<a href="/gente/">Gente</a> | 
		<a href="/fotos/">Fotos</a> |
		<a href="/citas_en_linea_blog/">Articulos</a> | <a href="/citas-en-linea-acerca.php">Acerca de Amigo Cupido</a> | <a href="/citas_en_linea_contacto.php">Contactanos</a>	|
		<a href="/mi_cuenta/">Login</a> 	
<?php if(isset($_SESSION['user_id']))
		echo '| <a href="/logout/">Salir (Logout)</a>';
?>
</div> 

<!-- #EndLibraryItem --><div id="contentText">
      <h1><!-- InstanceBeginEditable name="title" -->Login<!-- InstanceEndEditable --></h1>
	  <!-- InstanceBeginEditable name="content" -->	


<script language="JavaScript" type="text/javascript">
<!--
//  ------ check form ------
function checkData() {
	var f1 = document.forms[0];
	var wm = "<?PHP echo $strJSHello; ?>\n\r\n";
	var noerror = 1;

	// --- entered_login ---
	var t1 = f1.entered_login;
	if (t1.value == "" || t1.value == " ") {
		wm += "<?PHP echo $strLogin; ?>\r\n";
		noerror = 0;
	}

	// --- entered_password ---
	var t1 = f1.entered_password;
	if (t1.value == "" || t1.value == " ") {
		wm += "<?PHP echo $strPassword; ?>\r\n";
		noerror = 0;
	}

	// --- check if errors occurred ---
	if (noerror == 0) {
		alert(wm);
		return false;
	}
	else return true;
}
//-->
</script>

<style type="text/css">
<!-- 
A:hover.link {
	background-color: #E9E9E9;
}
//-->
</style>



<form action='<?PHP echo $documentLocation; ?>' method="post" onsubmit="return checkData()">


	<table cellpadding="0" cellspacing="0" border="0"><tr><td align="center" valign="middle">
		<table cellpadding="4" width="100%" height="100%" border="0">
		<tr><td align="center"><h1><?PHP echo $strLoginInterface; ?></h1></td></tr>
		<tr><td align="center">
			<b><i><?PHP
			// check for error messages
			if ($message) {
				echo $message;
			} ?></i></b>
		</td></tr>
		<tr>
		<td align="right" valign="bottom">
			<table cellpadding="4" cellspacing="1" >
			<tr><td><?PHP echo $strLogin; ?>: </td>
			<td> <input type="text" name="entered_login" /></td></tr>
			<tr><td><?PHP echo $strPassword; ?>: </td>
			<td> <input type="password" name="entered_password" /></td></tr>
			</table>
			<input type="submit" hspace="7" vspace="4" alt="<?PHP echo $strEnter; ?>   &gt;&gt;&gt;" value="<?PHP echo $strEnter; ?>   &gt;&gt;&gt;" />
		</td></tr>
		<tr><td>
		Si no estas registrado, registrate <strong><a href="/registrate/">aqui</a></strong>. Es GRATIS!
		</td>
		</tr>
		</table>
	</td></tr></table>


</form>


<script language="JavaScript" type="text/javascript">
<!--
document.forms[0].entered_login.select();
document.forms[0].entered_login.focus();
//-->
</script>
<!-- InstanceEndEditable --><br />
    </div>
	</div>


</body>
<!-- InstanceEnd --></html>
