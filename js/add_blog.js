// JavaScript Document

function check_and_submit(){
	f = document.form1;

	if(validate()){
			return true;
	}else{
		return false;
	}
}

function validate() {
	var f = document.form1;

	if (f.title.value == '') {
			alert ('Por favor llena el titulo');
			f.title.focus();
			return false;
	}
	if (f.content.value == '') {
			alert ('Por favor llena el contenido de tu entrada de diario');
			f.content.focus();
			return false;
	}

	return true;
}