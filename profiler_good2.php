<?php 
define('IN_PHPBB', true);
include('includes/config.php'); 

session_start();


$mode = ( isset($HTTP_GET_VARS['mode']) ) ? $HTTP_GET_VARS['mode'] : $HTTP_POST_VARS['mode'];
$step = ( isset($HTTP_GET_VARS['step']) ) ? $HTTP_GET_VARS['step'] : $HTTP_POST_VARS['step'];
$mode = htmlspecialchars($mode);

if ( $mode == 'register' && $step == 'profile') {
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
	$sql = "UPDATE " . SITE_USERS_TABLE ." SET ";
		
		$sql .= " about_me=" . GetSQLValueString($about_me, "text"); //this is req.
		if(!empty($my_ideal_mate))     { $sql .= ", my_ideal_mate=" . GetSQLValueString($my_ideal_mate, "text"); } //text
		if(!empty($turn_ons))          { $sql .= ", turn_ons=" . GetSQLValueString($turn_ons, "text"); } //text
		if(!empty($turn_offs))         { $sql .= ", turn_offs=" . GetSQLValueString($turn_offs, "text"); } //text
		if(!empty($relation_type))     { $sql .= ", relation_type=" . GetSQLValueString($relation_type, "int"); } //tinyint(3)
		if(!empty($marital_status))    { $sql .= ", marital_status=" . GetSQLValueString($marital_status, "int"); } //tinyint(4)
		if(!empty($race))              { $sql .= ", race=" . GetSQLValueString($race, "int"); } //tinyint(4)
		if(!empty($religion))          { $sql .= ", religion=" . GetSQLValueString($religion, "int"); } //tinyint(4)
		if(!empty($drink_habit))       { $sql .= ", drink_habit=" . GetSQLValueString($drink_habit, "int"); } //tinyint(4)
		if(!empty($smoke_habit))       { $sql .= ", smoke_habit=" . GetSQLValueString($smoke_habit, "int"); } //tinyint(4)
		if(!empty($have_kids))         { $sql .= ", have_kids=" . GetSQLValueString($have_kids, "int"); } //tinyint(4)
		if(!empty($want_kids))         { $sql .= ", want_kids=" . GetSQLValueString($have_kids, "int"); } //tinyint(4)
		if(!empty($income))            { $sql .= ", income=" . GetSQLValueString($income, "int"); } //tinyint(4)
		if(!empty($education))         { $sql .= ", education=" . GetSQLValueString($education, "int"); } //tinyint(4)
		if(!empty($employment_status)) { $sql .= ", employment_status=" . GetSQLValueString($employment_status, "int"); } //tinyint(4)
		if(!empty($occupational_area)) { $sql .= ", occupational_area=" . GetSQLValueString($occupational_area, "int"); } //smallint(6)
		if(!empty($exercise_freq))     { $sql .= ", exercise_freq=" . GetSQLValueString($exercise_freq, "int"); } //tinyint(4)
		if(!empty($last_reading))      { $sql .= ", last_reading=" . GetSQLValueString($last_reading, "text"); } //text
		if(!empty($lang_spoken))       { $sql .= ", lang_spoken=" . GetSQLValueString(implode($lang_spoken, ","), "text"); } //set
		if(!empty($weight_lb))         { $sql .= ", weight_lb=" . GetSQLValueString($weight_lb, "int"); } //smallint(5)
		if(!empty($weight_kg))         { $sql .= ", weight_kg=" . GetSQLValueString($weight_kg, "int"); } //float
		if(!empty($body_type))         { $sql .= ", body_type=" . GetSQLValueString($body_type, "int"); } //tinyint(4)	
		if(!empty($height_cm))         { $sql .= ", height_cm=" . GetSQLValueString($height_cm, "int"); } //smallint(5)
		if(!empty($eyes_color))        { $sql .= ", eyes_color=" . GetSQLValueString($eyes_color, "int"); } //tinyint(4)
		if(!empty($hair_color))        { $sql .= ", hair_color=" . GetSQLValueString($hair_color, "int"); } //tinyint(4)
		
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
	
	mail("thecell@tmomail.net", "New User on AmigoCupido ".$_SESSION['user_id'], $_SERVER['REMOTE_ADDR']);
	
	print_header();
	print_photo_form();
	exit;


}
elseif ( $mode == 'register' && $step == 'photoup') {


	// the $result is an array than contains an array of the status and message for the file uploaded. 
	// like this
	//$result[filenumber] => array(status,message);
	$result = upload_photo();
	
	print_header();
	//----------------------------End of picture upload ----------------------------
	if($result){
		foreach($result as $key => $resultrow) {
			if($resultrow[0] > 0) { //success
				echo $resultrow[1];
				$upload_error = 0;
			}else { 
				echo "<p class=\"error\">".$resultrow[1]."</p>"; //failure
				$upload_error = 1;
				}
		}
	}
	
	if(!$upload_error) {
		echo "<strong>Gracias</strong><br> Estaremos construyendo el area  de perfil en los siguietes 10 dias y te avisaremos cuando este listo.<br />";
	}else{
		print_photo_form();
	}

}
else{
	print_header();
	print_form();
}

function upload_photo() {
//-----------------------------------------------upload photo --------------------------------------------

/*
$filename = 'test.jpg';

// Set a maximum height and width
$width = 200;
$height = 200;

// Content type
header('Content-type: image/jpeg');

// Get new dimensions
list($width_orig, $height_orig) = getimagesize($filename);

if ($width && ($width_orig < $height_orig)) {
   $width = ($height / $height_orig) * $width_orig;
} else {
   $height = ($width / $width_orig) * $height_orig;
}

// Resample
$image_p = imagecreatetruecolor($width, $height);
$image = imagecreatefromjpeg($filename);
imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);

// Output
imagejpeg($image_p, null, 100);

*/


	// initialization
	$result_final = "";
	$counter = 0;
	global $dbname, $dbhost, $dbuser, $dbpasswd, $lang, $upload_error;
	$images_dir = MEMBER_IMG_DIR_PATH;
	// List of our known photo types
	$known_photo_types = array( 
						'image/pjpeg' => 'jpg',
						'image/jpeg' => 'jpg',
						'image/gif' => 'gif',
						//'image/bmp' => 'bmp',
						'image/x-png' => 'png'
					);
	
	// GD Function List
	$gd_function_suffix = array( 
						'image/pjpeg' => 'JPEG',
						'image/jpeg' => 'JPEG',
						'image/gif' => 'GIF',
						//'image/bmp' => 'WBMP',
						'image/x-png' => 'PNG'
					);

	// Fetch the photo array sent by the form
	$photos_uploaded = $_FILES['photo_filename'];
	// Fetch the photo caption array
	$photo_caption = $_POST['photo_caption'];
//echo count($photos_uploaded);
	while( $counter <= count($photos_uploaded) ) {
		if($photos_uploaded['size'][$counter] > 0) {
		//echo $photos_uploaded['type'][$counter] . "<br>";
			if(!array_key_exists($photos_uploaded['type'][$counter], $known_photo_types)){
			// we will return an array where the first item $result_final[0] will be the status of the operation
			$result_final .= $lang['file'] . " ".($counter+1). " " . $lang['is_not_a_photo'] . "<br />";
			$result[$counter] = array(0,$result_final);
				
			}
			else {
				
				//---------------db save -----------------------------
				$filetype = $photos_uploaded['type'][$counter];
				$extention = $known_photo_types[$filetype];
				$filename = $_SESSION['user_id'].".".time().".".$extention;
				$orig_name = $photos_uploaded['name'][$counter];
				
				mysql_connect($dbhost, $dbuser, $dbpasswd) or die("Could not connect: " . mysql_error());
				mysql_select_db($dbname);
				//check if profile pic exists allready
				$sql = "SELECT * from " . USERS_GALLERY_TABLE
				. " WHERE photo_uid =".$_SESSION['user_id']." and use_in_profile=1";
				//if it does delete it - we will replace it with the current one.
				$profile_pic_result = mysql_query($sql);
				$profile_pic_rows = mysql_num_rows($profile_pic_result);
				if($profile_pic_rows){
					for($i=0;$i<$profile_pic_rows;$i++){
						$profile_pic_row = mysql_fetch_assoc($profile_pic_result);
						unlink($images_dir."/".$profile_pic_row['photo_filename']);
						unlink($images_dir."/tb_".$profile_pic_row['photo_filename']);
						$sql = "DELETE from " . USERS_GALLERY_TABLE . " where photo_id=".$profile_pic_row['photo_id'];
						mysql_query($sql);
					}
				}
				
				$sql = "INSERT INTO " . USERS_GALLERY_TABLE 
				." (photo_uid, photo_filename, photo_caption, photo_category, orig_filename,use_in_profile)"
				." VALUES('".$_SESSION['user_id']."', '".addslashes($filename)."', '"
				.addslashes($photo_caption[$counter])."', 0, '".$orig_name."',1)" ;
				
				mysql_query($sql);
				
				$new_id = mysql_insert_id();
				
				
				//mysql_query( "UPDATE " . USERS_GALLERY_TABLE . " SET photo_filename='".addslashes($filename)."' WHERE photo_id='".addslashes($new_id)."'" );
				//---------------db save end -----------------------------
				
				// Store the orignal file with predefined maximun dimensions
				$width = IMG_MAX_WIDTH;
				$height = IMG_MAX_HEIGHT;
				
				list($width_orig, $height_orig) = getimagesize($photos_uploaded["tmp_name"][$counter]);
				
				// is it bigger than our set max width and max height?
				// else keep the same dimention
				if($width_orig > $width || $height_orig > $height) {
					if ($width && ($width_orig < $height_orig)) {
					  $width = ($height / $height_orig) * $width_orig;
					} else {
					  $height = ($width / $width_orig) * $height_orig;
					}
				} else {
					$width = $width_orig;
					$height = $height_orig;
				}
				//echo "w:".$width."<br>h:".$height."<br>";
				// Build Thumbnail with GD 1.x.x, you can use the other described methods too
				$function_suffix = $gd_function_suffix[$filetype];
				$function_to_read = "ImageCreateFrom".$function_suffix;
				$function_to_write = "Image".$function_suffix;
				$image_location = $images_dir."/".$filename;
				$thumb_name = "tb_".$filename;
				$thumb_img_location = $images_dir."/".$thumb_name;
				
				// Read and write for owner, read for everybody else
				//chmod("/somedir/somefile", 0644);
				
				// Read the source file
				$source_handle = $function_to_read ($photos_uploaded['tmp_name'][$counter]); 
				
				//copy($photos_uploaded['tmp_name'][$counter], $image_location);
				
				if($source_handle)
				{
					// Let's create an blank image for the thumbnail
					if(GD_VERSION >= 2 && $filetype != 'image/gif') {
						$destination_handle = imagecreatetruecolor($width,$height);
					}else{
				     	$destination_handle = imagecreate($width,$height);
					}
				
					// Now we resize it
					if(GD_VERSION >= 2) {
						//echo 'using imagecopyresampled<br>';
					  imagecopyresampled($destination_handle,$source_handle,0,0,0,0,$width,$height,$width_orig,$height_orig);
					}else{
						//echo 'old imagecopyresized<br>';
			      	  imagecopyresized($destination_handle,$source_handle,0,0,0,0,$width,$height,$width_orig,$height_orig);
					}
				}
				// Let's save the thumbnail
				$function_to_write( $destination_handle, $image_location);
				ImageDestroy($destination_handle );
				chmod($image_location, 0666);
				
				
				//-----------------------thumbnail-------------------------
				// Get new dimensions
				//ist($width_orig, $height_orig) = getimagesize($photos_uploaded["tmp_name"][$counter]);
				$thumbnail_width = THUMB_MAX_WIDTH;
				$thumbnail_height = THUMB_MAX_HEIGHT;	
							
				
				if ($thumbnail_width && ($width_orig < $height_orig)) {
				  $thumbnail_width = ($thumbnail_height / $height_orig) * $width_orig;
				} else {
				  $thumbnail_height = ($thumbnail_width / $width_orig) * $height_orig;
				}

				
				
				if($source_handle)
				{
					// Let's create an blank image for the thumbnail
					if(GD_VERSION >= 2 && $filetype != 'image/gif') {
						$destination_handle = imagecreatetruecolor($thumbnail_width,$thumbnail_height);
					}else{
				     	$destination_handle = imagecreate($thumbnail_width,$thumbnail_height);
					}
				
					// Now we resize it
					if(GD_VERSION >= 2) {
						//echo 'using imagecopyresampled<br>';
					  imagecopyresampled($destination_handle,$source_handle,0,0,0,0,$thumbnail_width,$thumbnail_height,$width_orig,$height_orig);
					}else{
						//echo 'old imagecopyresized<br>';
			      	  imagecopyresized($destination_handle,$source_handle,0,0,0,0,$thumbnail_width,$thumbnail_height,$width_orig,$height_orig);
					}
				}
				// Let's save the thumbnail
				$function_to_write( $destination_handle, $thumb_img_location );
				ImageDestroy($destination_handle );	
				chmod($thumb_img_location, 0666);			
				
				
				$result_final .= "<img src='". MEMBER_IMG_DIR_URL ."/".$thumb_name."' width=".$thumbnail_width." />".$lang['image']." ".($counter+1)." ".$lang['added_fem']."<br />";
				$result[$counter] = array(1,$result_final);
			}
		}
	$counter++;
	}
	return $result;
}// end of upload photo

//is it a valid image resource?
function is_gd_handle($var) {
   ob_start();
       imagecolorallocate($var, 255, 255, 255);
       $error = ob_get_contents();
   ob_end_clean();
   if(preg_match('/not a valid Image resource/',$error)) {
       return false;
   } else {
       return true;
   }
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
global $lang_occupational_area, $lang_height_cm, $lang_eyes_color,$lang_hair_color,$lang_body_type;
?>
<script language="javascript">
function validate() {
	var f = document.form1;

	if ( f.about_me.value == '' ) {
		alert ("Vamos! dinos un poco acerca de ti.");
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
	  
    <legend><?=$lang['basic_info']?></legend>
      
      
      <div class="required" id="aboutMeArea">
        <label for="first_name"><? echo $lang['describe_personality']; ?></label>
        <textarea name="about_me"></textarea>
			<small>
			
			Comienza por describir tu personalidad y tu mejores cualidades. Podrías escribir acerca de tu trabajo, familia o mascotas, tu origen y metas, tus viajes, dónde creciste o describir la foto que agregaste. En la próxima pagina podrás describir tu pasatiempos y físico. Por favor no incluyas URL de otros sitios web, emails, tu nombre completo o alguna otra informacion de contacto, puede ser sacado por los moderadores. 
			</small>
      </div>
	  
	  <div class="optional" id="myIdealMateArea">
        <label for="first_name"><? echo $lang['describe_what_you_looking_for']; ?></label><br />
        <textarea name="my_ideal_mate" id="my_ideal_mate"></textarea>	
		<small>Proporciona una descripción acerca de tu pareja o amistad ideal para dar a las demás personas más información a cerca de lo que estás buscando.</small>	
      </div>
	  
	  <div class="optional" id="turnOns">
        <label for="turn_ons"><?php echo $lang['turn_ons']; ?>:</label><br />
        <textarea name="turn_ons" id="turn_ons"></textarea>
        <small> </small>      </div>
	  
	  <div class="optional" id="turnOffs">
        <label for="turn_offs"><?php echo $lang['turn_offs']; ?>:</label><br />
        <textarea name="turn_offs" id="turn_offs"></textarea>	
		<small> </small>		
      </div>	  

	  
	  
	  <div class="optional" id="relationTypeArea">
	  <label for="relation_type"><?php echo $lang['relationship_type']; ?>:</label>
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
	  <label for="race"><?php echo $lang['race']; ?>:</label>
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
	 
	      <legend><?=$lang['lifestyle']?></legend>
		  	  
	  
	  <div class="optional" id="drinkHabit">
	  <label for="drinkHabit"><?php echo $lang['drink_habit']; ?>:</label>
	  <select name="drink_habit">
        <?php  
		foreach ($lang_drink_habit as $key=>$value) {
			echo "<option value=\"".$key."\">".$value."</option>\n";
		}?>
      </select>
	  </div>
	  
	  
	  <div class="optional" id="smokeHabit"> 
	  <label for="smokeHabit"><?php echo $lang['smoke_habit']; ?>:</label>
	  	    <select name="smoke_habit">
		<?php  
		foreach ($lang_smoke_habit as $key=>$value) {
			echo "<option value=\"".$key."\">".$value."</option>\n";
		}?>
          </select>
	  </div>
		  
	  	  <div class="optional" id="haveKids">
	  <label for="haveKids"><?php echo $lang['children']; ?></label>
	    <select name="have_kids" size="1">
		<?php  
		foreach ($lang_have_kids as $key=>$value) {
			echo "<option value=\"".$key."\">".$value."</option>\n";
		}?>
        </select>
      </div>
	   
	   
	  <div class="optional" id="wantKids">
	  <label for="wantKids"><?php echo $lang['want_children']; ?></label>
	    <select name="want_kids" size="1">
		<?php  
		foreach ($lang_want_kids as $key=>$value) {
			echo "<option value=\"".$key."\">".$value."</option>\n";
		}?>
        </select>
      </div>
	  
	 <div class="optional" id="income">
	  <label for="income"><?php echo $lang['income']; ?></label>
	    <select name="income" size="1">
		<?php  
		foreach ($lang_income as $key=>$value) {
			echo "<option value=\"".$key."\">".$value."</option>\n";
		}?>
        </select>
      </div>
	  
	  	  <div class="optional" id="education">
	  <label for="education"><?php echo $lang['education']; ?></label>
	    <select name="education" size="1">
		<?php  
		foreach ($lang_education as $key=>$value) {
			echo "<option value=\"".$key."\">".$value."</option>\n";
		}?>
        </select>
      </div>
	  
	 <div class="optional" id="employmentArea">
	  <label for="employment_status"><?php echo $lang['employment_status']; ?></label>
	    <select name="employment_status" size="1">
		<?php  
		foreach ($lang_employment_status as $key=>$value) {
			echo "<option value=\"".$key."\">".$value."</option>\n";
		}?>
        </select>
		
      </div>
	  
	  
	 <div class="optional" id="employmentArea">
	  <label for="ocupation"><?php echo $lang['occupation']; ?></label>
<select name="occupational_area">
<?php  
foreach ($lang_occupational_area as $key=>$value) {
	echo "<option value=\"".$key."\">".$value."</option>\n";
}?>
<option value="255">Otra</option>
</select>
		
      </div>	  
	  
		<div class="optional" id="exerciseFreqArea">
		<label for="exercise_freq">¿Que tan frequente haces ejercicio?</label>
	    <select name="exercise_freq">
		<?php  
		foreach ($lang_exercise_freq as $key=>$value) {
			echo "<option value=\"".$key."\">".$value."</option>\n";
		}?>
        </select>
		</div>
	  
	  	  
	  </fieldset>
	  
	  	 
	 <fieldset>
	 
	      <legend><?=$lang['interests']?></legend>
		  
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
	 
	      <legend><?=$lang['physical_appearance']?></legend>	
		  
		  
		<div class="optional" id="weightArea">
        <label for="weight">Cuanto Pesas?</label>
			<input type="text" name="weight" id="weight" class="inputText" size="6" maxlength="5" 
			value="" /> <select name="weight_unit" onChange="javascript:set_weight()">
					<option value="">Unidad de Peso</option>
					<option value="lb">Libras</option>
					<option value="kg">Kilos</option>
					</select>
		<small>(Solo Digitos)</small>
		</div>  
		
		<div class="optional" id="bodyTypeArea">
		<label for="height_cm">Tu tipo de cuerpo</label>
	    <select name="body_type">
		<?php  
		foreach ($lang_body_type as $key=>$value) {
			echo "<option value=\"".$key."\">".$value."</option>\n";
		}?>
        </select>
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
          <input type="button" class="inputSubmit" value="<?php echo $lang['save']; ?> &raquo;" onClick="validate()" />
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
		<label> Descripcion (opcional):</label>
		 <input type="text" name="photo_caption[]" />
		</div>
__HTML_END;
	 }
?>	 
<script type="text/javascript">
function continueform() {
	var f = document.form1;
	f.submit();
}
</script>

	<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" enctype="multipart/form-data" name="form1">
	 <fieldset>

	 <input type="hidden" name="step" value="photoup" />
	 <input type="hidden" name="mode" value="register" />
	 	<legend>Si tienes una foto tuya, subela!</legend>
		<small>Si no tienes una foto, puedes actualizar tu perfil y subir tu(s) foto(s) despues.</small>
		<?php echo $photo_upload_fields; ?>
	
	</fieldset>
	<fieldset>
	  
      <div class="submit">
        <div>
          <input type="button" class="inputSubmit" value="<?php echo $lang['continue']; ?> &raquo;" onClick="continueform()" />
		 <!-- <input type="submit" /> -->
        </div>
      </div>
    </fieldset>
	</form>
<? 
}
?> 
	
	</div>
	<!-- InstanceEndEditable --></div>


</body>
<!-- InstanceEnd --></html>