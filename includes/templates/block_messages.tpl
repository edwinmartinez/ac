{extends file="index.html"}
{block name=content}
<style>
<!--
#message-nav{
	float:left;
	display:inline;
	margin-left:10px;
	margin-top:20px;
	width:140px;
	font-size:12px;
	line-height:24px;
	font-weight:bold;
}
#message-nav ul{
	margin-left: 0;
	padding-left: 0;
}
#message-nav ul li{
	list-style: none;
}

#message-area{
	float:left;
	display:inline;
	margin-left:5px;
	width:560px;
	border:#000 1px solid;
}

#boxTitle{
	font-weight:bold;
}

.message-unit{
	clear:both;
	border-bottom:1px solid #ccc;
	margin-bottom:4px;
}
.fromUserDiv{
	width:130px;
	float:left;
	padding:4px;
	display:inline;
	border-right:1px solid #ccc;
	font-size:10px;
}
.messageSubjet{
	width:300px; 
	overflow:hidden;
	float:left;
	font-size:12px;
	font-weight:bold;
	padding-top:4px;
	border-right:1px solid #ccc;
}
-->
</style>

	<div id="message-nav">Folders
		<ul>
			<li id="inboxLink"><a href="?folder=inbox">{$lang.inbox}</a></li>
			<li id="sentboxLink"><a href="?folder=sentbox">{$lang.sentbox}</a></li>
			<li id="outboxLink"><a href="?folder=outbox">{$lang.outbox}</a></li>
			<li id="saveboxLink"><a href="?folder=savebox">{$lang.savebox}</a></li>
		</ul>
	</div>
	
	<div id="message-area">
	<div id="boxTitle">Inbox</div>

		<div class="message-unit">
		    <div class="fromUserDiv">from_user</div>
		    <div class="messageSubjet">subject</div>
			<div style="clear:both;"></div>
			<table id="messageTable"></table>
		</div>
		
		<!--end of message unit-->
		
        <div style="clear:both;"></div>
    </div>
{/block}