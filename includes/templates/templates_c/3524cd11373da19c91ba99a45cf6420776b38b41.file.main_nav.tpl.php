<?php /* Smarty version Smarty-3.1.3, created on 2011-11-18 15:29:06
         compiled from "/home/edwin/sites/amigocupido/includes/templates/main_nav.tpl" */ ?>
<?php /*%%SmartyHeaderCode:12191464494eb550115dd752-02519994%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3524cd11373da19c91ba99a45cf6420776b38b41' => 
    array (
      0 => '/home/edwin/sites/amigocupido/includes/templates/main_nav.tpl',
      1 => 1321658942,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '12191464494eb550115dd752-02519994',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.3',
  'unifunc' => 'content_4eb550115f85c',
  'variables' => 
  array (
    'login_link' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_4eb550115f85c')) {function content_4eb550115f85c($_smarty_tpl) {?>  <div id="topNav"> 
	<a href="/">Inicio</a> | 
	<a href="/gente/">Gente</a> |
	<a href="/fotos/">Fotos</a> |
	<a href="/citas_en_linea_blog/">Articulos</a> |
	<a href="/contacto/">Contactanos</a>
	<?php if (isset($_smarty_tpl->tpl_vars['login_link']->value)){?>
	<?php echo $_smarty_tpl->tpl_vars['login_link']->value;?>

	<?php }?>
  </div><?php }} ?>