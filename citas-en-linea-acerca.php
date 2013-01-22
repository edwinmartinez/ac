<?php 
include('includes/config.php');
require_once('includes/smarty_setup.php');
	$smarty = new Smarty_su;
	$smarty->compile_check = true;
	$smarty->assign("js",$js);	
	$smarty->assign("title","Amigo Cupido | Acerca de Amigo Cupido : Buscar Pareja Latin Dating");


	$content = <<<ENDOF
<div id="contentText">
		        <p>Amigo Cupido es una comunidad en l&iacute;nea de solteros dedicada a  ayudar a hombres y mujeres a encontrar otros solteros en un ambiente  confortable. Nuestra comunidad es una fuente para crear relaciones de  todo tipo, desde compa&ntilde;erismo hasta amistad, de romance hasta  matrimonio.</p>
        <p>En Amigo Cupido podr&aacute;s encontrar foros, perfiles con fotos, galer&iacute;as, mensajes an&oacute;nimos privados, y mucho m&aacute;s.</p>
        <p><strong>Servicios</strong></p>
        <p>Algunos de los servicios de Amigo Cupido incluyen:</p>
        <ul>
          <li>Perfiles y fotos de hombres solteros y mujeres solteras en tu area o a nivel nacional e internacional.</li>
          <li>Sistema de mensajes privados en l&iacute;nea para intercambiar mensajes personales.</li>
          <li>Foro publico en l&iacute;nea para discutir y compartir informaci&oacute;n.</li>
        </ul>
		
		<p><strong>Vamos. Registrate ya <a href="http://www.amigocupido.com/registrate/">aqui</a></strong></p>
		
        <p>Para obtener informaci&oacute;n adicional sobre Amigo Cupido, puede contactarnos a trav&eacute;s de nuestro <a href="citas_en_linea_contacto.php">formulario de contacto</a>.</p>
</div>
ENDOF;


	$smarty->assign("content_wide",$content);
	$smarty->display('index.html');	

?>