
function checkData() {
	//var f1 = document.forms[1];
	var f1 = document.getElementById ('loginForm');
	var wm = "Parece haber un error! ";
	var noerror = 1;

	// --- entered_login ---
	var t1 = f1.username;
	if (t1.value == "" || t1.value == " ") {
		wm += " *Apodo no es valido";
		noerror = 0;
	}

	// --- entered_password ---
	var t1 = f1.password;
	if (t1.value == "" || t1.value == " ") {
		wm += " *Contrasena no es valida";
		noerror = 0;
	}

	// --- check if errors occurred ---
	if (noerror == 0) {
		alert(wm);
		return false;
	}
	else return true;
}