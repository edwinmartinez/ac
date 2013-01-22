<?php require_once("../includes/config.php"); ?>
// JavaScript Document
//var myAjax = new Ajax.Request(
//    url,
//    {method: 'post', parameters: data, onComplete: ajax_response}
//);


var url = '<?php echo SCRIPT_BASE_URL.'/remote.php'; ?>';
makeEditable = function(id) {
    var div = $("comment_text_" + id);

//    pe = new PeriodicalExecuter(function() { updateTime(id); }, 1);

//    Element.addClassName($('comment_' + id), 'editable');
//    new Insertion.Bottom(div, '<div class="edit_control" id="edit_control_'+id+'">Edit Comment (<span id="time_'+id+'">'+time+' seconds</span>)</div>');

    editor = new Ajax.InPlaceEditor(div, url, { externalControl: 'edit_control_'+id, rows:5, cols:30, savingText: '<?php echo LA_SAVING; ?>', okText: '<?php echo LA_SAVE; ?>', cancelText: '<?php echo LA_CANCEL; ?>', 
    callback: function(form, value) { return 'action=edit_profile_comment&comment_id='+id+'&comment=' + escape(value) } });
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

function add_to_buddies(user_id,buddy_id){
	if(user_id != buddy_id){
		ajax_request('user_id='+user_id+'&buddy_id='+buddy_id+'&action=addbuddyrequest');
	}
}
function addfav(user_id,fav_id){
	if(user_id != fav_id){
		ajax_request('user_id='+user_id+'&fav_id='+fav_id+'&action=addfav');
	}
}
//--------------------------------------------------
function send_preview(data) {
	url = 'http://www.amigocupido.com/remote.php';
    var myAjax = new Ajax.Request(
        url,
        {method: 'post', parameters: data, onComplete: getProfileCommentResponse}
    );
}

function preview_comment(){
	var f = document.commentsform	
	data = 'action=profile_comment&user_id='+escape($F('profile_uid'))+'&commenter_uid='+escape($F('commenter_uid'))+'&comment='+escape($F('comment'));
	//alert(data);
	f.comment.value = "";
	send_preview(data);
}

function getProfileCommentResponse(originalRequest){
	//alert(originalRequest.responseText);
	var strIn = originalRequest.responseXML;
	var status = strIn.getElementsByTagName('status')[0].firstChild.data;
	message = strIn.getElementsByTagName('message')[0].firstChild.data;
	comment_id = strIn.getElementsByTagName('comment_id')[0].firstChild.data;
	comment = strIn.getElementsByTagName('comment')[0].firstChild.data;
	datetime = strIn.getElementsByTagName('datetime')[0].firstChild.data;
	commenter = strIn.getElementsByTagName('commenter')[0].firstChild.data;
	var commentsDiv = $('comments');
	if(status == 1) {
		var deleteLink ='<a href="#" onClick="delete_comment('+comment_id+');return false;">';
		deleteLink += '<img src="/images/icon_cross.png" border="0" style="margin-left:5px;" /></a> ';
		deleteLink += '<a href="#" onClick="delete_comment('+comment_id+');return false;"><?php echo LA_ERASE; ?></a>';
		
		var commentContent = '<div class="profileCommentLeft">'+commenter+'</div>';
		commentContent += '<div class="profileCommentRight">'
		commentContent += '<a href="#" id="edit_control_'+comment_id+'" ><img src="/images/icon_edit.png" border="0" style="margin-left:5px;" /><?php echo LA_EDIT; ?></a> '+deleteLink+'<br />';
		commentContent += '<span id="comment_text_'+comment_id+'">'+comment+'</span></div>';		
		commentsDiv.innerHTML += '<div id="comment_row_'+comment_id+'" class="commentRow" style="display:none;">'+commentContent+'</div>';
		new Effect.SlideDown('comment_row_'+comment_id);
		
		var div = $("comment_text_" + comment_id);
		 new Ajax.InPlaceEditor(div, url, { externalControl: 'edit_control_'+comment_id, rows:5, cols:30, savingText: '<?php echo LA_SAVING; ?>', okText: '<?php echo LA_SAVE; ?>', cancelText: '<?php echo LA_CANCEL; ?>', 
    callback: function(form, value) { return 'action=edit_profile_comment&comment_id='+comment_id+'&comment=' + escape(value) } });
		
		
	}else{
		alert(message);
	}
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
	window.open( "/perfil/popup.html?"+sPicURL, "",  
	"resizable=1,HEIGHT=200,WIDTH=200");
}


function staf_pop(user){
	var features = "status=yes,scrollbars=yes,resizable=yes,width=350,height=350";
    window.open('<?php echo SEND_TO_A_FRIEND_URL; ?>?u='+user,'_staf',features); 
}