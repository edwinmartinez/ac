
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
		document.getElementById('userMessage').innerHTML = '<p class=error>Ya existe alguine con este apodo en el systema. Por favor, escribe un nuevo apodo</p><br>';
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
			alert ("El nombre no puede contener car�cteres num�ricos");
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
		alert ("La sintaxi del email no es v�lida");
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
	var hoy = new Date();
	fnac_ano = Number(fnac_ano) + 18
	var fnac = new Date( fnac_ano + "/" + fnac_mes + "/" + fnac_dia )
	
	
	if ( fnac > hoy ) {
		alert("Tienes que ser mayor de 18 a&ntilde;os para poder inscribirte");	
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
