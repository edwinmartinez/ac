<?php /* Smarty version Smarty-3.1.3, created on 2011-11-11 09:34:03
         compiled from "/home/edwin/sites/amigocupido/includes/templates/homePage.tpl" */ ?>
<?php /*%%SmartyHeaderCode:9245326904ebc8c5f38c784-96615439%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f75666702222b65a5f330f4d42d6380166449806' => 
    array (
      0 => '/home/edwin/sites/amigocupido/includes/templates/homePage.tpl',
      1 => 1321010207,
      2 => 'file',
    ),
    'c7b51ed01d1cd0c536327ebfca6fe33b4995e565' => 
    array (
      0 => '/home/edwin/sites/amigocupido/includes/templates/index.html',
      1 => 1321032806,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '9245326904ebc8c5f38c784-96615439',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.3',
  'unifunc' => 'content_4ebc8c5fac47f',
  'variables' => 
  array (
    'title' => 0,
    'head' => 0,
    'js_top_load' => 0,
    'js' => 0,
    'navbar' => 0,
    'my_home' => 0,
    'acc_tab_class' => 0,
    'my_account_settings' => 0,
    'fo_tab_class' => 0,
    'my_pictures' => 0,
    'content' => 0,
    'content_wide' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_4ebc8c5fac47f')) {function content_4ebc8c5fac47f($_smarty_tpl) {?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="shortcut icon" href="/favicon.ico" />
<meta name="description" content="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['title']->value)===null||$tmp==='' ? "Citas en linea un servicio de citas en linea para buscar pareja. AmigoCupido is a Latin Dating Service" : $tmp);?>
" />
<meta name="Keywords" content="citas, citas en linea, citas con hispanos, latin, hispanos, hispanics, latin datin, dating,online dating service, adult dating, free online dating, dating site, dating services, free dating, single dating, christian dating, christian dating service, dating web site, internet dating, community dating general, jewish dating, hispanic dating service, dating personals,  latinas, personals dating services,  personals for single and dating, internet personals dating, International Dating  Services, latin personals, latin american personals, latin  international personals, personals latin america, hot single latinas, latin  single dating, sexy latin single, latin single, single latin female, hispanic latin single, latin american single, latin single web  site, latin dating, latin dating services, latin dating " />

<title><?php echo (($tmp = @$_smarty_tpl->tpl_vars['title']->value)===null||$tmp==='' ? "AmigoCupido.com | Conocer gente en internet" : $tmp);?>
</title>

<link href="/style_std.css" rel="stylesheet" type="text/css" />

<?php echo $_smarty_tpl->tpl_vars['head']->value;?>

<?php echo (($tmp = @$_smarty_tpl->tpl_vars['js_top_load']->value)===null||$tmp==='' ? "<script id=1 language=\"javascript\" src=\"../js/prototype.js\"></script>
<script language=\"javascript\" src=\"../js/editinplace.js\"></script>
" : $tmp);?>


<?php echo (($tmp = @$_smarty_tpl->tpl_vars['js']->value)===null||$tmp==='' ? "<!--js end-->" : $tmp);?>

</head>

<body>
<div id="headerWords">
  <h1>AmigoCupido.com - Servicio de Citas en linea y buscar pareja. Diviertete Encontrando tu amor. Latin Dating Service</h1>
</div>
<div id="container">
    <div id="logo">
        <a href="http://www.amigocupido.com/"><img src="/images/spacer.gif" alt="Amigo Cupido" title="Citas En Linea" width="319" height="80" border="0" /></a>
    </div>
<?php $_smarty_tpl->tpl_vars['navbar'] = new Smarty_variable($_smarty_tpl->getSubTemplate ('main_nav.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0));?>

<?php echo $_smarty_tpl->tpl_vars['navbar']->value;?>

  <div id="homeContentWide">
  
	
	<?php if (isset($_smarty_tpl->tpl_vars['my_home']->value)){?>
	
	<div class="breadCrumbs">
	<a href="/mi_cuenta/"><?php echo $_smarty_tpl->tpl_vars['my_home']->value;?>
</a> / <?php echo $_smarty_tpl->tpl_vars['title']->value;?>

	</div>
	
<!--	<div id="accHeadWrap">
	<a class="accHeadTab<?php if ($_smarty_tpl->tpl_vars['acc_tab_class']->value){?><?php echo $_smarty_tpl->tpl_vars['acc_tab_class']->value;?>
<?php }?>" href="?p=ac"><?php echo $_smarty_tpl->tpl_vars['my_account_settings']->value;?>
</a>
	<a class="accHeadTab<?php if ($_smarty_tpl->tpl_vars['fo_tab_class']->value){?><?php echo $_smarty_tpl->tpl_vars['fo_tab_class']->value;?>
<?php }?>" href="?p=fo"><?php echo $_smarty_tpl->tpl_vars['my_pictures']->value;?>
</a>
	</div>-->
	<?php }?>
	
	
<div id="homeContentWideTop">

		    <div id="homeLeftCol">
			<table border="0" cellpadding="4" cellspacing="0" >
			<tr><td width="193" valign="top">
		      <h1>Bienvenido!</h1>
			  <strong>Aqui vas a encontrar pareja</strong> <!--mediante citas en linea.-->
			  Quieres buscar pareja? Pues ya llegaste. Dejanos probarte que nuestro servicio de encontrar pareja por medio de la Internet puede llegar a darte una relacion duradera y sincera. Subcribete ya a AmigoCupido para conoser aquella persona que anda buscando su media naranja como t&uacute;.  Nuestro trabajo es de hacer que esta experiencia sea la mejor y mas agradable posible para ti. Desde el contenido y consejos en nuestros articulos hasta filtrar aquellas personas no deseadas.<br />
			 
			  
			  </td>
			  <td width="2" valign="top"><img src="images/spacer.gif" width="2" height="100" /></td>
			  <td width="196" valign="top"><h1>Busca Por:<br />
			  </h1>
			  
			    <table width="190" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFF99">
                  <tr>
                    <td>&nbsp;</td>
                    <td align="left" valign="bottom">&nbsp;</td>
                    <td rowspan="3" align="left" valign="middle"><img src="images/spacer.gif" width="10" height="1" alt="" /></td>
                    <td align="left" valign="bottom">&nbsp;</td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td align="left" valign="bottom">Soy</td>
                    <td align="left" valign="bottom">buscando</td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td align="left" valign="top">
					<form action="/gente/" name="form1">
					<select name="gender" id="gender" class="indexFormElements" style="width:70px;">
                      <option value="1">Hombre</option>
                      <option value="2" selected="selected">Mujer</option>
                                                            </select></td>
                    <td align="left" valign="top"><select name="seeks_gender" id="seeks_gender" class="indexFormElements" style="width:70px;">
                      <option value="1">Hombre</option>
                      <option value="2">Mujer</option>
                                                            </select></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td colspan="3">&nbsp;</td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td colspan="3" align="left" valign="bottom">entre las edades de </td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td colspan="3" align="left" valign="bottom">
                    <?php if (isset($_smarty_tpl->tpl_vars['age_menu']->value)){?>
                    	<?php echo $_smarty_tpl->tpl_vars['age_menu']->value;?>

                    <?php }?>
                    </td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td colspan="3" align="left" valign="bottom">&nbsp;</td>
                  </tr>
                   <?php if (isset($_smarty_tpl->tpl_vars['countries_menu']->value)){?>
                  <tr>
                    <td>&nbsp;</td>
                    <td colspan="3" align="left" valign="bottom">en</td>
                  </tr>
                 
                  <tr>
                    <td>&nbsp;</td>
                    <td colspan="3" align="left" valign="bottom">
                        <?php echo $_smarty_tpl->tpl_vars['countries_menu']->value;?>

                    </td>
                  </tr>
                  <?php }?>
                  <tr>
                    <td>&nbsp;</td>
                    <td colspan="3" align="left" valign="bottom">&nbsp;</td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td colspan="3" align="left" valign="bottom">
                      <input type="submit" name="Submit" value="Buscar" />
					  </form>	
                    </td>
                  </tr>
                </table>		    
			  </td>
			  </tr>
	  </table>
	<table border="0">
		<tr>
		<td>
		<div style="height:54px; width:310px; margin-right:8px;background: url(images/bg_note.gif) no-repeat; padding:10px 0px 0px 18px;">AmigoCupido es un servicio totalmente <strong>Gratis!</strong><br />No compartimos tu informacion o tu email con nadie.
		</div>
		</td>
		</tr>
	</table>
  
	  
		    </div>
	  
	  	    <div id="homeRightPic" style="float:right;"><img src="images/home_right_pic.png" alt="amigos en la web" /></div>
	<div class="loginBox">
		<form method="post"  onsubmit="return checkData()" action="/login/index.php"  id="loginForm">
		<label>Apodo:<input type="text" name="entered_login" class="text" /></label>
		<label> Contrase&ntilde;a: <input type="password" name="entered_password" class="text" />
		<input type="submit" hspace="7" vspace="4" alt=" Entrar " value=" Entrar " class="buttons" /></label>
	
		No estas registrado? Vamos... <strong><a href="/registrate/" style="color:#0033FF; font-size:14px">&iexcl;Registrate Ya!</a></strong>
		<br />Se te olvido tu <a href="http://www.amigocupido.com/olvide_contrasena/">contrase&ntilde;a?</a>
		</form>
		
		<div class="clear" style="clear:both"></div>
	</div>	
			
			</div>
<div style="clear:both"></div>

	
	<?php if (isset($_smarty_tpl->tpl_vars['content']->value)){?>
	<div id="contentText">
	<p>
	<?php echo $_smarty_tpl->tpl_vars['content']->value;?>

	</p>
	</div>
	<?php }?>
	
	<?php if (isset($_smarty_tpl->tpl_vars['content_wide']->value)){?>

	<?php echo $_smarty_tpl->tpl_vars['content_wide']->value;?>


	<?php }?>	
	
	    <div id="footer">
        <a href="http://www.amigocupido.com/contacto/">Contactanos</a> 
        <a href="http://www.amigocupido.com/terminos_y_condiciones/">Terminos y Condiciones</a>
        </div>
	
	</div>

</div>


<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
</script>
<script type="text/javascript">
_uacct = "UA-595260-2";
urchinTracker();
</script>

</body>
</html>
<?php }} ?>