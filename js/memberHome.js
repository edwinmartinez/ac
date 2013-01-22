$(document).ready(function(){

	getFriends({"confirmed":"confirmed","tableId":"friendsTable"});
	getFriends({"confirmed":"unconfirmed","tableId":"waitinFriendsTable"});
	getNewMessageCount();
});

function getFriends(options){
	var default_args = {
			'confirmed'	:	"confirmed",
			'tableId'	:	"friendsTable"
		};
		for(var index in default_args) {
			if(typeof options[index] == "undefined") options[index] = default_args[index];
		}
	console.log('options.tableId='+options.tableId);
	$.post("/jsonfriends/",{confirmed:options.confirmed},function(data){
		//alert(data);

		$("#"+options.tableId).html("");
		$.each(data, function(i, friend) {
		  console.log("username="+friend.username);
			var tblRow =
				"<tr>"
				+"<td><a href=\"/perfil/"+friend.username+"\">"+friend.username+"</a></td>"
				+"<td>"+friend.confirmed+"</td>"
				+"</tr>"
			$(tblRow).appendTo("#"+options.tableId);
		});
		
	}, 'json');
}

function getNewMessageCount(){

	//console.log('options.tableId='+options.tableId);
	$.post("/jsonnewmessagecount/",{type:'new'},function(data){
		$("#msgCount").html('('+data+')');
	}, 'text');
}
