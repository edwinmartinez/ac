<?php 
define('IN_PHPBB', true);
include('includes/config.php'); 

session_start();


$mode = ( isset($HTTP_GET_VARS['mode']) ) ? $HTTP_GET_VARS['mode'] : $HTTP_POST_VARS['mode'];
$step = ( isset($HTTP_GET_VARS['step']) ) ? $HTTP_GET_VARS['step'] : $HTTP_POST_VARS['step'];
$mode = htmlspecialchars($mode);

if ( $mode == 'register' ) {
//f($_POST['step'] == 1){ 
	array_map("trim",$_POST);
	$error = array();
	
	$required = array('about_me');
	
	$strip_var_list = array(
	    'about_me', 'about_ideal_mate', 'turn_ons','turn_offs','last_reading'
	);
	$strip_var_list['confirm_code'] = 'confirm_code';

	// Remove doubled up spaces
	//$username = preg_replace('#\s+#', ' ', $username);
	//$username = preg_replace('#_+#', '_', $username);
	
	$protect = array(
	   "<" => "&lt;",
	   ">" => "&gt;",
	   "&" => "&amp;",
	   "\"" => "&quot;",
	   //"\0" => " ",
	   "\x0B" => "",
	   "\|" => "",
	   "%" => ""
	);	
	
	//old way was to $ = strtr($nick, $protect);
	foreach($strip_var_list as $var) {
		if ( !empty($$var) ) {
			//$$var = strtr($$var,$protect) ;
			foreach($protect as $checkfor=>$replacement){
			    //echo $checkfor . " in " . $$var . "<br>";
				$$var = preg_replace('#_+#', '_', $$var); //remove double _
				$$var = preg_replace('#\s+#', ' ', $$var); //remove double space
				$$var = preg_replace('/\\\+/', '', $$var);//remove \ backslash (weird problem)
				//echo $$var . "<br>";
				//$var = preg_replace($checkfor, $replacement, $$var);
			}
			
		} 
	}
	
	foreach($required as $var) {
		if (empty($$var) ) { $error[$var] =  $lang['this_field_is_required']; }
	}

	
    

	// Strip all tags from data ... may p**s some people off, bah, strip_tags is
	// doing the job but can still break HTML output ... have no choice, have
	// to use htmlspecialchars ... be prepared to be moaned at.
	while( list($var, $param) = @each($strip_var_list) ){
		if ( !empty($HTTP_POST_VARS[$param]) ) {
			$$var = trim(htmlspecialchars($HTTP_POST_VARS[$param])) ;
		}
	}
	



	// If we have an error then let's give back the user the registration form step 1
	// if not we continue registering the user 
	if(!empty($error)) {
		print_form();
		exit;
	}


	$now = date("Y-d-m H:i:s");
	
	// insert into db----------------------
		//SET col_name1=expr1 [, col_name2=expr2 ...]
        //[WHERE where_definition]
	$sql = "UPDATE " . USERS_TABLE ." SET ";
		
		$sql .= " about_me=" . GetSQLValueString($about_me, "text"); //this is req.
		if(!empty($my_ideal_mate)) { $sql .= ", my_ideal_mate=" . GetSQLValueString($my_ideal_mate, "text"); } //text
		if(!empty($turn_ons)) { $sql .= ", turn_ons=" . GetSQLValueString($turn_ons, "text"); } //text
		if(!empty($turn_offs)) { $sql .= ", turn_offs=" . GetSQLValueString($turn_offs, "text"); } //text
		if(!empty($relation_type)) { $sql .= ", relation_type=" . GetSQLValueString($relation_type, "int"); } //tinyint(3)
		if(!empty($marital_status)) { $sql .= ", marital_status=" . GetSQLValueString($marital_status, "int"); } //tinyint(4)
		if(!empty($race)) { $sql .= ", race=" . GetSQLValueString($race, "int"); } //tinyint(4)
		if(!empty($religion)) { $sql .= ", religion=" . GetSQLValueString($religion, "int"); } //tinyint(4)
		if(!empty($drink_habit)) { $sql .= ", drink_habit=" . GetSQLValueString($drink_habit, "int"); } //tinyint(4)
		if(!empty($smoke_habit)) { $sql .= ", smoke_habit=" . GetSQLValueString($smoke_habit, "int"); } //tinyint(4)
		if(!empty($have_kids)) { $sql .= ", have_kids=" . GetSQLValueString($have_kids, "int"); } //tinyint(4)
		if(!empty($want_kids)) { $sql .= ", want_kids=" . GetSQLValueString($have_kids, "int"); } //tinyint(4)
		if(!empty($income)) { $sql .= ", income=" . GetSQLValueString($income, "int"); } //tinyint(4)
		if(!empty($education)) { $sql .= ", education=" . GetSQLValueString($education, "int"); } //tinyint(4)
		if(!empty($employment_status)) { $sql .= ", employment_status=" . GetSQLValueString($employment_status, "int"); } //tinyint(4)
		if(!empty($occupational_area)) { $sql .= ", occupational_area=" . GetSQLValueString($occupational_area, "int"); } //smallint(6)
		if(!empty($exercise_freq)) { $sql .= ", exercise_freq=" . GetSQLValueString($exercise_freq, "int"); } //tinyint(4)
		if(!empty($last_reading)) { $sql .= ", last_reading=" . GetSQLValueString($last_reading, "text"); } //text
		if(!empty($lang_spoken)) { $sql .= ", lang_spoken=" . GetSQLValueString(implode($lang_spoken, ","), "text"); } //set
		if(!empty($weight_lb)) { $sql .= ", weight_lb=" . GetSQLValueString($weight_lb, "int"); } //smallint(5)
		if(!empty($weight_kg)) { $sql .= ", weight_kg=" . GetSQLValueString($weight_kg, "int"); } //float
		if(!empty($height_cm)) { $sql .= ", height_cm=" . GetSQLValueString($height_cm, "int"); } //smallint(5)
		if(!empty($eyes_color)) { $sql .= ", eyes_color=" . GetSQLValueString($eyes_color, "int"); } //tinyint(4)
		if(!empty($hair_color)) { $sql .= ", hair_color=" . GetSQLValueString($hair_color, "int"); } //tinyint(4)
		
		$sql .= " WHERE user_id=" . $_SESSION['user_id'] . " LIMIT 1";

/* 		. 'NOW() ';
		
		if(!empty($city)) { $sql .= ", ".GetSQLValueString($city,"text"); }
		if(!empty($state_id)) { $sql .= ", ".GetSQLValueString($state_id,"int");
		} elseif(!empty($state_desc)) { $sql .= ", ".GetSQLValueString($state_desc,"text");}
		if(!empty($postal_code)) { $sql .= ", ".GetSQLValueString($postal_code,"text"); }
		
		
		$sql .= ")";	 */	   
					  
					   
		//echo $sql . "<br>\n" . $now . "<br>";			   
					   
	
	mysql_connect($dbhost, $dbuser, $dbpasswd) or
	   die("Could not connect: " . mysql_error());
	mysql_select_db($dbname);
	
	//$result = mysql_query($sql) or die(mysql_error());
	 if ( !mysql_query($sql) ) {
		//message_die(CRITICAL_ERROR, 'Error updating users table', '', __LINE__, __FILE__, $sql);
		echo "Critical Error Error updating users table on line ". __LINE__ ."<br> in file: ". __FILE__ . "<br>statement: ". $sql ;
		die(mysql_error());
	} 
	
	// let's insert into phpbb users but first let's get the users id since it is not auto_increment by default
	// we will do the same way phpbb does it

	
	// create a script that takes that confirmationn looks into unconfirmed users for the number and confirms the user
	// takes him to the login page. If it's the first time login in then take him to the edit profile section which
	// is nice.
	
	//I would have him log in but it's not cool if someone snooping on confirmation email
	// is able to log in just by clicking the conf link
	
	//print header("Location: registrate2.php");
	print_header();
	print_photo_form();
	exit;


}
else{
	print_header();
	print_form();
}



function print_header() {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/citasenlinea01.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="shortcut icon" href="/favicon.ico" />
<meta name="Description" content="Citas en linea un servicio de citas en linea para hispanos. Citas en linea is a Latin Dating Service" />
<meta name="description" lang="es" content="Servicio de citas en línea en donde podrás conocer, de una manera facil y simple, a otros solteros en Latinoamérica y el resto del mundo en busca de amor y amistad." />
<meta name="Keywords" content="citas, citas en linea, citas con hispanos, latin, hispanos, hispanics, latin datin, dating,online dating louisville online dating service, adult dating, free online dating, dating site, dating services, free dating, single dating, christian dating, christian dating service, dating web site, gay dating, internet dating, community dating general, jewish dating, hispanic dating service, dating personals,  latinas, personals dating services,  personals for single and dating, internet personals dating, International Dating  Services, latin personals, latin american personals, latin  international personals, personals latin america,  latin meet, hot single latinas, latin  single dating, sexy latin single, latin single, latin single  connection, single latin female, hispanic latin single, latin american single, latin single web  site, latin dating, latin dating services, latin dating " />
<!-- InstanceBeginEditable name="doctitle" -->
<title>AmigoCupido - Citas en Linea | Citas con hispanos</title>
<!-- InstanceEndEditable -->
<link href="style_std.css" rel="stylesheet" type="text/css" />
<!-- InstanceBeginEditable name="head" -->
<link href="forms01.css" rel="stylesheet" type="text/css" />
<script language="javascript">
function validate() {
	var f = document.form1;

	if ( f.about_me.value == '' ) {
		alert ("Tienes que decirnos un poco acerca de ti.");
		f.about_me.focus()
		changeClass('aboutMeArea','error');
		return
	}
	changeClass('aboutMeArea','required');
	
	if ( f.weight.value != '' ) {
		if(!checkNumeric(f.weight,50,500,',','.','')) {
			changeClass('weightArea','error');
			//f.weight.focus();
			return
		}
		if(f.weight_unit.value == '') {
			alert ("Por favor escoje la unidad de peso (libras o kilos).");
			f.weight_unit.focus()
			changeClass('weightArea','error');
			return
		}
		set_weight();
		changeClass('weightArea','optional');
	}
	    
	f.submit()	
}



function changeClass(Elem, myClass) {
	var elem;
	if(document.getElementById) {
		var elem = document.getElementById(Elem);
	} else if (document.all){
		var elem = document.all[Elem];
	}
	elem.className = myClass;
}
// --------------------- Set the weight  -------------------
function set_weight() {
	var f = document.form1;
	var weight = f.weight.value;
	var units = f.weight_unit.value
	if (units == 'lb') {
		f.weight_lb.value = Math.round(f.weight.value);
		f.weight_kg.value = Math.round((f.weight.value*.45)*10)/10;
	}
	else if(units == 'kg') {
		f.weight_kg.value = Math.round(f.weight.value*10)/10;
		f.weight_lb.value = Math.round(f.weight.value*2.2);
	}
}
//---------------------- check numeric --------------------
function checkNumeric(objName,minval, maxval,comma,period,hyphen)
{
	var numberfield = objName;
	if (chkNumeric(objName,minval,maxval,comma,period,hyphen) == false)
	{
		numberfield.select();
		numberfield.focus();
		return false;
	}
	else
	{
		return true;
	}
}

function chkNumeric(objName,minval,maxval,comma,period,hyphen)
{
// only allow 0-9 be entered, plus any values passed
// (can be in any order, and don't have to be comma, period, or hyphen)
// if all numbers allow commas, periods, hyphens or whatever,
// just hard code it here and take out the passed parameters
var checkOK = "0123456789" + comma + period + hyphen;
var checkStr = objName;
var allValid = true;
var decPoints = 0;
var allNum = "";

for (i = 0;  i < checkStr.value.length;  i++)
{
ch = checkStr.value.charAt(i);
for (j = 0;  j < checkOK.length;  j++)
if (ch == checkOK.charAt(j))
break;
if (j == checkOK.length)
{
allValid = false;
break;
}
if (ch != ",")
allNum += ch;
}
if (!allValid)
{	
alertsay = "Por favor, escribe solo estos valores \""
alertsay = alertsay + checkOK + "\" en la casilla de \"" + checkStr.name + "\" "
alert(alertsay);
return (false);
}

// set the minimum and maximum
var chkVal = allNum;
var prsVal = parseInt(allNum);
if (chkVal != "" && !(prsVal >= minval && prsVal <= maxval))
{
alertsay = "Por favor pon un peso mayor o "
alertsay = alertsay + "igual a \"" + minval + "\" y menor o "
alertsay = alertsay + "igual a \"" + maxval + "\" en la  casilla de \"" + checkStr.name + "\" "
alert(alertsay);
return (false);
}
}

</script>
<!-- InstanceEndEditable --><!-- InstanceParam name="content" type="boolean" value="false" --><!-- InstanceParam name="contentWide" type="boolean" value="true" -->
</head>

<body>
<div id="headerWords">
  <h1>AmigoCupido.com - Servicio de Citas en linea y buscar pareja. Diviertete Encontrando tu amor. Latin Dating Service</h1>
</div>
<div id="container"><!-- #BeginLibraryItem "/Library/topNav.lbi" -->
		<div id="logo">
<a href="http://www.citasenlinea.net/"><img src="/images/spacer.gif" alt="CitasEnLinea.NET" title="Citas En Linea" width="319" height="80" border="0" /></a></div>
		<div id="topNav">
		<a href="/">Inicio</a> | <a href="/citas_en_linea_blog/">Articulos</a> | <a href="/citas-en-linea-acerca.php">Acerca de Amigo Cupido</a> | <a href="/citas_en_linea_contacto.php">Contactanos</a>		</div>
<!-- #EndLibraryItem --><!-- InstanceBeginEditable name="EditRegion5" -->

    <div id="homeContentWide">
	
	<?php
} // end of header
function print_form() {
global $lang, $lang_relation_type, $lang_marital_status, $lang_race, $lang_religion, $lang_drink_habit, $lang_smoke_habit;
global $lang_have_kids, $lang_want_kids, $lang_income, $lang_education, $lang_exercise_freq, $lang_employment_status;
global $lang_occupational_areas, $lang_height_cm, $lang_eyes_color,$lang_hair_color,$lang_body_type;
?>
<div id="contentText">
      <p>&nbsp;</p>
	  <strong>Bienvenido <?php echo $_SESSION['first_name']; ?></strong><br />
      <p>Casillas en <span style="color:#CC0000;font-weight:bold;">Rojo</span> son requeridas.</p>
</div>
	  <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" enctype="multipart/form-data" name="form1">
    <input type="hidden" name="step" value="profile" />
	<input type="hidden" name="mode" value="register" />
	<input type="hidden" name="weight_lb" value="" />
	<input type="hidden" name="weight_kg" value="" />
	<input type="hidden" name="id" value="<?php echo $_SESSION['user_id']; ?>" />
	  <fieldset>
	  
    <legend>Informacion Basica</legend>
      
      
      <div class="required" id="aboutMeArea">
        <label for="first_name">Como es tu personalidad?</label>
        <textarea name="about_me"></textarea>
			<small>
			
			Comienza por describir tu personalidad y tu mejores cualidades. Podrías escribir acerca de tu trabajo, familia o mascotas, tu origen y metas, tus viajes, dónde creciste o describir la foto que agregaste. En la próxima pagina podrás describir tu pasatiempos y físico. Por favor no incluyas URL de otros sitios web, emails, tu nombre completo o alguna otra informacion de contacto, puede ser sacado por los moderadores. 
			</small>
      </div>
	  
	  <div class="optional" id="myIdealMateArea">
        <label for="first_name">Como seria la persona que te gustaria conocer?</label><br />
        <textarea name="my_ideal_mate" id="my_ideal_mate"></textarea>	
		<small>Proporciona una descripción acerca de tu pareja o amistad ideal para dar a las demás personas más información a cerca de lo que estás buscando.</small>	
      </div>
	  
	  <div class="optional" id="turnOns">
        <label for="turn_ons">Lo que m&aacute;s me gusta de una persona:</label><br />
        <textarea name="turn_ons" id="turn_ons"></textarea>
        <small> </small>      </div>
	  
	  <div class="optional" id="turnOffs">
        <label for="turn_offs">Lo que menos me gusta de una persona:</label><br />
        <textarea name="turn_offs" id="turn_offs"></textarea>	
		<small> </small>		
      </div>	  

	  
	  
	  <div class="optional" id="relationTypeArea">
	  <label for="relation_type">Tipo de Relacion</label>
		<select name="relation_type">
		<?php  
		foreach ($lang_relation_type as $key=>$value) {
			echo "<option value=\"".$key."\">".$value."</option>\n";
		}?>
		</select>  
      </div>
	  

	  
	  
	  <div class="optional" id="maritalStatusArea">
	  <label for="maritalStatus">Estado Civil</label>
	    <select name="marital_status">
		<?php  
		foreach ($lang_marital_status as $key=>$value) {
			echo "<option value=\"".$key."\">".$value."</option>\n";
		}?>
        </select>
      </div>
	  
	  
	  <div class="optional" id="race">
	  <label for="race">Raza</label>
	    <select name="race">
		<?php  
		foreach ($lang_race as $key=>$value) {
			echo "<option value=\"".$key."\">".$value."</option>\n";
		}?>
        </select>
      </div>
	  
	  
	  <div class="optional" id="religion">
	  <label for="religion">Religion</label>
	  <select name="religion">
		<?php  
		foreach ($lang_religion as $key=>$value) {
			echo "<option value=\"".$key."\">".$value."</option>\n";
		}?>
            </select>
	  </div>
	  
	  
	 
	 </fieldset>
	 
	 <fieldset>
	 
	      <legend>Estilo de Vida</legend>
		  	  
	  
	  <div class="optional" id="drinkHabit">
	  <label for="drinkHabit">Habito de Bebida</label>
	  <select name="drink_habit">
        <?php  
		foreach ($lang_drink_habit as $key=>$value) {
			echo "<option value=\"".$key."\">".$value."</option>\n";
		}?>
      </select>
	  </div>
	  
	  
	  <div class="optional" id="smokeHabit"> 
	  <label for="smokeHabit">Habito de Fumar </label>
	  	    <select name="smoke_habit">
		<?php  
		foreach ($lang_smoke_habit as $key=>$value) {
			echo "<option value=\"".$key."\">".$value."</option>\n";
		}?>
          </select>
	  </div>
		  
	  	  <div class="optional" id="haveKids">
	  <label for="haveKids">Hijos</label>
	    <select name="have_kids" size="1">
		<?php  
		foreach ($lang_have_kids as $key=>$value) {
			echo "<option value=\"".$key."\">".$value."</option>\n";
		}?>
        </select>
      </div>
	   
	   
	  <div class="optional" id="wantKids">
	  <label for="wantKids">Deseas hijos</label>
	    <select name="want_kids" size="1">
		<?php  
		foreach ($lang_want_kids as $key=>$value) {
			echo "<option value=\"".$key."\">".$value."</option>\n";
		}?>
        </select>
      </div>
	  
	 <div class="optional" id="income">
	  <label for="income">Ingreso Anual</label>
	    <select name="income" size="1">
		<?php  
		foreach ($lang_income as $key=>$value) {
			echo "<option value=\"".$key."\">".$value."</option>\n";
		}?>
        </select>
      </div>
	  
	  	  <div class="optional" id="education">
	  <label for="education">Educacion</label>
	    <select name="education" size="1">
		<?php  
		foreach ($lang_education as $key=>$value) {
			echo "<option value=\"".$key."\">".$value."</option>\n";
		}?>
        </select>
      </div>
	  
	 <div class="optional" id="employmentArea">
	  <label for="employment_status">Estado de Empleo</label>
	    <select name="employment_status" size="1">
		<?php  
		foreach ($lang_employment_status as $key=>$value) {
			echo "<option value=\"".$key."\">".$value."</option>\n";
		}?>
        </select>
		
      </div>
	  
	  
	 <div class="optional" id="employmentArea">
	  <label for="ocupation">Ocupacion</label>
<select name="occupational_area">
<?php  
foreach ($lang_occupational_areas as $key=>$value) {
	echo "<option value=\"".$key."\">".$value."</option>\n";
}?>
<option value="255">Otra</option>
</select>
		
      </div>	  
	  
		<div class="optional" id="exerciseFreqArea">
		<label for="exercise_freq">¿Que tan frequente haces ejercicio?</label>
	    <select name="exercise_freq">
	      <option value="0"></option>
		<?php  
		foreach ($lang_exercise_freq as $key=>$value) {
			echo "<option value=\"".$key."\">".$value."</option>\n";
		}?>
        </select>
		</div>
	  
	  	  
	  </fieldset>
	  
	  	 
	 <fieldset>
	 
	      <legend>Intereses</legend>
		  
	  <div class="optional" id="lastReading">
			<label for="last_reading">¿Cual ha sido tu ultima lectura?</label>
		    <textarea name="last_reading"></textarea>
	      <small>(Nombre de libros, novelas, etc.)</small>		</div>
		
		
   
	 <label>¿Que lenguages hablas o entiendes?</label><br />
	 <br />
<table border="0">
<tr><td valign="top" width="230">
	<label class="labelCheckbox"><input type="checkbox" name="lang_spoken[]" class="inputCheckbox" value="es" checked="checked" /> Español</label>
	<label class="labelCheckbox"><input type="checkbox" name="lang_spoken[]" class="inputCheckbox" value="en" /> Inglés</label><br />
	<label class="labelCheckbox"><input type="checkbox" name="lang_spoken[]" class="inputCheckbox" value="de" /> Alemán</label>
	<label class="labelCheckbox"><input type="checkbox" name="lang_spoken[]" class="inputCheckbox" value="ar" /> Arabe</label>
	<label class="labelCheckbox"><input type="checkbox" name="lang_spoken[]" class="inputCheckbox" value="ca" /> Catalan</label>	
	<label class="labelCheckbox"><input type="checkbox" name="lang_spoken[]" class="inputCheckbox" value="zh" /> Chino</label>
	<label class="labelCheckbox"><input type="checkbox" name="lang_spoken[]" class="inputCheckbox" value="ko" /> Coreano</label>
	<label class="labelCheckbox"><input type="checkbox" name="lang_spoken[]" class="inputCheckbox" value="fi" /> Finlandés</label>	
	<label class="labelCheckbox"><input type="checkbox" name="lang_spoken[]" class="inputCheckbox" value="fr" /> Francés</label>
	<label class="labelCheckbox"><input type="checkbox" name="lang_spoken[]" class="inputCheckbox" value="gl" /> Gaélico</label>	
	<label class="labelCheckbox"><input type="checkbox" name="lang_spoken[]" class="inputCheckbox" value="he" /> Hebreo</label>
	<label class="labelCheckbox"><input type="checkbox" name="lang_spoken[]" class="inputCheckbox" value="nl" /> Holandés</label>		
</td>
<td valign="top" width="230">
	<label class="labelCheckbox"><input type="checkbox" name="lang_spoken[]" class="inputCheckbox" value="hi" /> Indú</label>	
	<label class="labelCheckbox"><input type="checkbox" name="lang_spoken[]" class="inputCheckbox" value="it" /> Italiano</label>	
	<label class="labelCheckbox"><input type="checkbox" name="lang_spoken[]" class="inputCheckbox" value="ja" /> Japonés</label>	
	<label class="labelCheckbox"><input type="checkbox" name="lang_spoken[]" class="inputCheckbox" value="no" /> Noruego</label>	
	<label class="labelCheckbox"><input type="checkbox" name="lang_spoken[]" class="inputCheckbox" value="pl" /> Polaco</label>	
	<label class="labelCheckbox"><input type="checkbox" name="lang_spoken[]" class="inputCheckbox" value="pt" /> Portugués</label>	
	<label class="labelCheckbox"><input type="checkbox" name="lang_spoken[]" class="inputCheckbox" value="ru" /> Ruso</label>	
	<label class="labelCheckbox"><input type="checkbox" name="lang_spoken[]" class="inputCheckbox" value="sv" /> Sueco</label>			
	<label class="labelCheckbox"><input type="checkbox" name="lang_spoken[]" class="inputCheckbox" value="tg" /> Tagalog</label>		
	<label class="labelCheckbox"><input type="checkbox" name="lang_spoken[]" class="inputCheckbox" value="tr" /> Turco</label>	
	<label class="labelCheckbox"><input type="checkbox" name="lang_spoken[]" class="inputCheckbox" value="ot" /> Otro</label>
	</td><td>&nbsp;
</td></tr>
</table>				
        
	  </fieldset>
	  
	 <fieldset>
	 
	      <legend>Apariencia Fisica</legend>	
		  
		  
		<div class="optional" id="weightArea">
        <label for="weight">Cuanto Pesas:</label>
			<input type="text" name="weight" id="weight" class="inputText" size="6" maxlength="5" 
			value="" /> <select name="weight_unit" onchange="javascript:set_weight()">
					<option value="">Unidad de Peso</option>
					<option value="lb">Libras</option>
					<option value="kg">Kilos</option>
					</select>
		<small>(Solo Digitos)</small>
		</div>  
		
		
		<div class="optional" id="heightArea">
		<label for="height_cm">¿Cual es tu altura?</label>
	    <select name="height_cm">
		<?php  
		foreach ($lang_height_cm as $key=>$value) {
			echo "<option value=\"".$key."\">".$value."</option>\n";
		}?>
        </select>
		</div>
		
		
		<div class="optional" id="eyeColorArea">
		<label for="eyes_color">Color de ojos</label>
	    <select name="eyes_color">
		<?php  
		foreach ($lang_eyes_color as $key=>$value) {
			echo "<option value=\"".$key."\">".$value."</option>\n";
		}?>
        </select>
		</div>	
		
		
		<div class="optional" id="hairColorArea">
		<label for="hair_color">Color de cabello</label>
	    <select name="hair_color">
		<?php  
		foreach ($lang_hair_color as $key=>$value) {
			echo "<option value=\"".$key."\">".$value."</option>\n";
		}?>
        </select>
		</div>			
		
	</fieldset>  
	
	
	<fieldset>
	  
      <div class="submit">
        <div>
          <input type="button" class="inputSubmit" value="<?php echo $lang['save']; ?> &raquo;" onclick="validate()" />
		 <!-- <input type="submit" /> -->
        </div>
      </div>
    </fieldset>
</form>

<?php
} //end of print form
  
function print_photo_form() {
global $lang;

	if (!isset($number_of_fields)) $number_of_fields = 1;
  // Lets build the Image Uploading fields
		 for($counter=1; $counter <= $number_of_fields;$counter++) {
		   $photo_upload_fields .= <<<__HTML_END
		   <div class="optional">
		<label>Foto {$counter}:</label>
		 <input name="photo_filename[]" type="file" />
		</div>
		
		<div class="optional">
		<label> Caption:</label>
		 <textarea name="photo_caption[]" cols="30" rows="1"></textarea>
		</div>
__HTML_END;
	 }
?>	 
	 <fieldset>
	 <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" enctype="multipart/form-data" name="form1">
	 <input type="hidden" name="step" value="photoup" />
	 <input type="hidden" name="mode" value="register" />
	 	<legend>Si tienes una foto subela!</legend>
		<?php echo $photo_upload_fields; ?>
	</form>
	</fieldset>
	<fieldset>
	  
      <div class="submit">
        <div>
          <input type="button" class="inputSubmit" value="<?php echo $lang['save']; ?> &raquo;" onclick="validate()" />
		 <!-- <input type="submit" /> -->
        </div>
      </div>
    </fieldset>
<? 
}
?> 
	
	</div>
	<!-- InstanceEndEditable --></div>


</body>
<!-- InstanceEnd --></html>