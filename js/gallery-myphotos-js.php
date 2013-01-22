<?php 
include_once('../includes/config.php'); 
?>
//Event.observe(window, 'load', init, false); //commented cause it's specified in caller file



var picid = 'nothing';

//not used right now
function init(){
	modifyimage('mainPic', 0);
}

//Preload images ("yes" or "no"):
var preloadimg="yes"

var url = '<?php echo SCRIPT_BASE_URL.'/remote.php'; ?>';

//Set image border width
var imgborderwidth=0


if (preloadimg=="yes"){
	for (x=0; x<dynimages.length; x++){
		var myimage=new Image()
		myimage.src=dynimages[x][0]
	}
}

var title = 'nothing';
function returnimgcode(theimg){
	var imghtml=""
	imghtml+='<img src="'+theimg[1]+'" border="'+imgborderwidth+'">'
	if (theimg[3]!="") //caption
		imghtml+='<div class="caption" id="pic_caption">'+theimg[3]+'</div>'
	return imghtml
}



function titleComplete(t, obj){
	obj.innerHTML	= t.responseText;
	showAsEditable(obj, true);
}

function xmlError(e) {
//there was an error, show the user
alert(e);
} //end function xmlError

function modifyimage(loadarea, imgindex){
//--------------------------------------------------------------
	var imgobj=$(loadarea);
	imgobj.innerHTML=returnimgcode(dynimages[imgindex])
	theimg = dynimages[imgindex];
		
	var success	= function(originalRequest){
		var strIn = originalRequest.responseText;
//		includeJs('tinyxmldom.js');
		var xml;

		xml = originalRequest.responseText;

		//instantiate a new XMLDoc object. Send any errors to the xmlError function
		var objDom = new XMLDoc(xml, xmlError)
	
		//get the root node
		var objDomTree = objDom.docNode;
		var title = objDomTree.getElements("title")[0].getText();
		var caption = objDomTree.getElements("caption")[0].getText();
	
		
//		var tagsNodes = objDomTree.getElements("tags")[0].getElements("tag");
//		var status = objDomTree.getElements("status")[0].getText();


		var theTitle = "";
		var picControls = "";
		var picCaption = "";

		theTitle= '<h2><div onclick="editTitle('+theimg[0]+');" ';
		theTitle+=' onmouseover="showAsEditable(\'pic_title_'+theimg[0]+'\');"';
		theTitle+=' onmouseout="showAsEditable(\'pic_title_'+theimg[0]+'\',true);"';
		theTitle+=' id="pic_title_'+theimg[0]+'">'+title+'</div></h2>';

		picControls='<div class="myPicturesControls">';
		picControls+='<div style="float:left;display:inline;"><img src="/images/icon_cross.png"> ';
		picControls+='<a href="#" onclick="erasePic('+theimg[0]+');return false;"><?php echo LA_ERASE; ?></a></div>';
		picControls+='<div style="float:left;display:inline;margin-left:20px;"><a href="#" onclick="setImgAsIcon('+theimg[0]+');return false;"><?php echo LA_SET_IMG_AS_ICON;?></a></div>';
		picControls+='<div style="clear:both;"></div></div>';
		
		picCaption= '<div class="picCaption"';
		picCaption+=' onclick="editCaption('+theimg[0]+');"';
		picCaption+=' onmouseover="showAsEditable(\'pic_caption_'+theimg[0]+'\');"';
		picCaption+=' onmouseout="showAsEditable(\'pic_caption_'+theimg[0]+'\',true);"';
		picCaption+=' id="pic_caption_'+theimg[0]+'"';
		picCaption+=' >'+caption+'</div>';
				
		imgobj.innerHTML=theTitle+picControls+returnimgcode(dynimages[imgindex])+picCaption;
		
//		//tags area-------------------------------------------------------------------------------
		var tagsNodes = objDomTree.getElements("tags")[0].getElements("tag");
		var tagsobj = $('tags_area');
		var numberOfTags = tagsNodes.length;

		tagsobj.innerHTML = '';
		tagsobj.innerHTML = '<?php
		
		 //echo 'u:'.$_SESSION['user_id'].'<br>'; 
		 ?>';
		for(var i=0;i<tagsNodes.length;i++){
			var tagText = tagsNodes[i].getText();
			tagid = tagsNodes[i].getAttribute("tagid");
			tagsobj.innerHTML+= '<a href="/fotos/tag/'+escape(tagText)+'"><img src="/images/icon_camera_gray.gif" border="0"></a> ';
			tagsobj.innerHTML+= tagText;
			tagsobj.innerHTML+= ' <a href="#" onclick="remove_tag('+tagid+','+theimg[0]+'); return false;">(x)</a><br>';
		}
		picid = theimg[0];

//			//let's return to a link for adding tags
//			Element.hide($('tag_field_editor'));
//			Element.show($('add_tag_link'));

	}
	
	
	var failure	= function(t){imgobj.innerHTML=returnimgcode(dynimages[imgindex]);}
	
	var pars = 'photo_id='+theimg[0]+'&action=get_pic_info';
	var myAjax = new Ajax.Request(url, {method:'post', postBody:pars, onSuccess:success, onFailure:failure});
	
	return false
};

function add_pic_tag(){
	
	var failure	= function(t){alert("could not add tag")};
	var success = function(t){$('tags_area').innerHTML =  t.responseText; }
	
	var pars = 'photo_id='+picid+'&action=add_pic_tag'+'&tags='+$F('tag_field_edit');
	//$('tag_help').innerHTML =  '-'+pars;
	$('tag_field_edit').value = "";
	var myAjax = new Ajax.Request(url, {method:'post', postBody:pars, onSuccess:reload_tags, onFailure:failure});
}

function reload_tags(t){
	//alert(t.responseText);
	var tagsobj = $('tags_area');
	
	var success = function(originalRequest){
//		includeJs('tinyxmldom.js');
		var xml;
		xml = originalRequest.responseText;
		//instantiate a new XMLDoc object. Send any errors to the xmlError function
		var objDom = new XMLDoc(xml, xmlError)
		//get the root node
		var objDomTree = objDom.docNode;
		
		var tagsNodes = objDomTree.getElements("tags")[0].getElements("tag");
		var tagsobj = $('tags_area');
		var numberOfTags = tagsNodes.length;

		tagsobj.innerHTML = '';
		for(var i=0;i<tagsNodes.length;i++){
			var tagText = tagsNodes[i].getText();
			tagid = tagsNodes[i].getAttribute("tagid");;
			tagsobj.innerHTML+= '<a href="/fotos/tag/'+escape(tagText)+'"><img src="/images/icon_camera_gray.gif" border="0"></a> ';
			tagsobj.innerHTML+= tagText;
			tagsobj.innerHTML+= ' <a href="#" onclick="remove_tag('+tagid+','+theimg[0]+'); return false;">(x)</a><br>';
		}

	}
 	
	var pars = 'photo_id='+t.responseText+'&action=get_pic_info';
	var myAjax = new Ajax.Request(url, {method:'post', postBody:pars, onSuccess:success});
}



function showTagField(){
	obj = $('add_tag_link');
	Element.hide(obj);
	
		var textarea='<div id="tag_field_editor"><input type="text" id="tag_field_edit" name="tag_field"';
		textarea+=' value="">';

	var button	 = '<div><input id="add_tag_button" type="button" value="<?php echo LA_ADD; ?>" /></div></div>';
	
	new Insertion.After(obj, textarea+button);	
	
	Event.observe('add_tag_button', 'click', function(){add_pic_tag()}, false);
	
}


function remove_tag(tagid,photoid){
	//alert('tag:'+tagid+' img:'+photoid);
	var failure	= function(t){alert("could not delete tag")};
		
	var pars = 'photo_id='+photoid+'&tag_id='+tagid+'&action=delete_pic_tag';
	var myAjax = new Ajax.Request(url, {method:'post', postBody:pars, onSuccess:reload_tags, onFailure:failure});
}

//----------------------------end of gallery ------------------------------------------



//-----------------------------------------------------------------------------------
//Event.observe(window, 'load', init, false);
//


function erasePic(id){
	var answer = confirm('<?php echo LA_YOU_SURE_WANT_TO_ERASE_PHOTO; ?>')
	
	var success = function(t){
		//alert (t.responseText);
		window.location = "/micuenta/?p=fo";
	}
	var failure = function(){alert('Failure');}
	
	if (answer){
		var pars = 'photo_id='+id+'&action=delete_pic';
	var myAjax = new Ajax.Request(url, {method:'post', postBody:pars, onSuccess:success, onFailure:failure});
		
	}
	
}
function setImgAsIcon(id){
	
	var success = function(t){
		//alert (t.responseText);
		window.location = "/micuenta/?p=fo";
	}
	var failure = function(){alert('Failure');}
	
	
	var pars = 'photo_id='+id+'&action=icon_pic';
	var myAjax = new Ajax.Request(url, {method:'post', postBody:pars, onSuccess:success, onFailure:failure});
		
	
	
}
function makeEditableTitle(id){
	id = 'pic_title_'+id;
	//Event.observe(id, 'click', function(){edit(id,'text')}, false);
	Event.observe(id, 'mouseover', function(){showAsEditable(id)}, false);
	Event.observe(id, 'mouseout', function(){showAsEditable(id, true)}, false);
}

function editTitle(pid){

	obj = $('pic_title_'+pid);
	Element.hide(obj);
	var textarea = '<div id="'+obj.id+'_editor"><input type="text" id="'+obj.id+'_edit" size="40" name="'+obj.id+'" value="'+obj.innerHTML+'" />';

	var button	 = '<div><input id="'+obj.id+'_save" type="button" value="<?php echo LA_SAVE; ?>" maxlength="254" /> <?php echo LA_OR; ?> <input id="'+obj.id+'_cancel" type="button" value="<?php echo LA_CANCEL; ?>" /></div></div>';
	
	new Insertion.After(obj, textarea+button);	
	
	Event.observe(obj.id+'_save', 'click', function(){saveChanges(obj,pid,'update_pic_title')}, false);
	Event.observe(obj.id+'_cancel', 'click', function(){cleanUp(obj)}, false);
	
}

function editCaption(pid){
		
	obj = $('pic_caption_'+pid);
	Element.hide(obj);
	
		var textarea='<div id="'+obj.id+'_editor"><textarea id="'+obj.id+'_edit" name="'+obj.id+'"';
		textarea+=' class="myPicturesCaptionTextarea" rows="5" cols="30">'+obj.innerHTML+'</textarea>';

	var button	 = '<div id="'+obj.id+'_buttons" ><input id="'+obj.id+'_save" type="button" value="<?php echo LA_SAVE; ?>" /> <?php echo LA_OR; ?> <input id="'+obj.id+'_cancel" type="button" value="<?php echo LA_CANCEL; ?>" /></div></div>';
	
	new Insertion.After(obj, textarea+button);	
	
	Event.observe(obj.id+'_save', 'click', function(){saveChanges(obj,pid,'update_pic_caption')}, false);
	Event.observe(obj.id+'_cancel', 'click', function(){cleanUp(obj)}, false);
	
}


function showAsEditable(id, clear){
	obj = $(id); 
	if (!clear){
		Element.addClassName(obj, 'editable');
	}else{
		Element.removeClassName(obj, 'editable');
	}
}

function saveChanges(obj,pid,action){
	
	var new_content	=  escape($F(obj.id+'_edit'));

	obj.innerHTML	= "Saving...";
	cleanUp(obj, true);

	var success	= function(t){editComplete(t, obj);}
	var failure	= function(t){editFailed(t, obj);}

  	//var url = 'edit.php';
	var pars = 'picture_id='+pid+'&action='+action+'&content='+new_content;
	//alert (url);
	var myAjax = new Ajax.Request(url, {method:'post', postBody:pars, onSuccess:success, onFailure:failure});

}

function cleanUp(obj, keepEditable){
	Element.remove(obj.id+'_editor');
	Element.show(obj);
	if (!keepEditable) showAsEditable(obj, true);
}

function editComplete(t, obj){
	obj.innerHTML	= decode(t.responseText);
	showAsEditable(obj, true);
}

function editFailed(t, obj){
	obj.innerHTML	= 'Sorry, the update failed.';
	cleanUp(obj);
}

function decode(str) {
     var result = "";

     for (var i = 0; i < str.length; i++) {
          if (str.charAt(i) == "+") result += " ";
		  else if (str.charAt(i) == "\\") { }
          else result += str.charAt(i);
     }

	  return unescape(result);
}

function includeJs(scriptName) {
	var html = document.getElementsByTagName('head').item(0) || document.body;
	var js = document.createElement('script');
	js.setAttribute('language', 'javascript');
	js.setAttribute('type', 'text/javascript');
	js.setAttribute('src', scriptName);
	if (!html.appendChild(js)) {
		document.write('<script src="' + scriptName + '" type="text/javascript"></script>');
	}
}
