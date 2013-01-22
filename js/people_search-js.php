<?php 
include('../includes/config.php'); 
?>

Event.observe(window, 'load', init, false);
Event.observe(window, 'unload', byebye, false);
//var url = 'index.php';
var url = '/xmlminiprofiles/';

function init(){
	var page = 1;
	Event.observe('search_button', 'click', function(){show_page(page)}, false);
	Event.observe('search_username_button', 'click', function(){show_page(page)}, false);
	Event.observe('photo_only', 'click', function(){show_page(page)}, false);
	Event.observe('m', 'click', function(){show_page(page)}, false);
	Event.observe('f', 'click', function(){show_page(page)}, false);
	Event.observe('rpp', 'change', function(){show_page(page)}, false);
	Event.observe('country', 'change', function(){show_page(page)}, false);
	show_users();
}

function byebye(){
	document.cookie="rpp="+$F('rpp');
	document.cookie="min_age="+$F('min_age');
	document.cookie="max_age="+$F('max_age');
	document.cookie="m="+$F('m');
	document.cookie="f="+$F('f');
	document.cookie="ponly="+$F('photo_only');
	document.cookie="country="+$F('country');
}

function show_users(){
//------------------------------------------------------------------------------------

//for slider
document.cookie="min_age="+$F('min_age');
document.cookie="max_age="+$F('max_age');


//alert (validateInteger(escape($F('m'))));
var data = '';
var user_country_id = escape($F('country'));
if (validateInteger(user_country_id)) { data += '&user_country_id='+user_country_id; }
var m = escape($F('m'));
if (validateInteger(m)) { data += '&m=1'; }
var f = escape($F('f'));
if (validateInteger(f)) { data += '&f=1'; }
var min_age = escape($F('min_age'));
if (validateInteger(min_age)) { data += '&min_age='+min_age; }
var max_age = escape($F('max_age'));
if (validateInteger(max_age)) {	data += '&max_age='+max_age; }
var rpp = escape($F('rpp'));
if (validateInteger(rpp)) {	data += '&rpp='+rpp; }
var user_city = escape($F('user_city'));
if(user_city.length >= 1 && user_city.length <= 30) data += '&user_city='+user_city;
var user_username = escape($F('user_username'));
if(user_username.length > 0) {
	if(user_username.length >= 2 && user_username.length <= <?php echo USERNAME_MAX_CHARS; ?>) data += '&username='+user_username;
}
var photo_only = escape($F('photo_only'));
if (validateInteger(photo_only)) { data += '&photo_only=1'; }
var p = escape($F('p')); //page
if (validateInteger(p)) { data += '&p='+p; }
data = 'action=mini_profiles'+data;

//$('status').innerHTML = data;
//alert(data);
$('status').innerHTML = '<span style="background-color: #FF0000; color: #FFFFFF; padding: 2px 5px;"><?php echo $lang['searching...']; ?>';

	var myAjax = new Ajax.Request( url, 
	{
	method: 'get', 
	parameters: data, 
	onComplete: showUsersResponse 
	}); 
//xmlhttpPost(url,data,'showUsersResponse');
	
}

function showUsersResponse(originalRequest){
//--------------------------------------------------------------------------------------
//function showUsersResponse(strIn){
	$('usersCell').innerHTML = ''; //clean previous results;
	$('status').innerHTML = '';	
	var p = escape($F('p'));
	
	var strIn = originalRequest.responseXML;
	//var status = strIn.getElementsByTagName('status')[0].firstChild.data;
	var totalCount = strIn.getElementsByTagName('totalcount')[0].firstChild.data;
	if(strIn.getElementsByTagName('users')[0].hasChildNodes()){
		var no_of_user_nodes = strIn.getElementsByTagName('users')[0].childNodes.length;
	}
		
	var usersArray      = strIn.getElementsByTagName('users');
	var userCount = 0;
	var ids = '';
	
		for (var a=0; a<no_of_user_nodes; a++){
		
			if(usersArray[0].hasChildNodes()) {
				//Test Options-----------------------------------------------
				if (usersArray[0].childNodes[a].nodeType != 1) continue; 
				
				userCount++;				
				user_id = usersArray[0].childNodes[a].getAttribute("user_id");
				username = usersArray[0].childNodes[a].getAttribute("user_username");
				country = usersArray[0].childNodes[a].getAttribute("country");
				city = usersArray[0].childNodes[a].getAttribute("user_city");
				photo = usersArray[0].childNodes[a].getAttribute("photo");
				
				build_mini_profile(user_id,username,country,city,photo);
			}
		}
	
	if(userCount==0){
		$('usersCell').innerHTML = "No hay Resultados";
	}
	//$('status').innerHTML = '<span style="background-color: #66CC33; color: #FFFFFF; padding: 2px 5px;">Done</span>';
	var numberOfPagesInMenu = 10;
	var pagesToShowMin = 1;
	var pagesToShowMax = numberOfPagesInMenu;
	var totalPages = Math.ceil(totalCount/$F('rpp'));
	var pages = '';
	//if the current page is 3 pages or less from the last pages showing in the menu
	// let's add another five page offset
	if(p >= pagesToShowMax - 3){
		pagesToShowMax = (p/1) + 4; //(p/1) forces p to become a number
		//let's make sure the menu doesn't get smaller than numberOfPagesInMenu unnecesarily
		if(pagesToShowMax > totalPages){
			//if we are here then pagesToShowMax shows the last of the pages
			pagesToShowMax = totalPages;
		}
		pagesToShowMin = p - numberOfPagesInMenu + 5;
		if(pagesToShowMin > totalPages - numberOfPagesInMenu)
			pagesToShowMin = totalPages - numberOfPagesInMenu;
	}
	//alert(pagesToShowMin);
	for(var i=1; i<=totalPages || i>=pagesToShowMax; i++){
		if(i< pagesToShowMin){
			pages += '...&nbsp;&nbsp; ';
			i = pagesToShowMin;
		}else if(i >= pagesToShowMin && i<= pagesToShowMax){
			if (i == p) {
				pages += '<strong>'+i+'</strong>&nbsp;&nbsp; ';
			}else{
				pages += '<a href="#" onClick="show_page('+i+'); return false;">'+i+'</a>&nbsp;&nbsp;  ';
			}
		}else if (i> pagesToShowMax){
			pages += '...&nbsp;&nbsp; ';
			break;
		}
	}
	if(totalPages > 1 && p != 1){
		var prevPage = p-1;
		pages = '<a href="#" onClick="show_page('+prevPage+');"><<</a> &nbsp;&nbsp;' + pages;
	}
	if(totalPages > 1 && p != totalPages ){
		var nextPage = p/1+1; //made the division so we force p into an integer
		pages = pages + '<a href="#" onClick="show_page('+nextPage+');">>></a>';
	}
	
	// let's add the 'pages' text before 
	//pages = <?php echo $lang['pages']; ?> + ' ' + pages; 
	$('status').innerHTML = totalCount+' <?php echo $lang['profiles']; ?>&nbsp;&nbsp; '+ pages;
	$('statusBottom').innerHTML = pages;
}

function show_page(page){
	var f = document.form1;
	f.p.value = page;
	//document.cookie="rpp="+$F('rpp');
	show_users();
}



function build_mini_profile(user_id,username,country,city,photo){
	var usersArea = $('usersCell');
	var userDiv = document.createElement('div');
	
	var user = '<div id="'+user_id+'"  class="usersMiniProfile">';
	user += '<div class="miniProfilePhoto"><a href="<?php echo PROFILE_DIR_URL.'/'; ?>'+username+'"><img src="'+photo+'" border="0"></a></div>';
	user += '<a href="<?php echo PROFILE_DIR_URL.'/'; ?>'+username+'">'+username+'</a><br />'+country+'</div>';
	//userDiv.setAttribute('id', user_id);
	//userDiv.innerHTML = username +'<br />'+country;
	//userDiv.appendChild(usersArea);
	
	usersArea.innerHTML = usersArea.innerHTML + user;
}

function validateInteger( strValue ) {
  var objRegExp  = /(^-?\d\d*$)/;

  //check for integer characters
  return objRegExp.test(strValue);
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
                        eval(strResultFunc + '(xmlHttpReq.responseXML);');
                }
        }
        xmlHttpReq.send(strSubmit);
}

//Get cookie routine by Shelley Powers 
function get_cookie(Name) {
  var search = Name + "="
  var returnvalue = "";
  if (document.cookie.length > 0) {
    offset = document.cookie.indexOf(search)
    // if cookie exists
    if (offset != -1) { 
      offset += search.length
      // set index of beginning of value
      end = document.cookie.indexOf(";", offset);
      // set index of end of cookie value
      if (end == -1) end = document.cookie.length;
      returnvalue=unescape(document.cookie.substring(offset, end))
      }
   }
  return returnvalue;
}
