var url = 'remote_edit.php'; // The file on the server, which saves the edited text 
//var idName1 = 'desc'; // This ID should be same as $idName1 in example.php
//var idName2 = 'desc2'; // This ID should be same as $idName2 in example.php

//Event.observe(window, 'load', init, false);


//function init(){
//	makeEditable('username','textarea');
//	makeEditable('user_email','text');
//}

//function init(){
//	makeEditable(idName1);
//	makeEditable(idName2);
//}

function makeEditable(id,fieldtype){
	Event.observe(id, 'click', function(){edit($(id),fieldtype)}, false);
	Event.observe(id, 'mouseover', function(){showAsEditable($(id))}, false);
	Event.observe(id, 'mouseout', function(){showAsEditable($(id), true)}, false);
}

function edit(obj,fieldtype){
	Element.hide(obj);
	
	if(fieldtype == 'textarea') {
		var textarea = '<div id="'+obj.id+'_editor"><textarea id="'+obj.id+'_edit" name="'+obj.id+'" rows="4" cols="50">'+trim(obj.innerHTML)+'</textarea>';
	}else if (fieldtype == 'text') {
		var textarea = '<div id="'+obj.id+'_editor"><input type="text" id="'+obj.id+'_edit" name="'+obj.id+'" size="50" value="'+trim(obj.innerHTML)+'" />';
	}
	var button	 = '<div style="align:center;"><input id="'+obj.id+'_save" type="button" value="GUARDAR" /> O <input id="'+obj.id+'_cancel" type="button" value="CANCELAR" /></div></div>';
	
	new Insertion.After(obj, textarea+button);	
		
	Event.observe(obj.id+'_save', 'click', function(){saveChanges(obj)}, false);
	Event.observe(obj.id+'_cancel', 'click', function(){cleanUp(obj)}, false);
	
}

function showAsEditable(obj, clear){
	if (!clear){
		Element.addClassName(obj, 'editable');
	}else{
		Element.removeClassName(obj, 'editable');
	}
}

function saveChanges(obj){
	
	var new_content	=  escape($F(obj.id+'_edit'));
	var uid = escape($F('user_id'));
	
	obj.innerHTML	= "Saving...";
	cleanUp(obj, true);

	var success	= function(t){editComplete(t, obj);}
	var failure	= function(t){editFailed(t, obj);}


	var pars = 'id='+obj.id+'&uid='+uid+'&content='+new_content;
	//$('user_email_status').innerHTML = pars;
	var myAjax = new Ajax.Request(url, {method:'post', postBody:pars, onSuccess:success, onFailure:failure});

}

function cleanUp(obj, keepEditable){
	Element.remove(obj.id+'_editor');
	Element.show(obj);
	if (!keepEditable) showAsEditable(obj, true);
}

function editComplete(t, obj){
	obj.innerHTML	= t.responseText;
	showAsEditable(obj, true);
	
}

function editFailed(t, obj){
	obj.innerHTML	= 'Sorry, the update failed.';
	cleanUp(obj);
}


function trim(inputString) {

   if (typeof inputString != "string") { return inputString; }
   var retValue = inputString;
   var ch = retValue.substring(0, 1);
   
   while (ch == " ") {
      retValue = retValue.substring(1, retValue.length);
      ch = retValue.substring(0, 1);
   }
   ch = retValue.substring(retValue.length-1, retValue.length);
   
   while (ch == " ") {
      retValue = retValue.substring(0, retValue.length-1);
      ch = retValue.substring(retValue.length-1, retValue.length);
   }
   
   while (retValue.indexOf("  ") != -1) {
      retValue = retValue.substring(0, retValue.indexOf("  ")) + retValue.substring(retValue.indexOf("  ")+1, retValue.length); 
   }
   return retValue;
}