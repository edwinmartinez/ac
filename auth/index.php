<?PHP
	$logout = true;
	$cfgProgDir = './';
	include($cfgProgDir . "secure.php");
?>

<HTML><HEAD>
<TITLE>Index</TITLE>
</HEAD>

<BODY>
<P>This is a non secure test page.</P>

<P><A HREF="../test.html">LogIn to test page 1</A><BR>
<A HREF="../test2.php">LogIn to test page 2</A><BR>
<A HREF="../test3.php">LogIn to test page 3</A></P>
</BODY></HTML>
