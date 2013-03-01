<div class="container-fluid">
	<div class="row-fluid">
		
		<div class="span3 l-sidebar-left" style="border: 1px solid #ccc;">
			<ul>
				<li><?php echo $this->lang->line('messages_inbox'); ?></li>
				<li><?php echo $this->lang->line('messages_unread'); ?></li>
			</ul>
			
			<div class="row-fluid">
				<div id="messageList">
					message list
				</div>
			</div>
		</div>
		
  		<div class="span6">
  			<div><h2><?php echo $thread_username; ?></h2></div>
  					
	  		<div id="main-content">
	  			
				
					<div id="messagesInit"><?php echo $this->lang->line('messages_there_are_no_selected_conversations'); ?></div>
				
	  		</div>
	  		<div id="#messagesRespond">
	  			<?php if(!empty($messages)) var_dump($messages); ?>
	  		</div>
  		
  		</div>
	  

	</div><!--<div class="row-fluid">-->
</div>

 <script type="text/x-mustache-template" id="messagesSidebar-template">
    		<div id="sidebarMessageList">
				<a href="<?php echo base_url().'messages/'; ?>"><?php echo $this->lang->line('messages_messages'); ?></a> 
			</div>

			<li class="divider"></li>
			<div>something</div>

              
 </script>

<script type="text/x-mustache-template" id="messageUnit-template">
	<div class="messageUnit">
		<div class="messageUnit-from">{{ from_username }}</div>
		<div class="messageUnit-message">{{ msg_text }}</div>
	</div>
</script>

 <script type="text/x-mustache-template" id="messagesThread-template">
    		<div>
    			<ul id="threadMessageList"></ul>
			</div>

			<div>reply form</div>

 </script>

<script type="text/x-mustache-template" id="messageThreadItem-template">
	<div class="messageThreadItem">
		<div class="ThreadMessageItem-fromImg"><img src="{{from_username_img_url}}" /></div><div class="threadMessageItem-from"><span><a href="#">{{ from_username }}</a></span></div>
		<div class="ThreadMessageItem-text">{{ msg_text }}</div>
	</div>
</script>

<script type="text/x-mustache-template" id="messageThreadReplyForm-template">
	<form>
		<textarea name="replyText"></textarea>
	</form>
</script>
