<?php require_once("../includes/config.php"); ?>
// JavaScript Document
//var myAjax = new Ajax.Request(
//    url,
//    {method: 'post', parameters: data, onComplete: ajax_response}
//);


var url = '<?php echo SCRIPT_BASE_URL.'/remote.php'; ?>';
makeEditable = function(id) {
    var div = $("comment_text_" + id);


    editor = new Ajax.InPlaceEditor(div, url, { externalControl: 'edit_control_'+id, rows:5, cols:30, savingText: '<?php echo LA_SAVING; ?>', okText: '<?php echo LA_SAVE; ?>', cancelText: '<?php echo LA_CANCEL; ?>', 
    callback: function(form, value) { return 'action=edit_profile_comment&comment_id='+id+'&comment=' + escape(value) } });
}


//--------------------------------------------------
function send_preview(data) {
	url = 'http://www.amigocupido.com/remote.php';
	//alert(data);
    var myAjax = new Ajax.Request(
        url,
        {
			method: 'post', 
			parameters: data, 
			onComplete: getPhotoCommentResponse,
			onFailure: failureToPostComment
		}
    );
}

function preview_comment(){
	var f = document.commentsform;
	data = 'action=photo_comment&photo_id='+escape($F('photo_id'))+'&commenter_uid='+escape($F('commenter_uid'))+'&comment='+escape($F('comment'))+'&';
//lets use the id property for the form element
	f.comment.value = "";
	//f.comment.value = data;
	send_preview(data);
}



function getPhotoCommentResponse(originalRequest){
	
	var strIn = originalRequest.responseXML;
	//alert(strIn);
	var status = strIn.getElementsByTagName('status')[0].firstChild.data;
	message = strIn.getElementsByTagName('message')[0].firstChild.data;

	if(status == 1){
		alert(message);	
	}else{
		alert(message);
	}
}

function failureToPostComment(){
	alert('Failure to post Comment');
}


function delete_comment(comment_id){
	var answer = confirm(<?php echo "'".LA_ARE_YOU_SURE_DELETE_COMMENT."'"; ?>)
	if (answer){
		data = 'action=delete_profile_comment&comment_id='+escape(comment_id);
		url = 'http://www.amigocupido.com/remote.php';
		var myAjax = new Ajax.Request(
			url,
			{method: 'post', parameters: data, onComplete: getProfileCommentDeleteResponse}
		);
	}
}

function getProfileCommentDeleteResponse(originalRequest){
	//alert(originalRequest.responseText);
	var strIn = originalRequest.responseXML;
	var status = strIn.getElementsByTagName('status')[0].firstChild.data;
	message = strIn.getElementsByTagName('message')[0].firstChild.data;
	comment_id = strIn.getElementsByTagName('comment_id')[0].firstChild.data;
	//alert(message);
	if(status == 1){
		new Effect.SlideUp('comment_row_'+comment_id);
	}else {
		alert(message);
	}
}


function popup_photo(sPicURL) {
	window.open( "popup.html?"+sPicURL, "",  
	"resizable=1,HEIGHT=200,WIDTH=200");
}


function staf_pop(user){
	var features = "status=yes,scrollbars=yes,resizable=yes,width=350,height=350";
    window.open('<?php echo SEND_TO_A_FRIEND_URL; ?>?u='+user,'_staf',features); 
}