<?php /* Smarty version Smarty-3.1.3, created on 2011-12-13 03:57:38
         compiled from "/home/edwin/sites/amigocupido/includes/templates/block_findFriends.tpl" */ ?>
<?php /*%%SmartyHeaderCode:6026229474ec83ad56e72f3-13440457%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '92fa9025f992d082700677ad0dfb4f94988cfc54' => 
    array (
      0 => '/home/edwin/sites/amigocupido/includes/templates/block_findFriends.tpl',
      1 => 1321818894,
      2 => 'file',
    ),
    'c7b51ed01d1cd0c536327ebfca6fe33b4995e565' => 
    array (
      0 => '/home/edwin/sites/amigocupido/includes/templates/index.html',
      1 => 1323657353,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '6026229474ec83ad56e72f3-13440457',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.3',
  'unifunc' => 'content_4ec83ad5e7d38',
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
<?php if ($_valid && !is_callable('content_4ec83ad5e7d38')) {function content_4ec83ad5e7d38($_smarty_tpl) {?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
	
	

<table width="730" border="0" cellpadding="0" cellspacing="0">
  <tr valign="top">
    <td width="180">
	 <div id="searchBox" class="leftBox">
            <h2><?php echo $_smarty_tpl->tpl_vars['lang']->value['people_search'];?>
<a href="#"></a></h2>
			
		<div class="leftBoxSearchContent">
			<form id="form1" name="form1" method="post">
			<input type="hidden" id="p" value="1" />
			<div class="searchItem">
				<strong><?php echo $_smarty_tpl->tpl_vars['lang']->value['gender'];?>
:</strong><br />
				
                <input id="f" name="f" type="checkbox" value="1" <?php if ($_smarty_tpl->tpl_vars['data']->value['check_f']==true){?> checked="checked" <?php }?> />
                <?php echo $_smarty_tpl->tpl_vars['lang']->value['woman'];?>
 
                           
                <input id="m" name="m" type="checkbox" value="1" <?php if ($_smarty_tpl->tpl_vars['data']->value['check_m']==true){?> checked="checked" <?php }?> /> <?php echo $_smarty_tpl->tpl_vars['lang']->value['man'];?>

			</div>
			
			<div class="searchItem"> 
				<strong><?php echo $_smarty_tpl->tpl_vars['lang']->value['age'];?>
:</strong><br />
				<?php echo $_smarty_tpl->tpl_vars['lang']->value['between'];?>
 <strong><span id="min_age_txt"><?php echo $_smarty_tpl->tpl_vars['data']->value['min_age'];?>
</span></strong> <?php echo $_smarty_tpl->tpl_vars['lang']->value['and'];?>

	 			<strong><span id="max_age_txt"><?php echo $_smarty_tpl->tpl_vars['data']->value['max_age'];?>
</span></strong> 

                <input id="min_age" name="min_age" type="hidden" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['min_age'];?>
" />
			  	<input id="max_age" name="max_age" type="hidden" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['max_age'];?>
" />
				 
              Anos
		  
				<div id="track6" style="width:200px;background-color:#aaa;height:5px;position:relative; margin-top:6px; margin-bottom:14px">
					<div id="handle6-1" style="position:absolute;top:0;left:0;width:11px;height:18px;background-image:url(../images/horizontal_handle.gif); background-position:0px -3px;"> </div>
					<div id="handle6-2" style="position:absolute;top:0;left:0;width:11px;height:18px;background-image:url(../images/horizontal_handle.gif); background-position:0px -3px;"> </div>
				</div>
			</div>
			
			<div class="searchItem">
				<strong><?php echo $_smarty_tpl->tpl_vars['lang']->value['country'];?>
</strong><br />
					<?php echo $_smarty_tpl->tpl_vars['countries_menu']->value;?>

			</div>
	
			<div class="searchItem">
					<input id="photo_only" type="checkbox" name="photo_only" value="1" <?php if ($_smarty_tpl->tpl_vars['data']->value['photo_only']==true){?> checked="checked" <?php }?> />
				<?php echo $_smarty_tpl->tpl_vars['lang']->value['only_profiles_with_photos'];?>
<br />
			</div>
			
			<div class="searchItem">	
				   <?php echo $_smarty_tpl->tpl_vars['lang']->value['results_per_page'];?>

				  <select id="rpp" name="rpp">
				  
				 <script language="javascript"> 
				 //debugger;
				 if (get_cookie("rpp")!="" && get_cookie("rpp")== 20) 
				 	document.write('<option value="20" selected="selected" >20</option>');
				else document.write('<option value="20" >20</option>');
				if (get_cookie("rpp")!="" && get_cookie("rpp")== 40) 
				 	document.write('<option value="40" selected="selected" >40</option>');
				else document.write('<option value="40" >40</option>');	
				if (get_cookie("rpp")!="" && get_cookie("rpp")== 60) 
				 	document.write('<option value="60" selected="selected" >60</option>');
				else document.write('<option value="60" >60</option>');
				</script>
				</select><br />
			</div>
	
				</form>
		</div>
		
		<h2><?php echo $_smarty_tpl->tpl_vars['lang']->value['by_city'];?>
</h2>
		<div class="leftBoxSearchContent" style="text-align:center;">    
			<div><input id="user_city" name="user_city" type="text"  /></div>
			<div>
			<input id="search_button" type="button" name="Submit" value="<?php echo $_smarty_tpl->tpl_vars['lang']->value['search'];?>
" />
			</div>
		</div>

		
		<h2><?php echo $_smarty_tpl->tpl_vars['lang']->value['by_username'];?>
</h2>
		<div class="leftBoxSearchContent" style="text-align:center;">    
			<div><input id="user_username" name="user_username" type="text"  /></div>
			<div align="center">
				<input id="search_username_button" type="button" name="Submit" value="<?php echo $_smarty_tpl->tpl_vars['lang']->value['search'];?>
" />
			</div>		
		</div>

	</div>	
			
	</td>
	
    <td width="6"><img src="../images/spacer.gif" width="6" height="10" /></td>
	
    <td>
	<table width="476" border="0" cellspacing="0" class="userTable">
        <tr valign="top">
          <td nowrap="nowrap" class="userCellTitle"><?php echo $_smarty_tpl->tpl_vars['lang']->value['our_members'];?>
!</td>
          <td nowrap="nowrap" class="userCellEdit" id="status">&nbsp;</td>
        </tr>
        <tr valign="top">
          <td colspan="2" id="usersCell">
              <div style="width:100%;text-align:center;margin-top:20px;"><img src="../images/icon_spin_32.gif" /></div>
         </td>
        </tr>
        <tr valign="top">
          <td colspan="2">&nbsp;<div id="statusBottom">Area Status</div></td>
        </tr>
      </table></td>
  </tr>
  <tr valign="top">
    <td colspan="3">&nbsp;</td>
  </tr>
</table>


		<script type="text/javascript" language="javascript">
	var f = document.form1;
	var theValues = new Array();
	for(var i=18;i<=89;i++){
		theValues.push(i);
	}
	var slider6 = new Control.Slider(['handle6-1','handle6-2'],'track6',{
			
			sliderValue:[
				'<?php echo $_smarty_tpl->tpl_vars['data']->value['min_age'];?>
',
				'<?php echo $_smarty_tpl->tpl_vars['data']->value['max_age'];?>
'],
			range:$R(18,89),
			values:theValues,
			restricted:true,
			handleImage:['/images/horizontal_handle.gif','/images/horizontal_handle.gif'],
			onSlide:function(v){
				//$('debug6').innerHTML='slide: '+v.inspect();  
				slide_update(v);
			},
			onChange:function(v){
				//$('debug6').innerHTML='changed! '+v.inspect();
				show_page(1);
			}
		});
		
	function slide_update(v){
		var temp = new Array();
		var numbers = v.toString();
		temp = numbers.split(',');
		f.min_age.value = temp[0];
		f.max_age.value = temp[1];
		$('min_age_txt').innerHTML= temp[0];
		$('max_age_txt').innerHTML= temp[1];
	}
	</script>


	
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