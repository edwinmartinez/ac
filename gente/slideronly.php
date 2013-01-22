<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Slider Only</title>
<script language="javascript" src="../js/prototype.js"></script>
<script src="../js/scriptaculous/scriptaculous.js" type="text/javascript"></script>
<link href="../styles/profile.css" rel="stylesheet" type="text/css" />
</head>

<body>
<script language="javascript">

Event.observe(window, 'load', init, false);
var url = 'index.php';

function init(){
	Event.observe('search_button', 'click', function(){show_page(1)}, false);
	Event.observe('photo_only', 'click', function(){show_page(1)}, false);
	Event.observe('m', 'click', function(){show_page(1)}, false);
	Event.observe('f', 'click', function(){show_page(1)}, false);
	Event.observe('rpp', 'change', function(){show_page(1)}, false);
	Event.observe('country', 'change', function(){show_page(1)}, false);
	show_users();
}

function show_users(){
//------------------------------------------------------------------------------------
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
var photo_only = escape($F('photo_only'));
if (validateInteger(photo_only)) { data += '&photo_only=1'; }
var p = escape($F('p')); //page
if (validateInteger(p)) { data += '&p='+p; }
data = 'action=mini_profiles'+data;

$('statusArea').innerHTML = data;
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
	
	if(!userCount){
		$('usersCell').innerHTML = "No hay Resultados";
	}
	//$('status').innerHTML = '<span style="background-color: #66CC33; color: #FFFFFF; padding: 2px 5px;">Done</span>';
	var totalPages = Math.ceil(totalCount/$F('rpp'));
	var pages = '<?php echo $lang['pages']; ?> ';
	for(var i=1; i<=totalPages; i++){
		if (i == p) {
			pages += '<strong>'+i+'</strong>&nbsp;&nbsp; ';
		}else{
			pages += '<a href="#" onClick="show_page('+i+');">'+i+'</a>&nbsp;&nbsp;  ';
		}
	}
	$('status').innerHTML = totalCount+' <?php echo $lang['profiles']; ?>&nbsp;&nbsp; '+ pages;
	
}

function show_page(page){
	var f = document.form1;
	f.p.value = page;
	//alert('page'+page);
	show_users();
}

function build_mini_profile(user_id,username,country,city,photo){
	var usersArea = $('usersCell');
	var userDiv = document.createElement('div');
	
	var user = '<div id="'+user_id+'"  class="usersMiniProfile">';
	user += '<a href="../perfil/'+username+'"><img src="'+photo+'" border="0"></a><br>';
	user += '<a href="../perfil/'+username+'">'+username+'</a><br />'+country+'</div>';
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

</script>

<div id="homeContentWide">
<table width="218">
                  <tr>
                    <td>	
<form id="form1" name="form1" method="post">
		<input type="hidden" id="p" value="1" />
			<div>					        
                <input id="f" name="f" type="checkbox" value="1" checked="checked" />
                <?php echo $lang['woman']; ?> 
                           
                <input id="m" name="m" type="checkbox" value="1" checked="checked" />
                <?php echo $lang['man']; ?>
			</div>
	
			<div> entre las edades de:<br />
		
			
                <input id="min_age" name="min_age" type="text" size="2" 
				<?php if (!empty($_REQUEST['min_age'])){ echo "value=".'"'.$_REQUEST['min_age'].'"'; } ?>/>
              <?php echo $lang['and']; ?>
              <input id="max_age" name="max_age" type="text" size="2" 
			  <?php if (!empty($_REQUEST['max_age'])){ echo "value=".'"'.$_REQUEST['max_age'].'"'; } ?>/>
              Anos

								  
			    <div id="track6" style="width:200px;background-color:#aaa;height:5px;position:relative;">
    <div id="handle6-1" style="position:absolute;top:0;left:0;width:5px;height:10px;background-color:#f00;"> </div>
    <div id="handle6-2" style="position:absolute;top:0;left:0;width:5px;height:10px;background-color:#0f0;"> </div>
  				</div>
				<p id="debug6"> </p>	
				
				
							  <div id="track7" style="width:200px;background-color:#aaa;height:5px;position:relative;">
    <div id="handle7-1" style="position:absolute;top:0;left:0;width:5px;height:10px;background-color:#f00;"> </div>
    <div id="handle7-2" style="position:absolute;top:0;left:0;width:5px;height:10px;background-color:#0f0;"> </div>
  </div>
					

		 <p id="debug7"> </p>					

	 	

					</td>
                  </tr>
                </table>			  
			  
			</div>
			<div>Paiz:<br />
                <?php echo $countries_menu; ?>
			</div>
			<div>Ciudad:<br />
                <input id="user_city" name="user_city" type="text"  />
			</div>
			<div>
                <input id="photo_only" type="checkbox" name="photo_only" value="1"/>
              Solo perfiles que tienen fotos</p>
			  <?php echo $lang['results_per_page']; ?><br />
			  <select id="rpp" name="rpp">
			  <option value="20">20</option>
			  <option value="40">40</option>
			  <option value="60">60</option>
			  </select>
			</div>
			<div align="center">
                <input id="search_button" type="button" name="Submit" value="<?php echo $lang['search']; ?>" />
			</div>
            </form> 

			
			         
	</div>
	<script type="text/javascript" language="javascript">
	
		 var slider7 = new Control.Slider(['handle7-1','handle7-2'],'track7',{
        sliderValue:[0.3, 0.8],
        restricted:true,
        onSlide:function(v){$('debug7').innerHTML='slide: '+v.inspect()},
        onChange:function(v){$('debug7').innerHTML='changed! '+v.inspect()}});
	
	var f = document.form1;
	var theValues = new Array();
	for(var i=18;i<=89;i++){
		theValues.push(i);
	}
	var slider6 = new Control.Slider(['handle6-1','handle6-2'],'track6',{
			sliderValue:['18','89'],
			range:$R(18,89),
			values:theValues,
			restricted:true,
			onSlide:function(v){
				$('debug6').innerHTML='slide: '+v.inspect();  
				slide_update(v);
			},
			onChange:function(v){
				$('debug6').innerHTML='changed! '+v.inspect();
				//show_page(1);
			}
		});
		

	
	
	function slide_update(v){
		var temp = new Array();
		var numbers = v.toString();
		temp = numbers.split(',');
		f.min_age.value = temp[0];
		f.max_age.value = temp[1];
	}
	</script>
</body>
</html>
