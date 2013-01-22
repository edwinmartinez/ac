$(document).ready(function(){

	getMsgs({"type":"new","tableId":"messageTable"});
	$('#boxTitle').html("Inbox");
	$('#inboxLink').click(function(event) {
		$('#boxTitle').html('Inbox');
		event.preventDefault();
		getMsgs({"type":"new","tableId":"messageTable"});
	   });
	$('#outboxLink').click(function(event) {
		$('#boxTitle').html("Sent");
		event.preventDefault();
		getMsgs({"type":"sent","tableId":"messageTable"});
	   });
	$('#sentboxLink').click(function(event) {
		$('#boxTitle').html('Saved out');
		event.preventDefault();
		getMsgs({"type":"saved_out","tableId":"messageTable"});
	   });
	$('#saveboxLink').click(function(event) {
		$('#boxTitle').html('Saved out');
		event.preventDefault();
		getMsgs({"type":"saved_in","tableId":"messageTable"});
	   });
});

function getMsgs(options){
	var default_args = {
			'type'	:	"new",
			'maxChars': 30,
			'tableId'	:	"messageTable"
		};
		for(var index in default_args) {
			if(typeof options[index] == "undefined") options[index] = default_args[index];
		}
	console.log('options.tableId='+options.tableId);
	
	$.post("/jsonmessages/",{type:options.type},function(data){

		$("#"+options.tableId).html("");
		$.each(data, function(i, msg) {
		  console.log("msg_id="+msg.msg_id+"username="+msg.from_username);
		  msg.message = (msg.message != null)?msg.message:'';
			var tblRow =
				"<tr>"
				+"<td id=\""+msg.msg_id+"\"><input type=\"checkbox\" /></td>"
				+"<td><a href=\"/perfil/"+msg.from_username+"\">"+msg.from_username+"</a></td>"
				+"<td>"+msg.subject+"</td>"
				+"<td>"+msg.message+"</td>"
				+"<td>"+msg.date+"</td>"
				+"</tr>"
			$(tblRow).appendTo("#"+options.tableId);
		});
		
	}, 'json');
}