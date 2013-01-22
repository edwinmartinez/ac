<?php 
define('IN_PHPBB', true);
include('includes/config.php');
 
ob_start(); //for captcha
session_start();
//conflict with loggedin user password
unset($HTTP_SESSION_VARS['login']);
unset($HTTP_SESSION_VARS['password']);

mysql_connect($dbhost, $dbuser, $dbpasswd) or die("Could not connect: " . mysql_error());
mysql_select_db($dbname);							


function check_username(){
	global $dbhost,$dbuser,$dbpasswd,$dbname,$phpbb_root_dir, $error;
	if (!empty($_REQUEST['username'])) {
	
		$username = trim($_REQUEST['username']);
		// Remove doubled up spaces
		$username = preg_replace('#\s+#', ' ', $username);
		$username = preg_replace('#_+#', '_', $username);

		if (ereg('[^A-Za-z0-9_-]', $username)) {
			//"This contains characters other than letters and numbers";
			$error = 1;
		} 
		if (preg_match("/�/i", $username)) {
			$error = 1;
		} 
		// no errors
	} else { $error = 2; }
    
	if(!$error) {
		// if user doesn't exist username_exists returns 0
		echo username_exists($username)  . "|" . $username;
	} else {
		echo "2" . "|" . $username;
	}
}


function check_username_filter($username){
	global $error, $lang, $username_min_chars, $username_max_chars;
	
	if (!empty($username) ) {
		if (ereg('[^A-Za-z0-9_-]', $username)) {
			//echo "This contains characters other than letters and numbers";
			$error['username'] = $lang['Your'] . " " . $lang['username'] . " "  . $lang['contains_ilegal_characters'];
			return false;
		}
		
		if (strstr("'", $username) || strstr(' ',$username)) {
			//we will store in error an anonymous array the first item contains the $var so that we know
			// where the error lies
				//note that if we allready have an error flagged above this will simply replace that error
				$error[$var] = $lang['Your'] . " " . $lang['username'] . " "  . $lang['contains_ilegal_characters'];
				//echo $lang['Your'] . " " . $lang['username'] . " "  . $lang['contains_ilegal_characters'];
				return false;
		}
		
		// let's test for the length of username
		if(strlen($username) < $username_min_chars) {
			$error['username'] = $lang['username_must_have_min_chars'];
			return false;
		}
		if(strlen($username) > $username_max_chars) {
			$error['username'] = $lang['username_must_not_exceed_max_chars'];
			return false;
		}	
		// let's check for duplicity of username
		if(!$error['username']) {
			if(username_exists($username)  > 0 ) {
				$error['username'] = $lang['username_taken_enter_new_one'] ;
				return false;
			}

		} // no errors
		return true;
	} else { //then we have an empty username
		return false;
	}
}

/* check to see if the user exists in the database
*  
* return 0 if the user doesn't exist or 1 if the user exists
*/
function username_exists($username) {

		$sql = "SELECT username from " . SITE_USERS_TABLE
			." WHERE LOWER(username) = '" . strtolower(trim($username)) . "'"
			." limit 1";
				   
		if ( !($result = mysql_query($sql)) )
		{
			printf('Could not select username at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql);
			return -1;
		} else {
			$totalRows = mysql_num_rows($result);
			mysql_free_result($result);
		}
		return $totalRows;

}

function name_case($name) {
   $newname = strtoupper($name[0]);   
   for ($i=1; $i < strlen($name); $i++) {
       $subed = substr($name, $i, 1);   
       if (((ord($subed) > 64) && (ord($subed) < 123)) ||
           ((ord($subed) > 48) && (ord($subed) < 58)))
       {
           $word_check = substr($name, $i - 2, 2);
           if (!strcasecmp($word_check, 'Mc') || !strcasecmp($word_check, "O'")) {
               $newname .= strtoupper($subed); 
           }
           else if ($break){
               $newname .= strtoupper($subed);
           }
           else  {
               $newname .= strtolower($subed);
           }
             $break=0;
       }
       else {
           // not a letter - a boundary
             $newname .= $subed;
           $break=1;
       }
   }   
   return $newname;
}



$mode = ( isset($_GET['mode']) ) ? $_GET['mode'] : $_POST['mode'];
$mode = htmlspecialchars($mode);

if ( $mode == 'register' ) {
//if($_POST['step'] == 1){ 
	$_POST = array_map("trim",$_POST);
	
	// put all the post vars into their respective var names
	// basically we make it compatible with the old style
	foreach ($_POST as $k => $v){
		$$k = $v;
	}

	
	$error = array();
	
	$required = array('first_name','last_name','email','confirm_email','country','gender','seeks_gender','username',
		'password','confirm_password','acceptterms');
	
	$strip_var_list = array(
	    'first_name' => 'first_name',
		'last_name' => 'last_name',
		'username' => 'username', 
		'email' => 'email', 
		'confirm_email' => 'confirm_email',
		'region_desc' => 'region_desc',
		'city' => 'city',
		'postal_code' => 'postal_code', 
		'occupation' => 'occupation', 
		'interests' => 'interests',
		'email' => 'email',
		'confirm_email' => 'confirm_email'
	);
	$strip_var_list['confirm_code'] = 'confirm_code';

	
	$protect = array(
	   "<" => "&lt;",
	   ">" => "&gt;",
	   "&" => "&amp;",
	   "\"" => "&quot;",
	   //"'" => "&#39;",
	   "\n" => " ",
	   "\t" => " ",
	   "\r" => " ",
	   //"\0" => " ",
	   "\x0B" => "",
	   //" " => "",
	   "\|" => "",
	   "%" => ""
	);	
	

	
	foreach($strip_var_list as $var) {
		echo "<pre>";
		echo "var: $var: ".$$var."\n";
		echo "</pre>";
		if ( !empty($$var) ) {
			//$$var = strtr($$var,$protect) ;
			foreach($protect as $checkfor=>$replacement) {
				$$var = preg_replace('#_+#', '_', $$var); //remove double _
				$$var = preg_replace('#\s+#', ' ', $$var); //remove double space
				$$var = preg_replace('/\\\+/', '', $$var);//remove \ backslash (weird problem)
				
				if (ereg($checkfor, $$var)) {
					$error[$var] = $lang['Your'] . " " . $lang[$var] . " "  . $lang['contains_ilegal_characters'];
				}
			}
			
		} 
	}
	
	foreach($required as $var) {
		if (empty($$var) ) { $error[$var] =  $lang['this_field_is_required']; }
	}
	
	check_username_filter($username);
	
  

	// Strip all tags from data ... may p**s some people off, bah, strip_tags is
	// doing the job but can still break HTML output ... have no choice, have
	// to use htmlspecialchars ... be prepared to be moaned at.
	while( list($var, $param) = @each($strip_var_list) ){
		if ( !empty($_POST[$param]) ) {
			$$var = trim(htmlspecialchars($_POST[$param])) ;
		}
	}
	

	if($password != $confirm_password) {
		$error['password'] = 'Error: '.$password .":". $password ."noteq".$confirm_password." ". $lang['password_and_confirm_password_dont_match'];
	}


//Let's check if we have the email in our records

//first let's check if the emails is correct - emailcheck
	if(empty($error)){
		if(!eregi("^[[:alnum:]][a-z0-9_.-]*@[a-z0-9.-]+\.[a-z]{2,4}$", $email)) {
			 $error['email'] = LA_BAD_EMAIL_FORMAT;
		}else{// ok so the email passed the test above
	
			$sql = "SELECT " . USER_EMAIL_FIELD . " from " . SITE_USERS_TABLE
							   ." WHERE LOWER(".USER_EMAIL_FIELD.") = '" . strtolower($email) . "'"
							   ." limit 1";
			if ( !($result = mysql_query($sql)) ) {
					printf('Could not select username at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql);
			} else {
				$totalRows = mysql_num_rows($result);
				// if we have more than 0 rows returned then we have someone allready in the db
				// let's return an error
				if($totalRows > 0 ) { 
					$error['email'] = LA_ERROR_EMAIL_IN_OUR_RECORDS;
				}
				mysql_free_result($result);
			}
		}
	}
	
	
	//warning for senegal
	if($_POST['country'] == '185'){
		$to      = 'martinmedia@yahoo.com';
		$subject = 'senegal user';
		$message = 'senegal user trying to register from ip: '.$_SERVER['REMOTE_ADDR'];
		$headers = 'From: contacto@amigocupido.com' . "\r\n" .
			'Reply-To: contacto@amigocupido.com' . "\r\n" .
			'X-Mailer: PHP/' . phpversion();
		
		mail($to, $subject, $message, $headers);
		printf("Error 1266. Could not connect to database: Please inform your site administrator" );
		exit;
	}
	
	// If we have an error then let's give back the user the registration form step 1
	// if not we continue registering the user 
	if(!empty($error)) {
		print_form();
		exit;
	}


	
	
	//let's compose the birthdate
	$birthdate = $birth_year . "-" . $birth_month . "-" . $birth_day;
	$now = date("Y-d-m H:i:s");
	$password = md5($password);
	// insert into db----------------------
		
	$sql = "INSERT INTO " . SITE_USERS_TABLE ." (".USERNAME_FIELD.", 
		user_password, 
		user_first_name, 
		user_last_name, 
		reg_from_ip,
		user_email,
		user_birthdate, 
		user_gender, 
		user_seeks_gender, 
		user_country_id, 
		user_created";
				
		if(!empty($city)) { $sql .= ", user_city"; }
		if(!empty($state_id)) { $sql .= ", user_state_id";
		} elseif (!empty($state_desc)) { $sql .= ", user_state_desc";}
		if(!empty($postal_code)) { $sql .= ", user_postal_code"; }
		
		$sql .= ") VALUES ("
		. GetSQLValueString($username, "text") . ", "
		. GetSQLValueString($password, "text") . ", "
		. GetSQLValueString(name_case($first_name), "text") . ", "
		. GetSQLValueString(name_case($last_name), "text") . ", "
		. GetSQLValueString($_SERVER['REMOTE_ADDR'], "text") . ", "
		. GetSQLValueString(strtolower($email), "text") . ", "
		. GetSQLValueString($birthdate, "text") . ", "
		. GetSQLValueString($gender, "int") . ", "
		. GetSQLValueString($seeks_gender, "int") . ", "
		. GetSQLValueString($country, "int") . ", "
		//. GetSQLValueString($now, "text") ;
		. 'NOW() ';
		
		if(!empty($city)) { $sql .= ", ".GetSQLValueString(ucwords(strtolower($city)),"text"); } //Capitalize City
		if(!empty($state_id)) { $sql .= ", ".GetSQLValueString($state_id,"int");
		} elseif(!empty($state_desc)) { $sql .= ", ".GetSQLValueString($state_desc,"text");}
		if(!empty($postal_code)) { $sql .= ", ".GetSQLValueString($postal_code,"text"); }
		
		
		$sql .= ")";		   
					  
		   
					   
	

	$result = mysql_query($sql) or die(mysql_error());
	
	// let's insert into phpbb users but first let's get the users id since it is not auto_increment by default
	// we will do the same way phpbb does it
	
/* 	$sql = "SELECT MAX(user_id) AS total
				FROM " . PHPBB_USERS_TABLE;
			if ( !($result = mysql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not obtain next user_id information', '', __LINE__, __FILE__, $sql);
			}

			if ( !($row = mysql_fetch_assoc($result)) )
			{
				message_die(GENERAL_ERROR, 'Could not obtain next user_id information', '', __LINE__, __FILE__, $sql);
			}
			$user_id = $row['total'] + 1; */
	// if you want to use phpbb user_id system uncomment the last block and comment the following line
	$user_id = mysql_insert_id();	
	// let's save this for the session
	$_SESSION['user_id'] = $user_id;
	$_SESSION['first_name'] = $first_name;
	$_SESSION['last_name'] = $last_name;
	
	$sql = "INSERT INTO " . PHPBB_USERS_TABLE ." (user_id, username, user_password, user_email, user_regdate, user_lang, user_viewemail, user_attachsig, user_popup_pm,  user_avatar, user_notify_pm, user_style, user_dateformat) ";
	$sql .= "VALUES ("
		. $user_id . ", "
		. GetSQLValueString($username, "text") . ", "
		. GetSQLValueString($password, "text") . ", "
		. GetSQLValueString($email, "text") . ", "
		. time() . ", "
		. GetSQLValueString("spanish", "text") . ", " //user_lang
		. GetSQLValueString("0", "int"). ", " //user_viewemail
		. GetSQLValueString("0", "int"). ", " // user_attachsig
		. GetSQLValueString("1", "int"). ", " // user_popup_pm
		. GetSQLValueString("", "text") . ", " // user_avatar
		. GetSQLValueString("1", "int"). ", "
		. GetSQLValueString("1", "int"). ", "
		. GetSQLValueString("D M d, Y g:i a", "text")
		. ")";
	
	
	//echo $sql . "<br>user_id".$user_id."<br>rowtotal:".$row['total']."\n";
	//$result = mysql_query($sql) or die(mysql_error());
	if ( !($result = mysql_query($sql)) ) {
			printf('Could not insert userpref at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql);
	}
	
	
	
	$sql = "INSERT INTO ".USER_PREFERENCES_TABLE." (user_id) values (".$user_id.")";
	if ( !($result = mysql_query($sql)) ) {
			printf('Could not insert userpref at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql);
	}
	
	// create a random number to store as the confirmation number and send as an email -------------
	
	// send mail to confirm email ----------------
	
	// output a page that says you have been registered an email has been sent to confirm your registration
	// if you have that confirmation number you may enter it here
	
	// create a script that takes that confirmationn looks into unconfirmed users for the number and confirms the user
	// takes him to the login page. If it's the first time login in then take him to the edit profile section which
	// is nice.
	
	//I would have him log in but it's not cool if someone snooping on confirmation email
	// is able to log in just by clicking the conf link
	
	print header("Location: profiler.php");
	exit;


} else{
	print_form();
}

function print_form() {
	//include('citas_en_linea_foros/db/mysql.php');
	global $dbhost, $dbuser, $dbpasswd,$dbname,$error,$lang,$username_min_chars,$username_max_chars;
	global 	
		
	    $first_name,
		$last_name ,
		$username, 
		$email, 
		$gender,
		$seeks_gender,
		$confirm_email,
		$birth_day, $birth_month, $birth_year,
		$country,
		$state_id,
		$state_desc,
		$city,
		$postal_code, 
		$occupation, 
		$interests,
		$email,
		$confirm_email,
		$acceptterms ; 
		
	$top_countries = array("US","CA","ES","MX","GT","SV","HN","NI","CR","PA","DO","CU","CL","PR","PE","EC","VE","BO","UY","PY","AR","BR");
	
	$banned_countries = array("AO","SA","BN","BT","KH","TD","CI","HR","EG","AE","ET","GA","GM","GH","GS","GP","IR","CC","CK","FJ","JO","KE","KZ","KI","LS","LV","LY","LT","MO","MG","ML","MU","YT","NA","NR","NP","NE","NG","NU","OM","NC","PW","PG","PF","QA","CF","RW","RU","EH","WS","AS","SN","SL","SY","SO","LK","SZ","ZA","SD","SR","TZ","TJ","TP","TG","TK","TO","TN","TV","TM","UA","UG","UZ","VU","ZR","ZM","ZW","CG","CM","KH","BI","BD","BH","BY","MZ","BW","BJ","AZ","YE","DJ","MM","MW","KG","BW","BF","CY","KM","ER","GU","CX","SB","SC","NF","MH","MP","KW","KY","GN","GQ","GW","SJ","TC","VI","VG","WF","KN","VC","PM","RE","PK","PN","ST","FO","BV","HM","AM","CV","BA");
	

	$sql = "SELECT * from countries order by countries_name_es asc";
	
	$result = mysql_query($sql) or die(mysql_error());
	if ( !($result = mysql_query($sql)) ) {
			printf('Could not select countries at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql);
	}

	while ($row = mysql_fetch_assoc($result)) {
	//echo $country .":". $row['countries_id']."<br>";
		if($country == $row['countries_id']) {$selected = 'selected="selected"'; }
		else { $selected = ""; }	
		if(in_array($row['countries_iso_code_2'],$top_countries)){
			$countries_menu_top .= sprintf("<option value=\"%s\">%s</option>\n",
				$row['countries_id'],$row['countries_name_es']);
		}
		//check for banned countries
		if(!in_array($row['countries_iso_code_2'],$banned_countries)){
			$countries_menu_bot .= sprintf("<option %s value=\"%s\">%s</option> \n", $selected,$row['countries_id'], $row['countries_name_es']);  
		}
		
		$menu_div = '<option value="">---------------</option>'."\n";
		$countries_menu = $countries_menu_top . $menu_div . $countries_menu_bot;
	} 
	mysql_free_result($result);
	
	
	//create gender menu
	if($gender == 1) { $selectedm = 'selected="selected"';$selectedf = ''; }
	elseif ($gender == 2) { $selectedf = 'selected="selected"';  $selectedm = '';}
	$gender_menu  = "<select name=\"gender\" id=\"gender\">\n";
	$gender_menu .= "<option value=\"\">".$lang['select_your_sex']."</option>\n";
	$gender_menu .= "<option ". $selectedm . "value=\"1\">".$lang['man']."</option>\n";
	$gender_menu .= "<option ". $selectedf . "value=\"2\">".$lang['woman']."</option>\n";
	$gender_menu .= "</select>\n";
	//if($gender
	
		//create seeks_gender menu
	$selectedm = '';
	$selectedf = '';
	if($seeks_gender == 1) { $selectedm = 'selected="selected"';$selectedf = ''; }
	elseif ($seeks_gender == 2) { $selectedf = 'selected="selected"';  $selectedm = '';}
	$seeks_gender_menu  = "<select name=\"seeks_gender\" id=\"seeks_gender\">\n";
	$seeks_gender_menu .= "<option value=\"\">".$lang['select_your_preference']."</option>\n";
	$seeks_gender_menu .= "<option ". $selectedm . "value=\"1\">".$lang['man']."</option>\n";
	$seeks_gender_menu .= "<option ". $selectedf . "value=\"2\">".$lang['woman']."</option>\n";
	$seeks_gender_menu .= "</select>\n";
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/citasenlinea01.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="shortcut icon" href="/favicon.ico" />
<meta name="Description" content="Citas en linea un servicio de citas en linea para hispanos. Latin Dating Service" />
<meta name="description" lang="es" content="Servicio de citas en linea en donde podras conocer, de una manera facil y simple, a otros solteros en Latinoam�rica y el resto del mundo en busca de amor y amistad." />
<meta name="Keywords" content="citas, citas en linea, citas con hispanos, latin, hispanos, hispanics, latin datin, dating,online dating louisville online dating service, adult dating, free online dating, dating site, dating services, free dating, single dating, christian dating, christian dating service, dating web site, gay dating, internet dating, community dating general, jewish dating, hispanic dating service, dating personals,  latinas, personals dating services,  personals for single and dating, internet personals dating, International Dating  Services, latin personals, latin american personals, latin  international personals, personals latin america,  latin meet, hot single latinas, latin  single dating, sexy latin single, latin single, latin single  connection, single latin female, hispanic latin single, latin american single, latin single web  site, latin dating, latin dating services, latin dating " />
<!-- InstanceBeginEditable name="doctitle" -->
<title>AmigoCupido.Com - Citas en Linea | Citas con hispanos | Latin Dating</title>
<script language="javascript">
<!--
function test(strURL, strSubmit, strResultFunc) {
    document.getElementById('123').innerHTML = strURL + "<br>" + strSubmit + "<br>" + strResultFunc;
	//document.getElementById('123').innerHTML = "something";
}

function xmlhttpPost(strURL, strSubmit, strResultFunc) {

        var xmlHttpReq = false;
        
        // Mozilla/Safari
        if (window.XMLHttpRequest) {
                xmlHttpReq = new XMLHttpRequest();
                //xmlHttpReq.overrideMimeType('text/xml');
        }
        // IE
        else if (window.ActiveXObject) {
                xmlHttpReq = new ActiveXObject("Microsoft.XMLHTTP");
        }
		xmlHttpReq.open('POST', strURL, true);
        xmlHttpReq.setRequestHeader('Content-Type', 
		     'application/x-www-form-urlencoded');
        xmlHttpReq.onreadystatechange = function() {
                if (xmlHttpReq.readyState == 4) {
                        eval(strResultFunc + '(xmlHttpReq.responseText);');
                }
        }
        xmlHttpReq.send(strSubmit);
}

function displayRegionResult(strIn) {

        var strContent = '';
        var strPrompt = '';
        var nRowCount = 0;
        var strResponseArray;
        var strContentArray;
        var objRegion;
        
        // Split row count / main results
        strResponseArray = strIn.split('\n\n');
        
        // Get row count, set prompt text
        nRowCount = strResponseArray[0];
        strPrompt = nRowCount + ' row(s) returned.';
        
        // Actual records are in second array item --
        // Split them into the array of DB rows
        strContentArray = strResponseArray[1].split('\n');
        var rowArray;
		document.form1.region.options.length = 0; //clear the menu
		if (nRowCount > 0) {
			strContent = '<select name="region" id="region" class="selectOne">';
			strContent += '<option value="" selected>-Selecciona Estado/Region-</option>';
			strContent += '<option value="">------------</option>';
			document.form1.region.options[document.form1.region.length ] = new Option( '--Region/Estado--','' );	
		} else {
		    strContent = '<input type="text" name="region" id="region">';
		}
        // Create table rows
		
		
        for (var i = 0; i < strContentArray.length-1; i++) {
                // Create track object for each row
                //objRegion = new regionListing(strContentArray[i]);
				rowArray = strContentArray[i].split('|');
				strContent += '<option value="' + rowArray[0] + '">' + rowArray[1] + '</option>';
				
				document.form1.region.options[document.form1.region.length ] = new Option( rowArray[1], rowArray[0] ); 


                // ----------
                // Add code here to create rows -- 
                // with objTrack.arist, objTrack.title, etc.
                // ----------
				//strContent += '<tr><td>' + objRegion.id + ' - ' + objRegion.name '</td></tr>' + "\n";
        }
        
		
		if (nRowCount > 0) {
			strContent += '</select>';
			document.getElementById('regionParent').style.display='inline';
			document.getElementById('regionParent2').style.display='none';
		} 
        else {
		    //document.getElementById('region').innerHTML = strContent;
			document.getElementById('regionParent').style.display='none';
			document.getElementById('regionParent2').style.display='inline';
		}
        // ----------
        // Use innerHTML to display the prompt with rowcount and results
        // ----------
		//document.getElementById('region').innerHTML = strContent;
		//WriteLayer('region',null,strContent);
}

function displayUserNameResult(strIn) {
    // Split row count / main results
    strResponseArray = strIn.split('|'); 
	responseCode = strResponseArray[0];
	if(responseCode == "1"){
		document.getElementById('userMessage').innerHTML = '<p class=error><?php echo $lang['username_taken_enter_new_one']; ?></p><br>';
	}
	else if(responseCode == "2"){
		document.getElementById('userMessage').innerHTML = '<p class=error>Hay un error en tu apodo. Por favor corrige tu apodo.</p><br>';
	}
	else if(responseCode == "0"){
		document.getElementById('userMessage').innerHTML = '<p class=good>Este apodo esta disponible.</p><br>';
	}
	else {
		document.getElementById('userMessage').innerHTML = '<p class=error>Error</p><br>';
	}
}


function regionListing(strEntry) {
        var strEntryArray = strEntry.split('|');
        this.id       = strEntryArray[0];
        this.name        = strEntryArray[1];
}


// State generation of menus
// when a country is selected the state is dynamically changed in iframe
// --------------------------------------------------------------------------
function loadStates(s) {
	
	var f = document.form1
	
	// Initialize state to select one
	f.state_id.value = 0;
	f.state_desc.value = "";
	
	document.getElementById('state').src = "remote.php?action=regionmenu&countryid=" + s.options[s.selectedIndex].value
}


// Funci�n que se llama desde el IFRAME para actualizar el idProvicia
function updateStateId(id) {
	document.form1.state_id.value = id
}	

// Funci�n que se llama desde el IFRAME para actualizar la descProvicia
function updateStateDesc(txt) {
	document.form1.state_desc.value = txt
}	

function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}

function validate() {
	var f = document.form1;

	if ( f.first_name.value == '' ) {
		alert ("El nombre es obligatorio");
		f.first_name.focus()
		changeClass('firstNameArea','error');
		return
	}
	if ( ! f.first_name.value.isAT_SIGN() ) {
		alert ("Escribe de nuevo tu Nombre utilizando �nicamente letras");
		f.first_name.focus()
		changeClass('firstNameArea','error');
		return
	}
	// Comprobamos que en el nombre no nos pongan n�meros
	if( f.first_name.value.indexOf("0") != -1 ||
		f.first_name.value.indexOf("1") != -1 ||
		f.first_name.value.indexOf("2") != -1 ||
		f.first_name.value.indexOf("3") != -1 ||
		f.first_name.value.indexOf("4") != -1 ||
		f.first_name.value.indexOf("5") != -1 ||
		f.first_name.value.indexOf("6") != -1 ||
		f.first_name.value.indexOf("7") != -1 ||
		f.first_name.value.indexOf("8") != -1 ||
		f.first_name.value.indexOf("9") != -1 ) {
			alert ("El nombre no puede contener caracteres numericos");
			f.first_name.focus()
			changeClass('firstNameArea','error');
			return
	}
	
	if ( f.last_name.value == '' ) {
		alert ("El apellido es obligatorio");
		f.last_name.focus()
		changeClass('lastNameArea','error');
		return
	}
	if ( ! f.last_name.value.isAT_SIGN() ) {
		alert ("Escribe de nuevo tu apellido utilizando �nicamente letras");
		f.last_name.focus()
		changeClass('lastNameArea','error');
		return
	}
	// Comprobamos que en el nombre no nos pongan n�meros
	if( f.last_name.value.indexOf("0") != -1 ||
		f.last_name.value.indexOf("1") != -1 ||
		f.last_name.value.indexOf("2") != -1 ||
		f.last_name.value.indexOf("3") != -1 ||
		f.last_name.value.indexOf("4") != -1 ||
		f.last_name.value.indexOf("5") != -1 ||
		f.last_name.value.indexOf("6") != -1 ||
		f.last_name.value.indexOf("7") != -1 ||
		f.last_name.value.indexOf("8") != -1 ||
		f.last_name.value.indexOf("9") != -1 ) {
			alert ("El apellido no puede contener car�cteres num�ricos");
			f.last_name.focus()
			changeClass('lastNameArea','error');
			return
	}

	if ( f.email.value == '' ) {
		alert ("El email es obligatorio");
		f.email.focus()
		changeClass('emailArea','error');
		return
	}
	
	if ( ! f.email.value.isEmail() ) {
		alert ("La sintaxi del email no es valida");
		f.email.focus()
		changeClass('emailArea','error');
		return
	}

	if ( f.confirm_email.value == '' ) {
		alert ("Introduce de nuevo el email");
		f.confirm_email.focus()
		return
	}

	if ( f.email.value != f.confirm_email.value ) {
		alert ("Los campos de los emails no coinciden");
		f.confirm_email.value = ''
		f.confirm_email.focus()
		return
	}

	
	var fnac_dia = f.birth_day.options[f.birth_day.selectedIndex].value
	var fnac_mes = f.birth_month.options[f.birth_month.selectedIndex].value
	var fnac_ano = f.birth_year.options[f.birth_year.selectedIndex].value

	if( !fechaCorrecta(fnac_dia + "/" + fnac_mes + "/" + fnac_ano) ) {
		alert("La 'Fecha nacimiento' no es una fecha v�lida.");
		f.birth_day.focus();
		changeClass('birth_date','error');
		return
	}
	
	
	// Comprobamos que el usuario tenga m�s de 18 a�os
	// format 2005/12/21
	var hoy = new Date('<?php echo date('Y/m/d'); ?>')
	fnac_ano = Number(fnac_ano) + 18
	var fnac = new Date( fnac_ano + "/" + fnac_mes + "/" + fnac_dia )
	
	
	if ( fnac > hoy ) {
		alert("Tienes que ser mayor de 18 a�os para poder inscribirte");	
		return;
	}


	if ( f.country.options[f.country.selectedIndex].value == "" )  {
		alert("Selecciona tu pais");
		f.country.focus();
		return
	}
	if ( ! (f.state_id.value > 0) && f.state_desc.value == "" ) {
		alert("Selecciona o escribe tu estado o provincia.");
		//recargaProvincias(f.idPais);
		f.country.focus();
		return
	}
	if ( f.city.value == '' ) {
		alert ("La Ciudad o Municipio es obligatorio");
		f.city.focus()
		changeClass('cityArea','error');
		return
	}
	if ( f.country.options[f.country.selectedIndex].value == 223 && f.postal_code.value == "") {
		alert("Escribe tu Zip Code por Favor.");
		changeClass('postalCodeArea','error');
		f.postal_code.focus();
		return
	}
	if ( f.country.options[f.country.selectedIndex].value == 223) {
		if(!validateZIP(f.postal_code.value)) {
			return
		}
	}
	
	if ( f.username.value == '' ) {
		alert ("El apodo es obligatorio");
		f.username.focus()
		changeClass('userNameArea','error');
		return
	}
	if ( ! f.username.value.isAT_SIGN() ) {
		alert ("Escribe de nuevo tu Apodo utilizando �nicamente letras, cifras y guiones bajos");
		f.username.focus()
		changeClass('userNameArea','error');
		return
	}
	
	if ( f.password.value == '' ) {
		alert ("Contrase�a es obligatoria");
		f.password.focus()
		changeClass('passwordArea','error');
		return
	}
	
	if ( f.confirm_password.value == '' ) {
		alert ("Escribe de nuevo tu contrase�a");
		f.confirm_password.focus()
		changeClass('confirmPasswordArea','error');
		return
	}
	
	if ( f.password.value != f.confirm_password.value ) {
		alert ("Los campos de las contrase�as no coinciden");
		f.confirm_password.value = ''
		f.confirm_password.focus()
		changeClass('confirmPasswordArea','error');
		return
	}
	<? if(CAPTCHA_REG_ENABLED == 1) { ?>
	if ( f.captchastring.value == '' ) {
		alert ("Validacion de caracterers es obligatorio");
		f.captchastring.focus()
		changeClass('captcha','error');
		return
	}
	<? } ?>
		
	if ( ! f.acceptterms.checked ) {
		alert ("Tienes que aceptar los Terminos y condiciones para continuar");
		f.acceptterms.focus()
		changeClass('terms','error');
		return
	}
	
	f.submit()	
}

// Chequea si la fecha es v�lida segun el formato dd/mm/yyyy
function fechaCorrecta(indate) {
	
	if ( indate.length != 10 ) return false
	
    var sdate = indate.split("/")
  
    var chkDate = new Date(Math.abs(sdate[2]),(Math.abs(sdate[1])-1),Math.abs(sdate[0]))

    var cmpDate = (chkDate.getDate())+"/"+(chkDate.getMonth()+1)+"/"+(chkDate.getFullYear())
    var indate2 = (Math.abs(sdate[0]))+"/"+(Math.abs(sdate[1]))+"/"+(Math.abs(sdate[2]))
    
    if (indate2 != cmpDate || cmpDate == "NaN/NaN/NaN") return false
    else return true;
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

function validateZIP(field) {
	var valid = "0123456789-";
	var hyphencount = 0;

	if (field.length!=5 && field.length!=10) {
		alert("Por favor, escribe tu zip code de 5 digitos o 5 digitos+4.");
		return false;
	}
	for (var i=0; i < field.length; i++) {
		temp = "" + field.substring(i, i+1);
		if (temp == "-") hyphencount++;
		if (valid.indexOf(temp) == "-1") {
			alert("Hay caracteres invalidos en tu zip code.");
			return false;
		}
		if ((hyphencount > 1) || ((field.length==10) && ""+field.charAt(5)!="-")) {
			alert("El gion debe de ser usado correctamente  come  en el zip code de 5 digitos+4 digitos, por ejemplo: '12345-6789'. ");
			return false;
		}
	}
	return true;
}

// Funci�n que comprueba que no haya @
String.prototype.isAT_SIGN = function(){	
		
	var iChars = "*|,\":<>[]{}`';()&$#%@.";	
	var eLength = this.length;	
	for (var i=0; i < eLength; i++)	
	{		
		if (iChars.indexOf(this.charAt(i)) != -1)		
		{			
			return false;		
		}	
	}	
	return true;

}

// Funci�n que comprueba si el email es correcto
String.prototype.isEmail = function(){	
	
	if (this.length < 5)	{	return false;	}	
	var iChars = "*|,\":<>[]{}`';()&$#%";	
	var eLength = this.length;	
	for (var i=0; i < eLength; i++)	
	{		
		if (iChars.indexOf(this.charAt(i)) != -1)		
		{			
			return false;		
		}	
	}	
	var atIndex = this.lastIndexOf("@");	
	if(atIndex < 1 || (atIndex == eLength - 1))	{		
		return false;	
	}	
	
	var pIndex = this.lastIndexOf(".");	
	if(pIndex < 4 || (pIndex == eLength - 1))	
	{		
		return false;	
	}	
	if(atIndex > pIndex)	{		
		return false;	
	}	
	return true;

}
//-->
</script>

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
<a href="http://www.amigocupido.com/"><img src="/images/spacer.gif" alt="AmigoCupido.com" title="myspace en espanol" width="319" height="80" border="0" /></a></div>
		<div id="topNav">
		<a href="/">Inicio</a> | 
		<a href="/gente/">Gente</a> | 
		<a href="/fotos/">Fotos</a> |
		<a href="/citas_en_linea_blog/">Articulos</a> | <a href="/citas-en-linea-acerca.php">Acerca de Amigo Cupido</a> | <a href="/citas_en_linea_contacto.php">Contactanos</a>	|
		<a href="/mi_cuenta/">Login</a> 	
<?php if(isset($HTTP_SESSION_VARS['user_id']))
		echo '| <a href="/logout/">Salir (Logout)</a>';
?>
</div> 

<!-- #EndLibraryItem --><!-- InstanceBeginEditable name="EditRegion5" -->
    <div id="homeContentWide">


	<!--<div id="contentText"><?php echo $username;?></div> -->
	
	

	    <div id="contentText"><p><span style="border:none;"><img src="images/registrate.gif" width="190" height="26" border="0" /></span></p>
      <p>&nbsp;</p>
      <p>Casillas en <span style="color:#CC0000;font-weight:bold;">Rojo</span> son requeridas.</p>
	  <?php if (!empty($error)) echo '<p><div style="color:#FF0000;background-color: #ffffe1;padding:30px;margin:20px 0px;">'.$lang['there_are_errors_in_form'] .'</div></p>'; ?>
	  
    </div>
	<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" enctype="multipart/form-data" name="form1">
    <input type="hidden" name="step" value="1" />
	<input type="hidden" name="mode" value="register" />
	<input type="hidden" name="state_id" value="<?php echo $state_id; ?>">
	<input type="hidden" name="state_desc" value="<?php echo $state_desc ?>">
    <fieldset>
    <legend>Informacion Personal </legend>
      <div class="notes">
        <h4>Registrate</h4>
        <p class="last">Solo toma unos segundos! </p>
      </div>
      <? if (isset($error['first_name'])) { echo "<div class=\"error\">\n"; } else { ?>
      <div class="required" id="firstNameArea">
        <?php } if (isset($error['first_name'])) echo "<p class=\"error\"> ".$error['first_name']."</p>"; ?>
        <label for="first_name">Nombre:</label>
        <input type="text" name="first_name" id="first_name" class="inputText" size="18" maxlength="100" 
		value="<?php echo $first_name; ?>" />

      </div>
	  <? if (isset($error['last_name'])) { echo "<div class=\"error\">\n"; } else { ?>
	   <div class="required" id="lastNameArea">
         <?php } if (isset($error['last_name'])) echo "<p class=\"error\"> ".$error['last_name']."</p>"; ?>
        <label for="last_name">Apellido:</label>
        <input type="text" name="last_name" id="last_name" class="inputText" size="18" maxlength="100" 
		value="<?php echo $last_name; ?>" />

      </div>
	  <? if (isset($error['email'])) { echo "<div class=\"error\">\n"; } else { ?>
      <div class="required" id="emailArea">
	   <?php } if (isset($error['email'])) echo "<p class=\"error\"> ".$error['email']."</p>"; ?>
        <label for="email"><?php echo $lang['email']; ?>:</label>
        <input type="text" name="email" id="email" class="inputText" size="18" maxlength="250" 
		value="<?php echo $email; ?>" />
      </div>
	  
	  <? if (isset($error['confirm_email'])) { echo "<div class=\"error\">\n"; } else { ?>
		<div class="required">
         <?php } if (isset($error['confirm_email'])) echo "<p class=\"error\"> ".$error['confirm_email']."</p>"; ?>
        <label for="confirm_email"><?php echo $lang['confirm_email']; ?>:</label>
        <input type="text" name="confirm_email" id="confirm_email" class="inputText" size="18" maxlength="250" 
		value="<?php echo $confirm_email; ?>" />
        <small>Debe de ser igual al email que entraste en la casilla anterior.</small>

      </div>
	  
	  
	  <div class="required" id="birth_date">
	  	<label><?=$lang['birth_date']?>:</label>

		
		<?php
		$selected = ''; //let's reset it
		echo "<select name=\"birth_day\" id=\"birth_day\">";
		echo "<option value=\"\">".$lang['day']."</option>";
		for($d=1;$d<32;$d++) {
			$did = $d;
			if($did<10) $did = "0".$did;
			 if(!empty($birth_day)) {
			    //echo "bd: " .$birth_day . " did:".$did."<br>";
				if ($birth_day == $did){ $selected = "selected=\"selected\""; }
				else { $selected = ''; }
			}
			echo "<option " . $selected . " value=\"".$did."\">".$d."</option>\n\n";
		}
		echo "</select>\n\n";
		
		$selected = ''; //let's reset it
		echo "<select name=\"birth_month\" id=\"birth_month\">\n";
		echo "<option value=\"\">".$lang['month']."</option>\n";
		$months = array();
		$month_names = split(",",$lang['months'],12);
		for($m=0;$m<12;$m++) {
			$mid = $m+1;
			if($mid<10) $mid = "0".$mid;
			if ($birth_month == $mid){ $selected = "selected=\"selected\""; }
				else { $selected = ''; }
			echo "<option " . $selected ."value=\"".$mid."\">".trim($month_names[$m])."</option>\n";
		}
		echo "</select>\n\n";
		
		$selected = ''; //let's reset it
		echo "<select name=\"birth_year\" id=\"birth_year\">";
		echo "<option value=\"\">".$lang['year']."</option>";
		for($y=1914;$y < date('Y')-16;$y++) {
		    if(!empty($birth_year)) {
				if ($birth_year == $y){ $selected = "selected=\"selected\""; }
				else { $selected = ''; }
			} else {
				if($y == 1970) { 
					echo "<option selected=\"selected\">".$lang['year']."</option>\n"; }
			}
			echo "<option ". $selected . " value=\"".$y."\">".$y."</option>\n";
		}
		echo "</select>\n\n";
		?>


<small>Debes de tener  18 a&ntilde;os o mas para usar este servicio.</small>
	  </div>
	  
	  
	  
	  	<? if (isset($error['gender'])) { echo "<div class=\"error\">\n"; } else { ?>
	    <div class="required" id="genderArea">
		 <?php } if (isset($error['gender'])) echo "<p class=\"error\"> ".$error['gender']."</p>"; ?>
        <label for="gender"><?php echo $lang['my_gender_is']; ?></label>
		   <?php echo $gender_menu; ?>
      </div>		  
	  
	  	  	<? if (isset($error['seeks_gender'])) { echo "<div class=\"error\">\n"; } else { ?>
	    <div class="required" id="seeksGenderArea">
		 <?php } if (isset($error['seeks_gender'])) echo "<p class=\"error\"> ".$error['seeks_gender']."</p>"; ?>
        <label for="seeks_gender"><?php echo $lang['seeks_gender']; ?></label>
		   <?php echo $seeks_gender_menu; ?>
      </div>
	  
	  
	  	<? if (isset($error['country'])) { echo "<div class=\"error\">\n"; } else { ?>
	    <div class="required">
		 <?php } if (isset($error['country'])) echo "<p class=\"error\"> ".$error['country']."</p>"; ?>
        <label for="country"><?php echo $lang['country_of_residence']; ?>:</label>
          <select name="country" id="country" class="selectOne" onchange="loadStates(this)">
		  <option value=""><?php echo $lang['select_your_country']; ?></option>
          <option value="">---------------</option>
		   <?php echo $countries_menu; ?>
        </select>
      </div>	  
	  
	  <div  class="required" >
	  	<label for="state">Estado/Provincia:</label>
		<iframe id=state name=state src="remote.php?action=regionmenu&countryid=<?php 
		if(!empty($country)) echo $country; 
		else echo "0";
		if(!empty($state_id)) echo "&stateid=".$state_id;
		 ?>" WIDTH=240 HEIGHT=26 FRAMEBORDER=0 SCROLLING=no MARGINWIDTH=0 MARGINHEIGHT=0></iframe>
	  </div>
	     
      <div class="required" id="cityArea">
        <label for="city">Ciudad/Municipio:</label>
        <input type="text" name="city" id="city" class="inputText" size="18" maxlength="100" 
		value="<?php echo $city; ?>" />
      </div>

      <div class="optional" id="postalCodeArea">
        <label for="postal">Zip/C�digo Postal:</label>
        <input type="text" name="postal_code" id="postal_code" class="inputText" size="18" maxlength="50" 
		value="<?php echo $postal_code; ?>" />
      </div>
    </fieldset>


    
    <fieldset>
    <legend>Informacion de Login</legend>
      <div class="notes">
        <h4>Informacion <?php echo $username; ?></h4>
        <p>Tu apodo y   contrase&ntilde;a  deben de ser de por lo menos 6 caracteres de largo y son sensitivos a letras may&uacute;sculas. Por favor no pongas caracteres con acentos. </p>
      </div>
	  
	 <? if (isset($error['username'])) { echo "<div class=\"error\">\n"; } else { ?> 
	 <div class="required" id="userNameArea">
	  <?php } if (isset($error['username'])) echo "<p class=\"error\"> ".$error['username']."</p>"; ?>
	    <span id="userMessage"></span>
        <label for="username">Apodo:</label>
        <input type="text" name="username" id="username" class="inputText" size="18" maxlength="25" 
		value="<?php echo $username; ?>" />

        <small>
		<span style="text-align:left;"><input name="user_availability" type="button" onclick="xmlhttpPost('remote.php','action=checkuser&amp;username='+document.form1.username.value,'displayUserNameResult')" value="ver disponibilidad" /></span>
		<br />
        Solo puede contener  letras, numeros, y gui&oacute;n bajo (_) entre 6 y 20 caracteres de largo.</small>
	</div>
		
	  <? if (isset($error['password'])) { echo "<div class=\"error\">\n"; } else { ?> 
      <div class="required" id="passwordArea">
         <?php } if (isset($error['password'])) echo "<p class=\"error\"> ".$error['password']."</p>"; ?>
        <label for="password"><?php echo $lang['password']; ?> :</label>
        <input type="password" name="password" id="password" class="inputPassword" size="18" maxlength="25" 
		value="<?php echo $password; ?>" />
        <small>Entre 6 y 25 caracteres de largo.</small>      </div>
		
      <? if (isset($error['confirm_password'])) { echo "<div class=\"error\">\n"; } else { ?>
      <div class="required" id="passwordConfirmArea">
         <?php } if (isset($error['donfirm_password'])) echo "<p class=\"error\"> ".$error['donfirm_password']."</p>"; ?>
        <label for="confirm_password"><?php echo $lang['password_confirm']; ?> :</label>
        <input type="password" name="confirm_password" id="confirm_password" class="inputPassword" size="18" maxlength="25" 
		value="<?php echo $confirm_password; ?>" />
        <small>Debe de ser igual al de la casilla anterior .</small>
      </div>
	  
	  <? if(CAPTCHA_REG_ENABLED == 1) { ?>
	  <div class="required" id="captcha">
	  <label for="captcha_image">Verificacion de caracteres:</label>
	  <img src="includes/captcha/captcha.php" alt="CAPTCHA" />
            <input type="text" name="captchastring" size="10">
			<small>Escribe las letras y numeros que ves en la imagen de la izquierda</small>
	  </div>
	  <? } ?>
	  
	  <div class="required" id="terms">
         
        <small><input name="acceptterms" type="checkbox" id="acceptterms" <?php if (isset($acceptterms)) echo  'checked="checked"'; ?> value="1"> <?php echo $lang['accept_terms']; ?></small> 
      </div>
	  
      </fieldset>

    <fieldset>
      <div class="submit">

        <div>
          <input type="button" class="inputSubmit" value="<?php echo $lang['subscribe']; ?> &raquo;" onclick="validate()" />
		 <!-- <input type="submit" /> -->
        </div>
      </div>
    </fieldset>
  </form>
	
<?php
} 
?>
	
	</div>
	<!-- InstanceEndEditable --></div>


</body>
<!-- InstanceEnd --></html>