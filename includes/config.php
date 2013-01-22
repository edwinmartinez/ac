<?php 

ini_set('memory_limit', '128M');

 require_once('db_settings.php');
    $dbms         = DBMS ;
    $dbhost       = DBHOST ;
    $dbname       = DBNAME ;
    $dbuser       = DBUSER;
    $dbpasswd     = DBPASS;  
	
	
$phpbb_root_dir = "foros";	
    $table_prefix = 'phpbb_' ;

//remember to update also auth/secure.php

define ("SITE_USERS_TABLE", 'users');
define ("BUDDIES_TABLE", 'buddies');
define ("USER_PASS_RESET_TABLE","users_pass_reset");
define ("USER_PREFERENCES_TABLE", 'users_gen_pref');
define ("FAVORITE_PEOPLE_TABLE",'users_fav_people');
define ("PROFILE_COMMENTS_TABLE","users_profile_comments");
define ("USER_ID_FIELD", 'user_id');
define ("USER_EMAIL_FIELD", 'user_email');

define ("COUNTRY_TABLE", 'countries');
define ("COUNTRY_ID_FIELD", 'countries_id');
define ("COUNTRY_NAME_FIELD", 'countries_name_es'); //spanish
define ("STATE_TABLE", 'geo_regions');
define ("STATE_ID_FIELD", 'zone_id');
define ("STATE_NAME_FIELD", 'zone_name');
define ("PHPBB_USERS_TABLE", 'phpbb_users');
define ("PHPBB_MESSAGES_TABLE",'phpbb_privmsgs');
define ("PHPBB_MESSAGES_TEXT_TABLE",'phpbb_privmsgs_text');
define ("USERNAME_FIELD", 'user_username');
define ("USER_LAST_LOGIN_FIELD", 'user_last_login');
define ("USER_CREATED_FIELD", 'user_created');
define ("USERS_GALLERY_TABLE", 'users_gallery');
define ("USERS_GALLERY_COMMENTS_TABLE", 'users_gallery_comments');
define ("TAGS_TABLE",'tags');
define ("TAGS_TO_GALLERY_TABLE",'users_gallery2tag');
define ("USERS_BLOGS_TABLE", 'users_blogs');
define ("USERS_PROFILE_VIEWS_TABLE",'users_profile_views');

define ("DEFAULT_PHOTOS_PER_PAGE",60); //for the photos page


define ("SITE_CONTACT_EMAIL","contacto@amigocupido.com");
define ("SITE_NAME","AmigoCupido");
define ("SECRET_WORD","delfin");


//Main Version of GD 1 or 2
//define ("GD_VERSION", 2);
define ("IMG_MAX_WIDTH",640);
define ("IMG_MAX_HEIGHT",480);
define ("THUMB_MAX_WIDTH",100);
define ("THUMB_MAX_HEIGHT",100);
define ("STORE_MED_IMAGE",1);
define ("IMG_MED_MAX_WIDTH",500);
define ("IMG_MED_MAX_HEIGHT",500);
define ("STORE_SM_IMAGE",1);
define ("IMG_SM_MAX_WIDTH",240);
define ("IMG_SM_MAX_HEIGHT",240);
define ("STORE_SQUARE_IMAGE",1);
define ("SQUARE_MAX_SIZE",80);

//define ("SITE_ROOT_PATH", $_SERVER['DOCUMENT_ROOT'] );
define ("SITE_ROOT_PATH", '/home3/martinm4/public_html/amigocupido' );
define ("SITE_URL","http://www.amigocupido.com");
define ("SCRIPT_BASE_URL",SITE_URL);
define("JAVASCRIPT_DIR_URL", "http://".$_SERVER["SERVER_NAME"].'/js/');
define ("MEMBER_IMG_DIR_PATH", SITE_ROOT_PATH . "/membergallery");
define ("MEMBER_IMG_DIR_URL",  "/membergallery");
define ("GALLERY_UPLOAD_URL","/micuenta/?p=fo-form");

define ("PROFILE_DIR_URL", SITE_URL."/perfil");
define ("MEMBER_BLOG_DIR_URL","/blog/");
define ("MEMBER_ACCOUNT_URL", '/mi_cuenta/');
define ("BUDDY_APPROVAL_URL",SITE_URL."/buddyapproval.php");
define ("RECIPROCRATE_BUDDY_ON_APPROVAL", 1);  // 1 = yes, 0 = no
define ("PEOPLE_SEARCH_URL", "/gente");
//default results per page 
define ("PEOPLE_SEARCH_RPP", 40); 

define ("PHOTOS_URL", SITE_URL . "/fotos");
define ("GALLERY_TAG_SEARCH_URL", SITE_ROOT_PATH . "/".PHOTOS_URL. "/etiquetas");
define ("BLOG_READER_URL",SITE_URL."/blog/"); //managed by .htaccess
define ("BLOG_ADD_URL",SITE_URL."/micuenta/add_blog.php");

define ("PASSWORD_ACTIVATION_BASE_URL", SITE_URL."/pactiv/");
define ("SEND_TO_A_FRIEND_URL",SITE_URL."/ac/send_to_a_friend.php");
define ("REGISTER_URL",SITE_URL."/registrate/");
define("INCLUDES_DIR", $_SERVER['DOCUMENT_ROOT'].'/includes');

#excepttion to the no trailing slash
define('SMARTY_DIR', INCLUDES_DIR.'/smarty/libs/');
define ('LOGIN_REDIRECT_DEFAULT', '/'); 

define("AJAX_MANAGER_URL",SITE_ROOT_PATH."/remote.php");
define("PHPBB_DIR", SITE_ROOT_PATH."/foros");
define("PHPBB_DIR_URL", "/foros");

define("MEMBER_DIR", SITE_ROOT_PATH."/miembro");
define("MEMBER_DIR_URL", "/miembro");

define("ACCOUNT_DIR",  SITE_ROOT_PATH."/micuenta");
define("ACCOUNT_DIR_URL", "/micuenta");
define("MY_PICTURES_URL",  SCRIPT_BASE_URL."/micuenta/index.php?p=fo");

define("PROFILE_VIEWS_PAGE_URL",SCRIPT_BASE_URL."/micuenta/index.php?p=pv");

define("SEARCH_MIN_AGE_DEFAULT",18);
define("SEARCH_MAX_AGE_DEFAULT",65);

define("USERNAME_MIN_CHARS",4);
define("USERNAME_MAX_CHARS",20);
define("PASSWORD_MIN_CHARS",4);
define("PASSWORD_MAX_CHARS",20);

define("PEOPLE_SEARCH_REQUIRE_LOGIN",0);

// Private messaging from phpbb
defined('PRIVMSGS_TABLE') or define('PRIVMSGS_TABLE', 'phpbb_privmsgs');
defined('PRIVMSGS_READ_MAIL') or define('PRIVMSGS_READ_MAIL', 0);
defined('PRIVMSGS_NEW_MAIL') or define('PRIVMSGS_NEW_MAIL', 1);
defined('PRIVMSGS_SENT_MAIL') or define('PRIVMSGS_SENT_MAIL', 2);
defined('PRIVMSGS_SAVED_IN_MAIL') or define('PRIVMSGS_SAVED_IN_MAIL', 3);
defined('PRIVMSGS_SAVED_OUT_MAIL') or define('PRIVMSGS_SAVED_OUT_MAIL', 4);
defined('PRIVMSGS_UNREAD_MAIL') or define('PRIVMSGS_UNREAD_MAIL', 5);


//$sessionVariable = 'acLoggedUser';
define("SESSION_VARIABLE", 'acLoggedUser'); // for use in useraccess.class.php

$forbiden_profile_comment_tags = array("script","table","td","tr","th");
$forbiden_attributes_in_profile_comments = 
	array(	"onclick", "onmouseover", "ondblclick","onfocus", "onkeydown", "onkeypress",
			"onblur", "onkeyup", "onmousedown", "onmousemove", "onmouseout", "onmouseover",
			"onmouseup");

define ("b",150);
$username_min_chars = "4";
$username_max_chars = "20";

$cfgSiteDomainName = "amigocupido.com";
// no trailing slash in cfgHomeUrl please
$cfgHomeUrl = "http://www.".$cfgSiteDomainName;
$cfgCookiename = "ac";
$cfgCookiepath = "/";
$cfgCookiedomain = ".".$cfgSiteDomainName;

//-------- ratings -----------//

$rating_dbhost        = $dbhost;
$rating_dbuser        = $dbuser;
$rating_dbpass        = $dbpasswd;
$rating_dbname        = $dbname;
$rating_tableName     = 'ratings';
$rating_unitwidth     = 30; // the width (in pixels) of each rating unit (star, etc.)
	// if you changed your graphic to be 50 pixels wide, you should change the value above
//-------- ratings end --------//

$top_countries = array("US","CA","ES","MX","GT","SV","HN","NI","CR","PA","DO","CU","CL","PR","PE","EC","VE","BO","UY","PY","AR","BR","CO");

function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}

define("LA_ADD","Agregar");
define("LA_ADD_ME_AS_MALE_BUDDY","A&ntilde;adir como amigo");
define("LA_ADD_ME_AS_FEMALE_BUDDY","A&ntilde;adir como amiga");
define("LA_ADD_YOUR_COMMENT","Agrega tu comentario");
define("LA_ADD_TAG","Agregar Tag");
define("LA_ADD_TAG_HELP","Separa cada tag con un espacio. Por ejemplo: paisaje foto amigos. O para crear 2 palabras usa comillas dobles: \"san salvador\".");
define("LA_APPROVE","Aprovar");
define("LA_ARE_YOU_SURE_DELETE_COMMENT","Estas seguro que quieres borrar este comentario?");
define("LA_MY_FRIENDS","Mis Amigos!");
define("LA_YOU_HAVE_MESSAGES","Tienes Mensajes");
define("LA_MY_HOME","Mi Cuenta");
define("LA_BUDDY_ICON","Foto Icono");
define("LA_BUDDY_REQUEST_MSG",
"Hola %1%, \n %2% Quiere anadirte como su amigo/a.
 
Puedes ver su perfil aqui
%profileurl%

Para aprovar esta petici&#243;n ve con tu browser a este link:
%approvelink%

Para negar esta petici&#243;n ve a este otro link:
%denylink%
");
define("LA_BUDDY_REQUEST_SUBJECT","Nueva amistad en AmigoCupido");
define("LA_SUCCESSFUL_BUDDY_REQUEST","Tu Peticion de amigo/a ha sido mandada");
define("LA_BUDDY_DELETED", "Tu amigo ah sido borrado");
define("LA_BUDDY_RELATION_EXISTS","Ya eres amigo de esta persona o ya mandaste tu petioion para que ser amigos");
define("LA_BAD_BUDDY_REQUEST","Ocurrio un error mientra procesabamos tu peticion");
define("LA_BIG","Grande");
define("LA_BY_CITY","Por ciudad");
define("LA_BY_USERNAME","Por apodo");
define("LA_ERROR_WHILE_PROCESSING","Ocurrio un error mientras procesabamos tu peticion");
define("LA_CANCEL_AND_RETURN_TO_YOUR_PHOTOS","Cancelar y regresar a tus fotos");
define("LA_CHANGE","Cambiar");
define("LA_CHANGE_MY_PASSWORD","Cambiar Mi Contrase&ntilde;a");
define("LA_CHINESE_YEAR_ANIMAL","A&ntilde;o Chino");
define("LA_CLICK_HERE_TO_ADD_DESCRIPTION","Cliquea aqui para agregar descripcion");
define("LA_COMMENTS","Comentarios");
define("LA_COMMENTS_NOTIFICATION_SUBJECT","Tienes un nuevo comentario en tu perfil");
define("LA_COMMENTS_NOTIFICATION_MESSAGE","%commenter_username% a dejado un comentario en tu perfil de AmigoCupido. 
El comentario es el siquiente:
----------------------------------------
%comment%

----------------------------------------
El url de tu perfil es:
%profile_url%");
define("LA_COMMENT_ADDED_SUCCESSFULLY","Tu comentario fue agregado");
define("LA_COMMENT_DELETED_SUCCESSFULLY","Comentario ha sido borrado");
define("LA_COMMENT_COULD_NOT_BE_ADDED","Parece haber un error en tu comentario y no pudimos agregarlo");
define("LA_COMMENT_ADDED_AND_WAITING_FOR_APPROVAL","Tu comentario fue agregado y esta en espera para ser aprovado");
define("LA_COMMENT_UPDATED_SUCCESSFULLY","Comentario ha sido editado");
define("LA_COMMENTS_ARE_NOT_ALLOWED_BY_USER","Usuario ha optado por no permitir comentarios por el momento");
define("LA_CONTAINS_ERRORS","Contiene errores");
define("LA_COUNTRY", "Pais");
define("LA_CREATE_NEW_BLOG_ENTRY","Crear nueva entrada en tu diario");
define("LA_CURRENT_PASSWORD","Contrase&ntilde;a actual");
define("LA_DELETE","Borrar");
define("LA_DOES_NOT_EXIST","no existe");
define("LA_DUPLICATE_FAVORITE_REQUEST","Ya estoy en tu lista de favoritos");
define("LA_BAD_EMAIL_FORMAT","Tu email no esta escrito correctamente");
define("LA_BLOG_ENTRY","Entrada en el diario:");
define("LA_BLOG_ENTRY_ADD","Agregar una entrada en tu diario");
define("LA_BLOG_ENTRY_TITLE","Titulo:");
define("LA_BLOG_ENTRY_SUBMIT","Publicar entrada");
define("LA_BUDDY_APPROVED","Aprovado");
define("LA_BUDDY_APPROVED_ALLREADY","Amigo ya ha sido aprovado");
define("LA_BUDDY_DENIED","Negado");
define("LA_BUDDY_REQUEST_DOES_NOT_EXIST","La peticion de amigo/a no existe.");
define("LA_MY_BUDIES","Mis Amigos");
define("LA_WHY_DO_YOU_REPORT_THIS_PROFILE","Por favor describe porque estas reportando este perfil?");
define("LA_BUDDY_APPROVED_MESSAGE","Has aprovado a <b>%username%</b> la peticion para ser parte de sus amigos.");
define("LA_BUDDY_DENIED_MESSAGE","Has negado a <b>%username%</b> la peticion para ser parte de sus amigos.");
define("LA_DATE","Fecha");
define("LA_DENY","Negar");
define("LA_DUPLICATE_BUDDY_REQUEST","Ya has pedido ser su amigo/a antes. Tienes que esperar por su aprovacion");
define("LA_DUPLICATE_PROFILE_COMMENT","Este comentario parece ser duplicado");
define("LA_EDIT","Editar");
define("LA_EMAIL","Email");
define("LA_EMAIL_MY_PASSWORD","Mandame por email mi contrase&ntilde;a");
define("LA_EMAIL_MY_USERNAME","Mandame por email mi apodo");
define("LA_ERASE","Borrar");
define("LA_ERROR_EMAIL_IN_OUR_RECORDS","Este email ya existe en nuestros records. Por favor, danos otro email");
define("LA_FAVORITE_ADDED_SUCCESSFULLY","Gracias por agregarme a tus favoritos");
define("LA_FAVORITE_DELETED_SUCCESSFULLY","Usuario ha sido borrado de tus favotitos");
define("LA_PHOTO_DELETED_SUCCESSFULLY","Foto ha sido borrada");
define("LA_FIND_THE_IMAGES_ON_YOUR_COMPUTER","Encuentra las photos que quieras en tu computadora.");
define("LA_HERE_IS_YOUR_NEW_PASSWORD","Agui esta tu nueva Contrase&ntilde;a");
define("LA_INBOX","Mensajes Recibidos");
define("LA_LOGIN","Login");
define("LA_LOOK_AT_MY_PHOTOS","Mira mis fotos");
define("LA_MESSAGES","Mensajes");
define("LA_MESSAGE","Mensaje");
define("LA_MY_FAVORITES","Mis Favoritos");
define("LA_MY_ACCOUNT","Mi Cuenta");
//define("LA_MY_ACCOUNT_SETTINGS","Ajustes de Mi Cuenta");
define("LA_MY_PICTURES","Mis Fotos");
define("LA_NEXT","Siguiente");
define("LA_NEW_PASSWORD","Contrase&ntilde;a nueva");
define("LA_ONLY_PROFILES_WITH_PHOTOS","Solo perfiles con fotos");
define("LA_OR","o");
define("LA_OUR_MEMBERS","Nuestros Miembros");
define("LA_OUTBOX","Mensajes de Salida");
define("LA_PASSWORD","Contrase&ntilde;a");
define("LA_PEOPLE_THAT_WANT_TO_ADD_YOU_AS_BUDDY","Gente que te quiere agregar como su amigo");
define("LA_PHOTO_COMMENTS_NOTIFICATION_SUBJECT","Nuevo comentario en \"%photo_title%\"");
define("LA_PHOTO_COMMENTS_NOTIFICATION_MESSAGE","Nuevo Comentario en tu foto.
 %commenter_username% a dejado un comentario en tu una de tus fotos de AmigoCupido.
 
 Para leer este comentario ve a tu foto en AmigoCupido:
 %photo_url% 
 
 Gracias por usar AmigoCupido!
");
define("LA_PICTURE_ICON_CHANGED","Esta imagen es ahora tu nuevo icono");
define("LA_PICTURES","Fotos");
define("LA_PICTURES_OF", "Fotos de");
define("LA_PREVIOUS","Previa");
define("LA_PROFILE_VIEWS","Visitas recientes a mi perfil");
define("LA_PLEASE_LOGIN_TO_PERFORM_THIS_ACTION","Por favor, logea al sistema para hacer esta operacion");
define("LA_REGISTER_OR_LOGIN","<a href=\"%register_url%\">Registrate</a> gratis o <a href=\"%login_url%\">login</a> si ya eres miembro.");
define("LA_REPORT_USER","Reportar usuario");
define("LA_RESULTS_PER_PAGE","Resultados por pagina");
define("LA_RETYPE_PASSWORD","Vuelve a escribir tu contrase&ntilde;a nueva");
define("LA_RETURN_TO_MY_PICTURES","Regresar a mis fotos");
define("LA_SEND_TO_FRIEND","Mandar a un amigo");
define("LA_START_SLIDESHOW","Empezar Slideshow");
define("LA_STOP_SLIDESHOW","Parar Slideshow");
define("LA_ADD_TO_FAVORITES", 'A&ntilde;adir a favoritos');
define("LA_SAVE","Guardar");
define("LA_SAVEBOX","Mensajes Guardados");
define("LA_SAVING","Guardando");
define("LA_SEND","Mandar");
define("LA_SEND_TO_FRIEND_DEST_HEADER","<strong>Email dirigido a:</strong><br>Escribe las direcciones de email, separadas por comas. Maximo 200 caracteres."); 
define("LA_SEND_TO_FRIEND_MESSAGE_HEADER","<strong>Agrega tu mensaje personal:</strong> (opcional)");
define("LA_SEND_TO_FRIEND_DEFAULT_MESSAGE","Esta persona te va a interesar!!");
define("LA_SENT","Mandado");
define("LA_SENTBOX","Mensajes Mandados");
define("LA_SET_IMG_AS_ICON","Hacer esta imagen mi icono");
define("LA_SMALL_FEMALE","Peque&ntilde;a");
define("LA_CANCEL","Cancelar");
define("LA_TAG","Etiqueta (tag)");
define("LA_TAGS","Etiquetas (Tags)");
define("LA_THANKS_FOR_RATING", 'Gracias por tu voto!');
define("LA_THERE_ARE_NO_PHOTOS","No hay fotos en esta seleccion");
define("LA_THERE_ARE_NO_PROFILE_VIEWERS","Todavia no hay visitantes de tu perfil");
define("LA_TIME_AGO","hace %time%");
define("LA_TIME_AWAY","dentro de %time%");
define("LA_UPDATE","Actualizar");
define("LA_UPLOAD","Subir");
define("LA_UPLOAD_PICTURES","Subir fotos &raquo;");
define("LA_USER","Usuario");
define("LA_USER_HAS_NO_PHOTOS","No hay photos");
define("LA_USERNAME","Apodo");
define("LA_USERNAME_MUST_BE_BETWEEN_X_AND_X_CHARACTERS","Apodo debe de ser entre %1% y %2% caracteres");
define("LA_VERIFICATION_CODE","Verificacion de caracteres");
define("LA_VIEW_PROFILE_VIEWS","Ver visitas recientes &raquo;");
define("LA_YEARS_OLD","A&ntilde;os");
define("LA_YOU_DONT_HAVE_BUDDIES","Todavia no tienes amigos.");
define("LA_YOU_DONT_HAVE_PHOTOS","Todavia no tienes fotos. Vamos! Sube unas!");
define("LA_YOU_DONT_FAVORITES","Todavia no tienes favoritos.");
define("LA_YOU_HAVE_X_MESSAGES","Tienes %x% Mensajes");
define("LA_YOU_SURE_WANT_TO_ERASE_PHOTO","Estas segure que quieres borrar esta photo?");
define("LA_YOU_SURE_YOU_WANT_TO_REMOVE_FAVORITE","Estas seguro que quieres quitar a esta persona de tu lista de favoritos?");
define("LA_YOU_SURE_YOU_WANT_TO_DELETE_BUDDY","Estas seguro que quieres quitar a esta persona de tu lista de amigos?");
define("LA_YOUR_PASSWORD_HAS BEEN_CHANGED","Tu contrase&ntilde;a ha sido cambiada exitosamente");
define("LA_BUDDY_PENDING_APPROVAL","(esperando aprovacion)");
define("LA_USER_HAS_BEEN_REPORTED_THANKS","Gracias, el usario ha sido reportado y va a ser revisado por un administrador");
define("LA_USER_HAS_ALREADY_BEEN_REPORTED_THANKS","Gracias, el usario ya ha sido reportado antes y va a ser revisado por un administrador");
define("LA_WOULD_YOU_LIKE_TO_COMMENT","Te gustaria comentar?");
define("LA_ZODIAC_SIGN","Signo del Zodiaco");



define("LA_RAT","Rata");
define("LA_OX","Buey");
define("LA_TIGER","Tigre");
define("LA_RABBIT","Conejo");
define("LA_PIG","Cerdo");
define("LA_MONKEY","Mono");
define("LA_ROOSTER","Gallo");
define("LA_DRAGON","Dragon");
define("LA_SERPENT","Serpiente");
define("LA_HORSE","Caballo");
define("LA_GOAT","Oveja");
define("LA_DOG","Perro");
/*-------western zodiac --------*/
define("LA_ARIES","Aries");
define("LA_TAURUS","Taurus");
define("LA_GEMINI","Geminis");
define("LA_CANCER","Cancer");
define("LA_LEO","Leo");
define("LA_VIRGO","Virgo");
define("LA_LIBRA","Libra");
define("LA_SCORPIO","Scorpio");
define("LA_SAGITTARIUS","Sagitario");
define("LA_CAPRICORN","Capricornio");
define("LA_AQUARIUS","Acuario");
define("LA_PISCES","Piscis");




$lang = array(
	'home' => 'Inicio',
	'people' => 'Gente',
	'contact_us' => 'Contactanos',
	'photos' => 'Fotos',
	'our_members' => 'Nuestros miembros',
	'only_profiles_with_photos' => 'Solo perfiles con fotos',
	'by_city' => 'Por ciudad',
	'by_username' => 'Por apodo',
	'my_favorites' => 'Mis favoritos',
	'my_friends' => 'Mis amigos',
	'waiting_friends' => 'Amigos en en espera de tu aprovacion',
	'results_per_page' => 'Resultados por pagina',
	'my_account_settings' => 'Ajustes de Mi Cuenta',
	'first_name' => 'Nombre',
	'last_name' => 'Apellido',
	'basic_info' => 'Informacion Basica',
	'lifestyle' => 'Estilo de Vida',
	'interests' => 'Intereses',
	'physical_appearance' => 'Apariencia Fisica',
	'personal_info' => 'Informacion Personal',
	'birth_date' => 'Fecha de Nacimiento',
	'username' => 'Apodo', 
	'email' => 'Email', 
	'confirm_email' => 'Confirmacion del Email',
	'invalid_email' => 'Email Invalido',
	'change_my_password'=>'Cambiar Mi Contrase&ntilde;a',
	'change_password' => 'Cambiar Contrase&ntilde;a',
	'old_password' => 'Contrase&ntilde;a Actual',
	'new_password' => 'Contrase&ntilde;a Nueva',
	'confirm_new_password' => 'Confirma tu Contrase&ntilde;a Nueva (escribela otra vez)',
	'inbox' => 'Bandeja de Entrada',
	'outbox' => 'Bandeja de Salida',
	'savebox' => 'Mensajes Guardados',
	'sentbox' => 'Mensajes Enviados',
	'region_alt' => 'Region Alterna',
	'city' => 'Ciudad/Municipio',
	'months' => 'Enero, Febrero, Marzo, Abril, Mayo, Junio, Julio, Agosto, Septiembre, Octubre, Noviembre, Diciembre',
	'day' => 'Dia',
	'month' => 'Mes',
	'year' => 'A&#241;o',
	'accept_terms' => 'Estoy de acuerdo y acepto los <a href="javascript:MM_openBrWindow(\'/terminos_y_condiciones.php\',\'\',\'status=yes,scrollbars=yes,resizable=yes,width=540,height=420\')" class="link-ficha">T&eacute;rminos  y condiciones del usuario</a>',
	'postal_code' => 'Codigo Postal',
	'password' => 'Contrase&ntilde;a',
	'password_confirm' => 'confirma tu contrase&ntilde;a',
	'comments' => 'Comentarios',
	'contains_ilegal_characters' => 'contiene car&#225;cteres ilegales.',
	'is_empty' => 'no contiene nada',
	'username_taken_enter_new_one' => 'Ya hay una persona con este apodo en el systema. Por favor, escribe un nuevo apodo',
	'there_are_errors_in_form' => 'Existe uno o mas errores en esta forma por favor corrigelos',
	'field' => 'casilla',
	'subscribe' => 'Registrame',
	'save' => 'Guardar',
	'searching...' => 'Buscando...',
	'new_members' => 'Nuevos Miembros',
	'file' => 'Archivo',
	'image' => 'Imagen',
	'is_not_a_photo' => 'No Es Una Foto',
	'you_dont_have_saved_pictures' => 'No tienes fotos archivadas',
	'added_fem' => 'Agregada',
	'continue' => 'Continuar',
	'upload_your_picture' => 'Sube tu foto',
	'cancel' => 'Cancelar',
	'or' => '&oacute;',
	'this_field_is_required' => 'esta casilla es requerida',
	'password_and_confirm_password_dont_match' => 'Tus contrase&#241;a y la confirmacion de tu contrase&#241;a no son iguales',
	'username_must_have_min_chars' => 'Tu apodo debe de tener por lo menos ' .$username_min_chars. ' caracteres',
	'username_must_not_exceed_max_chars' => 'Tu apodo no puede exceder los ' .$username_max_chars. ' caracteres',
	'Your' => 'Tu',
	'my_gender_is' => 'Mi sexo es...',
	'seeks_gender' => 'Buscando...',
	'man' => 'Hombre',
	'woman' => 'Mujer',
	'man_or_woman' => 'Hombre o Mujer',
	'select_your_preference' => 'Selecciona tu preferencia',
	'select_your_sex' => '-------Tu sexo-------',
	'my_intentions_are' => 'Mis intenciones son' ,
	'select_your_country' => 'Selecciona tu pa&iacute;s',
	'country_of_residence' => 'Pais de Residencia',
	'music' => 'M&#250;sica',
	'marital_status' => 'Estado civil',
	'nationality' => 'Nacionalidad',
	'children' => 'Hijos',
	'want_children'=>'Deseo hijos',
	'religion' => 'Religion',
	'employment_status' => 'Estado de Empleo',
	'occupation' => 'Profesi&#243;n',
	'hobbies' => 'Pasatiempos',
	'sports' => 'Deportes',
	'drink_habit' => 'Habito de bebida',
	'smoke_habit' => 'Habito de fumar',
	'describe_personality' => 'Acerca de mi y mi personalidad',
	'describe_what_you_looking_for' => 'Descripci&#243;n de la pareja que busco',
	'the_first_things_people_usually_notice_about_me' => 'Las primeras cosas que los demas notan de mi',
	'I_spend_a_lot_of_time_thinking_about' => 'Paso mucho tiempo pensando en',
	'what_Im_doing_with_my_life' => 'Que estoy haciendo con mi vida',
	'Im_really_good_at' => 'Cosas en las que soy bueno(a)',
	
	'logout' => 'Log Out',
	'search' => 'Buscar',
	'people_search' => 'Busca Amigos',
	'and' => 'y',
	'pages' => 'Paginas',
	'profiles' => 'Perfiles',
	'you_have_no_messages' => 'No Tienes Mensajes',
	'you_have' => 'Tienes',
	'messages' => 'Mensajes',
	'message'  => 'Mensaje',
	'see_my_profile' => 'Ver Mi Perfil',
	'hello' => 'Hola',
	'profile' => 'Perfil',
	'settings' => 'Ajustes (Settings)',
	'done_with_changes' => 'Ya Termine Mis Cambios',
	'try_again' => 'Prueba otra vez',
	'edit_profile' => 'Cambiar Mi Perfil',
	'edit' => 'Cambiar',
	'friends_of' => 'Amigos de',
	'send_me_a_message' => 'Mandame un mensaje',
	'add_to_buddies' => 'Agregame como tu amigo',

	
	'between' => 'Entre',
	'gender' => 'Sexo',
	'age' => 'Edad',
	'years_old' => 'A&ntilde;os',
	'race' => 'Raza',
	'seeks_gender' => 'Busco',
	'interests' => 'Intereses',
	'details' => 'Detalles',
	'details_of' => 'Detalles de',
	'body_type' =>'Tipo de Cuerpo',
	'height' => 'Altura',
	'height_cm'=>'&#191;Cual es tu altura?',
	'eyes_color'=>'Color de ojos',
	'hair_color'=>'Color de cabello',
	'hair' => 'Cabello',
	'eyes' => 'Ojos',
	'country' => 'Pais',
	'all_countries' => 'Todos los Paises',
	'income' => 'Ingreso Anual',
	'exercise_freq' => 'Frequencia de Ejercicio',
	'fav_music' => 'Musica Favorita',
	'education' => 'Educacion',
	'turn_ons' => 'Lo que mas me gusta de una persona',
	'turn_offs' => 'Lo que menos me gusta de una persona',
	'relationship_type' => 'Tipo de Relacion Que Busco',
	'last_reading' => 'Ultima Lectura',
	'state' => 'Estado/Provincia',
	'lang_spoken' => 'Lenguages',
	'add_and_change_photos' => 'Agregar/cambiar Fotos',
	'my_account' => 'Mi Cuenta',
	'my_profile' => 'Mi Perfil',
	'the_url_of_my_profile' => 'El URL de mi perfil',
	
);

$lang_gender = array (
	'0' => 'Te digo mas tarde',
	'1' => 'Hombre',
	'2' => 'Mujer',
	'3' => 'Mujer o Hombre',
);	

$lang_relation_type = array (
	'0' => 'Te digo mas tarde ',
	'1' => 'Amor',
	'2' => 'Amistad',
	'3' => 'Amor y Amistad',
);
$lang_marital_status = array (
	'0' => 'Te digo mas tarde',
	'1' => 'Soltero/a',
	'2' => 'Casado/a',
	'3' => 'Viudo/a',
	'4' => 'Divorciado/a',
	'5' => 'Separado/a',
	'6' => 'Unido/a',
);
$lang_race = array (	
	'0' => 'Te digo mas tarde',
	'1' => 'Hispana/Latino',			
	'2' => 'Asi&aacute;tica',
	'3' => 'Cauc&aacute;sica/Blanco',
	'5' => 'Hind&uacute; Oriental',
	'6' => 'India',
	'7' => 'Negra/Afroamericano',
	'8' => 'Nativo Americano',
	'9' => 'Pacifico Islandez',
	'10' => 'Mezclada',
	'255' => 'Otra',
);
$lang_religion = array (
	'0' => 'Te digo mas tarde',
	'1' => 'Cristiana / Cat&oacute;lica',
	'2' => 'Cristiana / Protestante',
	'3' => 'Cristiana / Otra',
	'4' => 'Atea',
	'5' => 'Budista/Taoista',
	'6' => 'Judia',
	'7' => 'Hind&uacute;',
	'8' => 'Islamica',
	'9' => 'Ortodoxa',
	'10' => 'Bahai',
	'11' => 'Agnostica',
	'12' => 'Ninguna',
	'255' => 'Otra',
);
$lang_drink_habit = array (
	'0' => 'Te digo mas tarde',
	'1' => 'No tomo',
	'2' => 'De vez en cuando',
	'3' => 'Tomo frequente',
	'4' => 'Alcoholico',
);
$lang_smoke_habit = array (	
	'0' => 'Te digo mas tarde',
	'1' => 'No fumo',
	'2' => 'De vez en cuando',
	'3' => 'Fumo frequente',
	'4' => 'Es mi adicci&#243;n',
);
$lang_have_kids = array (
	'0' => 'Te digo mas tarde',
	'1' => 'Si - en casa siempre',
	'2' => 'No',
	'230' => 'Si - en casa a veces',
	'231' => 'Si - pero viven fuera',
);
$lang_want_kids = array (	
	'0' => 'Te digo mas tarde',
	'1' => 'Si',
	'2' => 'No',
	'3' => 'Indeciso(a)',
);
$lang_income = array (
	'0' => 'Te digo mas tarde',
	'1' => 'menos de $20,000',
	'2' => '$20,000 - $30,000',
	'3' => '$30,000 - $50,000',
	'4' => '$50,000 - $75,000',
	'5' => '$75,000 - $100,000',
	'6' => '$100,000 - $125,000',
	'7' => 'mas de $125,000',
);
$lang_education = array (
	'0' => 'Te digo mas tarde',
	'1' => 'Escuela Primaria',
	'2' => 'Escuela Secundaria',
	'3' => 'Algunos a&ntilde;os de Universidad',
	'4' => 'Titulo Univ. 2 a&ntilde;os',
	'5' => 'Titulo Univ. 4 a&ntilde;os o mas',
	'6' => 'Maestr&iacute;a',
	'7' => 'Doctorado',
);
$lang_exercise_freq = array (
	'0' => 'Te digo mas tarde',
	'1' => 'Todos los dias',
	'2' => 'Unos dias a la semana',
	'3' => 'Unos dias al mes',
	'4' => 'De ves en cuando',
	'5' => 'Nunca',
);	
$lang_employment_status = array (
	'0' => 'Te digo mas tarde',
	'1' => 'Fijo',
	'2' => 'Estudiante',
	'3' => 'Estudio y trabajo',
	'4' => 'Empleo temporal',
	'5' => 'Media Jornada',
	'6' => 'Tengo mi empresa',
	'7' => 'Independiente',
	'8' => 'Trabajo en casa',
	'9' => 'Desempleado/a',
	'10' => 'Retirado/a',
);

$lang_occupational_area = array (
'0'  => 'Te digo mas tarde',
'1'  => 'Contador/Auditoria',
'2'  => 'Admin./Servicios de Soporte',
'3'  => 'Publicidad/Mercadeo/Rel. Publicas',
'4'  => 'Aeroespacio/Aviacion/Defensa',
'5'  => 'Agricultura, Foresta, & Pesca',
'6'  => 'Aerolineas',
'7'  => 'Servicios de Arquitectura',
'8'  => 'Agricultura/Ganaderia',
'9'  => 'Arte, Entretenimiento, y Media',
'10' => 'Automotor Vehicle/Parts',
'11' => 'Banco',
'12' => 'Biotecnologia and Farmacos',
'13' => 'Servicios de Computadora',
'14' => 'Computadoras, Hardware',
'15' => 'Computadoras, Software',
'16' => 'Construccion',
'17' => 'Servicios de Consulta',
'18' => 'Servicio al Cliente y Call Center',
'19' => 'Educacion/Entrenamiento',
'20' => 'Electronica',
'21' => 'Agencia de Empleos',
'22' => 'Energia/Utilidades',
'23' => 'Ingenieria',
'24' => 'Management Ejecutivo',
'25' => 'Finanzas/Economia',
'26' => 'Gobierno',
'27' => 'Servicios M&#233;dicos/Salud',
'28' => 'Hospitabilidad/Turismo',
'29' => 'Recursos Humanos',
'30' => 'Seguros',
'31' => 'Internet/E-Commerce',
'32' => 'Policia/Servicios de Seguridad',
'33' => 'Legal',
'34' => 'Produccion/Manufacturacion',
'35' => 'Militar',
'36' => 'Organizacion sin Fines de Lucro',
'37' => 'Manejo de Operaciones',
'38' => 'Mantencion y Belleza Personal',
'39' => 'Project/Program Management',
'40' => 'Imprenta/Publicaciones',
'41' => 'Bienes y Raices/Inmobiliario',
'42' => 'Investigaci&#243;n y Desarrollo',
'43' => 'Restaurante/Servicios de Comida',
'44' => 'Ventas',
'45' => 'Ciencia',
'46' => 'Deportes y Recreacion',
'47' => 'Logistica',
'48' => 'Telecomunicaciones',
'49' => 'Textiles',
'50' => 'Transportacion/Bodegamiento',
'51' => 'Servicios Veterinarios',
'52' => 'Tienda/Productos al consumidor',
'255'=> 'Otra',
);

$lang_height_cm = array (
'0' => 'Te digo mas tarde',
'140' => '4\' 7" (140 cm) o menos',
'142' => '4\' 8" (142 cm)',
'145' => '4\' 9" (145 cm)',
'147' => '4\' 10" (147 cm)',
'150' => '4\' 11" (150 cm)',
'152' => '5\' (152 cm)',
'155' => '5\' 1" (155 cm)',
'157' => '5\' 2" (157 cm)',
'160' => '5\' 3" (160 cm)',
'162' => '5\' 4" (162 cm)',
'165' => '5\' 5" (165 cm)',
'167' => '5\' 6" (167 cm)',
'170' => '5\' 7" (170 cm)',
'173' => '5\' 8" (173 cm)',
'175' => '5\' 9" (175 cm)',
'178' => '5\' 10" (178 cm)',
'180' => '5\' 11" (180 cm)',
'183' => '6\' (183 cm)',
'185' => '6\' 1" (185 cm)',
'188' => '6\' 2" (188 cm)',
'190' => '6\' 3" (190 cm)',
'193' => '6\' 4" (193 cm)',
'196' => '6\' 5" (196 cm)',
'198' => '6\' 6" (198 cm)',
'201' => '6\' 7" (201 cm)',
'203' => '6\' 8" (203 cm)',
'206' => '6\' 9" (206 cm)',
'208' => '6\' 10" (208 cm)',
'211' => '6\' 11" (211 cm)',
'213' => '7\' (213 cm)',
'216' => '7\' 1" (216 cm)',
'218' => '7\' 2" (218 cm)',
'221' => '7\' 3" (221 cm)',
'224' => '7\' 4" (224 cm)',
'226' => '7\' 5" (226 cm)',
'229' => '7\' 6" (229 cm)',
'231' => '7\' 7" (231 cm)',
'234' => '7\' 8" (234 cm)',
'236' => '7\' 9" (236 cm)',
'239' => '7\' 10" (239 cm) o mas',
);
$lang_eyes_color = array (
'0' => 'Te lo dire despu&#233;s',
'1' => 'Caf&#233;',
'2' => 'Azul',
'3' => 'Casta&#241;o',
'4' => 'Gris',
'5' => 'Verde',
'6' => 'Negro',
);
$lang_hair_color = array (
'0' => 'Te lo dire despu&#233;s',
'1' => 'Caf&#233;',
'2' => 'Casta&#241;o',
'3' => 'Rubio',
'4' => 'Rojo',
'5' => 'Blanco/Canas',
'6' => 'Calvo/Pelon',
'7' => 'Canoso',
'8' => 'Negro',
);
$lang_body_type = array (
'0' => 'Te lo dire despu&#233;s',
'1' => 'Delgada/o',
'2' => 'Normal',
'3' => 'Estoy en linea',
'4' => 'Atl&#233;tica/o',
'5' => 'Musculoso/a',
'6' => 'Una libritas de mas',
'7' => 'Llena/o',
);

$lang_languages = array(
	'en' => 'Ingl&#233;s',
	'es' => 'Espa&ntilde;ol',
	'de' => 'Alem&#225;n',
	'ar' => 'Arabe',
	'ca' => 'Catalan',	
	'zh' => 'Chino',
	'ko' => 'Coreano',
	'fi' => 'Finland&#233;s',	
	'fr' => 'Franc&#233;s',
	'gl' => 'Ga&#233;lico',	
	'he' => 'Hebreo',
	'nl' => 'Holand&#233;s',		
	'hi' => 'Ind&#250;',	
	'it' => 'Italiano',	
	'ja' => 'Japon&#233;s',	
	'no' => 'Noruego',	
	'pl' => 'Polaco',	
	'pt' => 'Portugu&#233;s',	
	'ru' => 'Ruso',	
	'sv' => 'Sueco',			
	'tg' => 'Tagalog',		
	'tr' => 'Turco',	
	'ot' => 'Otro',
);

/*
<label><input name="10N[]" value="29" type="checkbox">Tatuaje escondido</label><br>
<label><input name="10N[]" value="30" type="checkbox">Tatuaje visible</label><br>
<label><input name="10N[]" value="225" type="checkbox">Cicatriz Arte</label><br>
<label><input name="10N[]" value="228" type="checkbox">Perforacion de ombligo</label><br>
<label><input name="10N[]" value="229" type="checkbox">Perforaciones en otras partes</label><br>

<input name="13N[]" value="34" type="checkbox">Aerobicos</label><br>
<label><input name="13N[]" value="35" type="checkbox">Artes marciales</label><br>
<label><input name="13N[]" value="36" type="checkbox">Correr</label><br>
<label><input name="13N[]" value="37" type="checkbox">Carreras de Motos/Autos</label><br>
<label><input name="13N[]" value="372" type="checkbox">B&#233;isbol</label><br>
<label><input name="13N[]" value="373" type="checkbox">Esqu&#237;</label><br>
<label><input name="13N[]" value="374" type="checkbox">Baloncesto</label><br>
<label><input name="13N[]" value="375" type="checkbox">F&#250;tbol</label><br>
<label><input name="13N[]" value="376" type="checkbox">Billar</label><br>
<label><input name="13N[]" value="377" type="checkbox">Nataci&#243;n</label><br>
<label><input name="13N[]" value="378" type="checkbox">Bolos</label><br>
<label><input name="13N[]" value="379" type="checkbox">Tenis / Deportes de raqueta</label><br>
<label><input name="13N[]" value="380" type="checkbox">Bicicletas</label><br>
<label><input name="13N[]" value="381" type="checkbox">Volleybal</label><br>
<label><input name="13N[]" value="383" type="checkbox">El caminar</label><br>
<label><input name="13N[]" value="384" type="checkbox">F&#250;tbol Americano</label><br>
<label><input name="13N[]" value="385" type="checkbox">Pesas / Maquinas de Ejercisio</label><br>
<label><input name="13N[]" value="386" type="checkbox">Golf</label><br>
<label><input name="13N[]" value="387" type="checkbox">Yoga</label>

<input name="16N[]" value="38" type="checkbox">Conexion de alumnos</label><br>
<label><input name="16N[]" value="39" type="checkbox">Discotecas/Bailar</label><br>
<label><input name="16N[]" value="40" type="checkbox">Acampar</label><br>
<label><input name="16N[]" value="41" type="checkbox">El cocinar</label><br>
<label><input name="16N[]" value="388" type="checkbox">Socios de negocio</label><br>
<label><input name="16N[]" value="389" type="checkbox">Club de libros / Discusi&#243;nes</label><br>
<label><input name="16N[]" value="390" type="checkbox">Caf&#233; y conversaci&#243;n</label><br>
<label><input name="16N[]" value="391" type="checkbox">Restaurantes</label><br>
<label><input name="16N[]" value="392" type="checkbox">La pesca / cazar</label><br>
<label><input name="16N[]" value="393" type="checkbox">Las Plantas de Jard&#237;n</label><br>
<label><input name="16N[]" value="394" type="checkbox">Costura / Bordar / Tejer</label><br>
<label><input name="16N[]" value="395" type="checkbox">Cine / Pel&#237;culas</label><br>
<label><input name="16N[]" value="396" type="checkbox">Museos / Galer&#237;as de Arte</label><br>
<label><input name="16N[]" value="397" type="checkbox">M&#250;sica y conciertos</label><br>
<label><input name="16N[]" value="398" type="checkbox">Teatro / Ballet</label><br>
<label><input name="16N[]" value="399" type="checkbox">Barajas</label><br>
<label><input name="16N[]" value="400" type="checkbox">Jugando deportes</label><br>
<label><input name="16N[]" value="401" type="checkbox">La pol&#237;tica</label><br>
<label><input name="16N[]" value="402" type="checkbox">Religi&#243;n</label><br>
<label><input name="16N[]" value="403" type="checkbox">Ir de Compras</label>
*/


function get_user_country($country_id){
	global $lang;
	$sql = "SELECT ".COUNTRY_NAME_FIELD." from ".COUNTRY_TABLE." WHERE ".COUNTRY_ID_FIELD."=".$country_id;
	if ( !($result = mysql_query($sql)) ) {
		printf('Could not select country at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql);
	}else{
		$country = mysql_fetch_assoc($result);
		return $country[COUNTRY_NAME_FIELD];
	}
	mysql_free_result($result);
}

function db_get_user_country($country_id){
	global $dbhost, $dbuser, $dbpasswd, $dbname,$lang;
	
	mysql_connect($dbhost, $dbuser, $dbpasswd) or
			die("Could not connect: " . mysql_error());
		mysql_select_db($dbname);
		
	$sql = "SELECT ".COUNTRY_NAME_FIELD." from ".COUNTRY_TABLE." WHERE ".COUNTRY_ID_FIELD."=".$country_id;
	if ( !($result = mysql_query($sql)) ) {
		printf('Could not select country at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql);
	}else{
		$country = mysql_fetch_assoc($result);
		return $country[COUNTRY_NAME_FIELD];
	}
}



function db_get_user_state($state_id){
	global $dbhost, $dbuser, $dbpasswd, $dbname,$lang;
	
	mysql_connect($dbhost, $dbuser, $dbpasswd) or
			die("Could not connect: " . mysql_error());
		mysql_select_db($dbname);
		
	$sql = "SELECT ".STATE_NAME_FIELD." from ".STATE_TABLE." WHERE ".STATE_ID_FIELD."=".$state_id;
	if ( !($result = mysql_query($sql)) ) {
		printf('Could not select state at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql);
	}else{
		$state = mysql_fetch_assoc($result);
		return $state[STATE_NAME_FIELD];
	}
}

function get_profile_photo($user_id,$bigpic=0){
	global $dbhost, $dbuser, $dbpasswd, $dbname,$lang;
		
	mysql_connect($dbhost, $dbuser, $dbpasswd) or
			die("Could not connect: " . mysql_error());
	mysql_select_db($dbname);
	//lets get the picture

	$uid = USER_ID_FIELD;
	$sql = "SELECT * from ".USERS_GALLERY_TABLE." where ";
	$sql .= "photo_uid='".$user_id."' ";
	$sql .= "and  use_in_profile=1 limit 1";
	
	
	
	$profile_pic_result = mysql_query($sql);
	$profile_pic_rows = mysql_num_rows($profile_pic_result);
	
	$sql2 = "SELECT user_gender,user_username as username from ".SITE_USERS_TABLE." WHERE user_id = ".$user_id." limit 1";	
	$user_result = mysql_query($sql2);
	$user_num_rows = mysql_num_rows($user_result);
	$profile = mysql_fetch_assoc($user_result);
	
	
	if($profile_pic_rows > 0){
		$profile_pic_row = mysql_fetch_assoc($profile_pic_result);
		list($uid,$imgname,$extention) = explode(".",$profile_pic_row['photo_filename']);
		$basefilename = $uid.".".$imgname;
		$largefilename = $basefilename."_l.".$extention;
		$squarefilename = $basefilename."_sq.".$extention;
		if($bigpic == 1){
			$profile_pic = MEMBER_IMG_DIR_URL."/".$uid."/".$largefilename;
		}else{
			//$profile_pic = MEMBER_IMG_DIR_URL . "/tb_".$profile_pic_row['photo_filename'];
			$profile_pic = MEMBER_IMG_DIR_URL ."/".$uid."/".$squarefilename;
		}
		
	}else{
		if($bigpic == 1){
			return false;
		}else{
			if($profile['user_gender'] == 1) { $profile_pic = "/images/nofoto_m.jpg";   }
			else{                                   $profile_pic = "/images/nofoto_f.jpg";   }	
		}
	}
	
	mysql_free_result($profile_pic_result);
	return $profile_pic;
}


function get_new_messages() {
	$CurrentTime		= time();
	$_SESSION['User']['Messages']	= array();
	$sql = "SELECT COUNT(*) as MessageCount 
	FROM " . PRIVMSGS_TABLE . " 
	WHERE privmsgs_to_userid = ". $_SESSION['user_id']."
	AND privmsgs_type IN (" . PRIVMSGS_NEW_MAIL . ", " . PRIVMSGS_UNREAD_MAIL . ")";
	$result	= @mysql_query($sql);
	if($row = @mysql_fetch_array($result)) {
		$MessageCount	= $row['MessageCount'];
		//echo "messages: $MessageCount ";
		$_SESSION['User']['Messages']['Count']	= $MessageCount;
	}


	// Set the time messages were lst checked
	$_SESSION['User']['Messages']['Time']	= $CurrentTime;
	return $MessageCount;
}

function get_username($user_id){
   	$sql1 = "SELECT * from ".SITE_USERS_TABLE." WHERE user_id = ".$user_id ." limit 1";	
	if ( !($result = mysql_query($sql1))){
					printf('Could not select record at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql1);
					exit;
	}
	$row = mysql_fetch_assoc($result);
	return $row['user_username'];
}

function get_userid($username){
   	$sql1 = "SELECT * from ".SITE_USERS_TABLE." WHERE user_username = '".$username ."' limit 1";
	$result = mysql_query($sql1) or die(mysql_error());	
	$row = mysql_fetch_assoc($result);
	return $row['user_id'];
}

function get_users ($limit=6,$gender=0,$order_by=USER_CREATED_FIELD,$order_type='desc',$return_array_format=0){
	global $dbhost, $dbuser, $dbpasswd,$dbname,$profile;
	mysql_connect($dbhost, $dbuser, $dbpasswd) or die("Could not connect: " . mysql_error());
	mysql_select_db($dbname);
	
	$sql  = "SELECT * from ".SITE_USERS_TABLE;
	if($gender > 0)
		$sql .= " WHERE user_gender = '".$gender."' ";
	if($order_by != USER_CREATED_FIELD)
		$sql .= " ORDER BY ".$order_by;
	else
		$sql .= " ORDER BY ".USER_CREATED_FIELD;
	$sql .= " ".$order_type." limit ".$limit;
	
	$result = mysql_query($sql) or die(mysql_error());
	if ( !($result = mysql_query($sql)) ) { printf('Could not select users at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql);
	} else {
		$total_rows = mysql_num_rows($result);
		if ($total_rows < 1) {
		}else{
			if($return_array_format == 1){
				for($i=0;$i<$total_rows;$i++){
					$profile = mysql_fetch_assoc($result);
					$profile['user_username'] = strtolower(preg_replace('/&#241;/','n',$profile['user_username']));
					$new_users[$i] = $profile;
					list($year,$month,$day) = explode("-",$profile['user_birthdate']);
						$age = date("Y") - $year;
						if(date("md") < $month.$day) { $age--; }
					$new_users[$i]['age'] = $age;
					$new_users[$i]['photo'] = get_profile_photo($profile['user_id']);
					$new_users[$i]['user_city'] = ucwords(strtolower($profile['user_city']));
					$new_users[$i]['country'] = db_get_user_country($profile['user_country_id']);
					
				}
			}else{
				for($i=0;$i<$total_rows;$i++){
					$profile = mysql_fetch_assoc($result);
					$profile['user_username'] = preg_replace('/&#241;/','n',$profile['user_username']);
	
					$new_users .= '<div class="usersMiniProfile">
					<div class="miniProfilePhoto"><a href="'.PROFILE_DIR_URL.'/'.$profile['user_username'].'"><img src="'.get_profile_photo($profile['user_id']).'" border="0" /></a></div>
					<a href="'.PROFILE_DIR_URL.'/'.$profile['user_username'].'">'.$profile['user_username']."</a><br>";
					$new_users .= ucwords(strtolower($profile['user_city'])).' <br>';
					$new_users .= db_get_user_country($profile['user_country_id']).' </div>'."\n";
					
				}
			}
		}
		mysql_free_result($result);
		return $new_users;      
	}
}

function get_profile_info($uid){
	global $dbhost,$dbuser,$dbpasswd,$dbname;
	if(!$uid) $uid = $HTTP_SESSION_VARS['user_id'];	
//	$sql = "SELECT * from ".SITE_USERS_TABLE." where "; 
//	$sql .= USER_ID_FIELD." = '" . $uid . "'"
//				   ." limit 1";
				   
	$sql = "SELECT * FROM ".SITE_USERS_TABLE." LEFT JOIN ".USER_PREFERENCES_TABLE
			." USING (user_id) where ".SITE_USERS_TABLE.".".USER_ID_FIELD." =".$uid." limit 1"; 
	
	mysql_connect($dbhost, $dbuser, $dbpasswd) or die("Could not connect: " . mysql_error());
	mysql_select_db($dbname);
	if ( !($result = mysql_query($sql)) ) {
		printf('Could not select username at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql);
	} else {
		$total_rows = mysql_num_rows($result);
		if ($total_rows < 1) {
			user_does_not_exist();
		}else{
			$profile = mysql_fetch_assoc($result);
			
		}
		mysql_free_result($result);
		return $profile;
	}

}

function get_buddy_count($of_user_id,$confirmed=1){

		$sqlcount = "SELECT count(*) as count from ".BUDDIES_TABLE." where user_uid=".$of_user_id." and confirmed=1";
		$result = mysql_query($sqlcount) or die(mysql_error());
		$row = mysql_fetch_row($result); 
		return $row[0];
}

// in progress -------
function get_buddies($of_user_id,$buddie_types='approved',$limit='',$get_pic=1){ //buddy_types 'all' or 'approved' or 'waiting'
	global $dbhost,$dbuser,$dbpasswd,$dbname;
	$buddies = array();
	$sql = "SELECT * from ".BUDDIES_TABLE." where user_uid=".$of_user_id;
	if($buddie_types == 'approved'){
		$sql .= " and confirmed=1";
	}else if ($buddie_types == 'waiting'){
		$sql .= " and confirmed=0";
	}else{
		//else is all so we don't need to add to the sql
	}
	$sql .= " order by buddy_request_date";
	if (!empty($limit)){
		$sql .= " limit ".$limit;
	}
	$result = mysql_query($sql) or die(mysql_error());
	if ( !($result = mysql_query($sql)) ) { printf('Could not select friends at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql);}
	$num_rows = mysql_num_rows($result);

	if($num_rows > 0){
		while($buddyrow=mysql_fetch_assoc($result)){
				$sql2 = "SELECT * from ".SITE_USERS_TABLE." WHERE user_id = ".$buddyrow['user_uid']." limit 1";	
				//echo $sql2."<br>";
				$result2 = mysql_query($sql2) or die(mysql_error());
				$profile = mysql_fetch_assoc($result2);
				$buddy_username = preg_replace('/&#241;/','n',get_username($buddyrow['buddy_uid']));
				array_push($buddies,array(
										'username'=> $buddy_username,
										'buddy_uid'=> $buddyrow['buddy_uid'],
										'confirmed'=>$buddyrow['confirmed'],
										'profile_link'=>PROFILE_DIR_URL.'/'.$buddy_username,
										'image' =>get_profile_photo($buddyrow['buddy_uid'])
										)
						 );
			
		}
		return $buddies;
	}else{
		return 0;
	}
}

function get_waiting_buddies(){
	$sql = "SELECT * from ".BUDDIES_TABLE." where buddy_uid=".$_SESSION['user_id']." and confirmed=0 order by buddy_request_date";
	$result = mysql_query($sql) or die(mysql_error());
	if ( !($result = mysql_query($sql)) ) { printf('Could not select countries at line %s file: %s <br> sql:%s',  __LINE__, __FILE__, $sql);}
	$num_rows = mysql_num_rows($result);

	if($num_rows > 0){
		$waiting_buddies = '<div class="waitingBuddyHead">'.LA_PEOPLE_THAT_WANT_TO_ADD_YOU_AS_BUDDY.'</div>';
		while($buddyrow=mysql_fetch_assoc($result)){
				$sql2 = "SELECT * from ".SITE_USERS_TABLE." WHERE user_id = ".$buddyrow['user_uid']." limit 1";	
				//echo $sql2."<br>";
				$result2 = mysql_query($sql2) or die(mysql_error());
				$profile = mysql_fetch_assoc($result2);
				
				$profile['user_username'] = preg_replace('/�/','n',$profile['user_username']);
				$waiting_buddies .= '<div class="waitingBuddy" id="'.$buddyrow['user_uid'].'_waiting">';
				$waiting_buddies .= '<img src="/images/icon_smile_gray.gif" border="0" /> <a href="'.PROFILE_DIR_URL.'/'.$profile['user_username'].'">'.$profile['user_username']."</a>";
				$waiting_buddies .= "<br>";
				$waiting_buddies .= '<a class="approveBuddyLink" href="#" onclick="approveBuddy('.$buddyrow['user_uid'].',\''.$buddyrow['approvalcode'].'\'); return false;">'.LA_APPROVE.'</a> ';
				$waiting_buddies .= '| <a class="approveBuddyLink" href="#" onclick="denyBuddy('.$buddyrow['user_uid'].',\''.$buddyrow['approvalcode'].'\'); return false;">'.LA_DENY.'</a>';
				$waiting_buddies .= ' </div>'."\n";
		}
		return $waiting_buddies;
	}else{
		return 0;
	}
}



function gen_rand_string($hash){
	$chars = array( 'a', 'A', 'b', 'B', 'c', 'C', 'd', 'D', 'e', 'E', 'f', 'F', 'g', 'G', 'h', 'H', 'i', 'I', 'j', 'J',  'k', 'K', 'l', 'L', 'm', 'M', 'n', 'N', 'o', 'O', 'p', 'P', 'q', 'Q', 'r', 'R', 's', 'S', 't', 'T',  'u', 'U', 'v', 'V', 'w', 'W', 'x', 'X', 'y', 'Y', 'z', 'Z', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0');
	
	$max_chars = count($chars) - 1;
	srand( (double) microtime()*1000000);
	
	$rand_str = '';
	for($i = 0; $i < 8; $i++)
	{
		$rand_str = ( $i == 0 ) ? $chars[rand(0, $max_chars)] : $rand_str . $chars[rand(0, $max_chars)];
	}

	return ( $hash ) ? md5($rand_str) : $rand_str;
}

function truncate ($str, $length=10, $trailing='...') 
{
      // take off chars for the trailing
      $length-=strlen($trailing);
      if (strlen($str) > $length) 
      {
         // string exceeded length, truncate and add trailing dots
         return substr($str,0,$length).$trailing;
      } 
      else 
      { 
         // string was already short enough, return the string
         $res = $str; 
      }
  
      return $res;
}

function truncate_words($str, $length=30, $trailing=' ...'){
	//remove double spaces;
	$str = preg_replace('#\s+#', ' ', $str);
	$words = explode(' ',$str);
	if(count($words) > $length){
		$space = '';
		for($i=0;$i<$length-1;$i++){
			$new_str .= $space.$words[$i];
			$space = ' ';
		}
		$new_str = $new_str.$trailing;
	}else{
		$new_str = implode(' ',$words);
	}	
	return $new_str;
}

function sortByField($multArray,$sortField,$desc=true){
   $tmpKey='';
   $ResArray=array();

   $maIndex=array_keys($multArray);
   $maSize=count($multArray)-1;

   for($i=0; $i < $maSize ; $i++) {

	   $minElement=$i;
	   $tempMin=$multArray[$maIndex[$i]][$sortField];
	   $tmpKey=$maIndex[$i];

	   for($j=$i+1; $j <= $maSize; $j++)
		 if($multArray[$maIndex[$j]][$sortField] < $tempMin ) {
			 $minElement=$j;
			 $tmpKey=$maIndex[$j];
			 $tempMin=$multArray[$maIndex[$j]][$sortField];

		 }
		 $maIndex[$minElement]=$maIndex[$i];
		 $maIndex[$i]=$tmpKey;
   }

   if($desc)
	   for($j=0;$j<=$maSize;$j++)
		 $ResArray[$maIndex[$j]]=$multArray[$maIndex[$j]];
   else
	 for($j=$maSize;$j>=0;$j--)
		 $ResArray[$maIndex[$j]]=$multArray[$maIndex[$j]];

   return $ResArray;
}

function translate_sign($sign){
	switch($sign) {
	case 'aries':
		return LA_ARIES;
	case 'taurus':
		return LA_TAURUS;
	case 'gemini':
		return LA_GEMINI;
	case 'cancer':
		return LA_CANCER;
	case 'leo':
		return LA_LEO;
	case 'virgo':
		return LA_VIRGO;
	case 'libra':
		return LA_LIBRA;
	case 'scorpio':
		return LA_SCORPIO;
	case 'sagittarius':
		return LA_SAGITTARIUS;
	case 'capricorn':
		return LA_CAPRICORN;
	case 'aquarius':
		return LA_AQUARIUS;
	case 'pisces':
		return LA_PISCES;
		//chinese---------
	case 'monkey':
		return LA_MONKEY;
	case 'rooster':
		return LA_ROOSTER;
	case 'dog':
		return LA_DOG;
	case 'pig':
		return LA_PIG;
	case 'rat':
		return LA_RAT;
	case 'ox':
		return LA_OX;
	case 'tiger':
		return LA_TIGER;
	case 'rabbit':
		return LA_RABBIT;
	case 'dragon':
		return LA_DRAGON;
	case 'serpent':
		return LA_SERPENT;
	case 'horse':
		return LA_HORSE;
	case 'goat':
		return LA_GOAT;
	}
}


?>