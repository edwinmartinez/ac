<?php /* Smarty version Smarty-3.1.3, created on 2011-12-13 03:45:53
         compiled from "/home/edwin/sites/amigocupido/includes/templates/block_messages.tpl" */ ?>
<?php /*%%SmartyHeaderCode:9952973044ee567b652e995-29805701%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e86977002479de90593aef527a15641d3607d8fb' => 
    array (
      0 => '/home/edwin/sites/amigocupido/includes/templates/block_messages.tpl',
      1 => 1323776705,
      2 => 'file',
    ),
    'c7b51ed01d1cd0c536327ebfca6fe33b4995e565' => 
    array (
      0 => '/home/edwin/sites/amigocupido/includes/templates/index.html',
      1 => 1323657353,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '9952973044ee567b652e995-29805701',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.3',
  'unifunc' => 'content_4ee567b65d67e',
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
<?php if ($_valid && !is_callable('content_4ee567b65d67e')) {function content_4ee567b65d67e($_smarty_tpl) {?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
	
	
<style>
<!--
#message-nav{
	float:left;
	display:inline;
	margin-left:10px;
	margin-top:20px;
	width:140px;
	font-size:12px;
	line-height:24px;
	font-weight:bold;
}
#message-nav ul{
	margin-left: 0;
	padding-left: 0;
}
#message-nav ul li{
	list-style: none;
}

#message-area{
	float:left;
	display:inline;
	margin-left:5px;
	width:560px;
	border:#000 1px solid;
}

#boxTitle{
	font-weight:bold;
}

.message-unit{
	clear:both;
	border-bottom:1px solid #ccc;
	margin-bottom:4px;
}
.fromUserDiv{
	width:130px;
	float:left;
	padding:4px;
	display:inline;
	border-right:1px solid #ccc;
	font-size:10px;
}
.messageSubjet{
	width:300px; 
	overflow:hidden;
	float:left;
	font-size:12px;
	font-weight:bold;
	padding-top:4px;
	border-right:1px solid #ccc;
}
-->
</style>

	<div id="message-nav">Folders
		<ul>
			<li id="inboxLink"><a href="?folder=inbox"><?php echo $_smarty_tpl->tpl_vars['lang']->value['inbox'];?>
</a></li>
			<li id="sentboxLink"><a href="?folder=sentbox"><?php echo $_smarty_tpl->tpl_vars['lang']->value['sentbox'];?>
</a></li>
			<li id="outboxLink"><a href="?folder=outbox"><?php echo $_smarty_tpl->tpl_vars['lang']->value['outbox'];?>
</a></li>
			<li id="saveboxLink"><a href="?folder=savebox"><?php echo $_smarty_tpl->tpl_vars['lang']->value['savebox'];?>
</a></li>
		</ul>
	</div>
	
	<div id="message-area">
	<div id="boxTitle">Inbox</div>

		<div class="message-unit">
		    <div class="fromUserDiv">from_user</div>
		    <div class="messageSubjet">subject</div>
			<div style="clear:both;"></div>
			<table id="messageTable"></table>
		</div>
		
		<!--end of message unit-->
		
        <div style="clear:both;"></div>
    </div>

	
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
        <a href="/citas-en-linea-acerca.php">Acerca de AmigoCupido</a>
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