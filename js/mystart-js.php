<?php require_once("../includes/config.php"); ?>
var url = '<?php echo SCRIPT_BASE_URL.'/remote.php'; ?>';

function deleteFav(ufp_id,fav_uid){
	var success = function(t){
		Element.hide($(ufp_id+'_fav'));
		//alert(t.responseText);

	}
	var failure	= function(t){alert (t.responseText);}
	var answer = confirm ("<?php echo LA_YOU_SURE_YOU_WANT_TO_REMOVE_FAVORITE; ?>")
	if (answer){
		var pars = 'action=delete_fav&ufp_id='+ufp_id+'&fav_uid='+fav_uid;
		var myAjax = new Ajax.Request(url, {method:'post', postBody:pars, onSuccess:success, onFailure:failure});
	}/*else{
		alert ("thanks.")
	}*/
	return false;
}

function approveBuddy(buddyid,approvalcode){
	var success = function(t){
		Element.hide($(buddyid+'_waiting'));
		//alert(t.responseText);

	}
	var failure	= function(t){alert (t.responseText);}
	var pars = 'action=approve_buddy&buddy_id='+buddyid+'&approval_code='+approvalcode;
	var myAjax = new Ajax.Request(url, {method:'post', postBody:pars, onSuccess:success, onFailure:failure});
	return false
}

function denyBuddy(buddyid,approvalcode){
	var success = function(t){
		Element.hide($(buddyid+'_waiting'));
		//alert(t.responseText);
	}
	
	var failure	= function(t){alert (t.responseText);}
	var pars = 'action=deny_buddy&buddy_id='+buddyid+'&r='+approvalcode;
	var myAjax = new Ajax.Request(url, {method:'post', postBody:pars, onSuccess:success, onFailure:failure});
	
	return false
}

function deleteBuddy(buddyid){
	var success = function(t){
		Element.hide($(buddyid+'_buddy'));
		alert(t.responseText);
	}
	
	var failure	= function(t){alert (t.responseText);}
	var answer = confirm ("<?php echo LA_YOU_SURE_YOU_WANT_TO_DELETE_BUDDY; ?>")
	if (answer){
	    var pars = 'action=delete_buddy&buddy_id='+buddyid;
	    var myAjax = new Ajax.Request(url, {method:'post', postBody:pars, onSuccess:success, onFailure:failure});
	}
	return false
}

function ajax_request(data) {
    var myAjax = new Ajax.Request(
        url,
        {method: 'post', parameters: data, onComplete: ajax_response}
    );
}

function ajax_response(originalRequest) {
	alert(originalRequest.responseText);
}