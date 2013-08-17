<div class="container-fluid">
	<div class="row-fluid">
		
		<div class="span3 l-sidebar-left" style="border: 1px solid #ccc;">
			<ul id="sidebarMessagesNav">
				<li><?php echo $this->lang->line('messages_inbox'); ?></li>
				<li><?php echo $this->lang->line('messages_unread'); ?></li>
			</ul>
			
			<div class="row-fluid clear">
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
	  		<div id="messagesRespond">
	  			<?php //if(!empty($messages)) var_dump($messages); ?>respond form
	  		</div>
  		
  		</div>
	  

	</div><!--<div class="row-fluid">-->
</div>

 <script type="text/x-mustache-template" id="messagesSidebar-template">
    		<div id="sidebarMessageList">
			</div>

			<li class="divider"></li>
			<div>something</div>

              
 </script>


 <script type="text/x-mustache-template" id="messagesThread-template">
    		<div id="threadMessages">
    			<ul id="threadMessageList"></ul>
			</div>
 </script>

<script type="text/x-mustache-template" id="messageThreadItem-template">
	<div class="messageThreadItem">
		<div class="ThreadMessageItem-fromImg"><img src="{{from_username_img_url}}" /></div><div class="threadMessageItem-from"><span><a href="#">{{ from_username }}</a></span></div>
		<time class="timeago ThreadMessageItem-time" datetime="{{msg_time}}">{{msg_time}}</time>
		<div class="ThreadMessageItem-text">{{ msg_text }}</div>
	</div>
</script>

<!-- Reply form -->
<script type="text/x-mustache-template" id="messageThreadForm-template">
	<fieldset>
		<div class='control-group'>
          <div class='controls'>
            <textarea class='input-xlarge threadMessageItem-replyField' id="msg_reply_text" name="msg_reply_text"></textarea>
          </div>
        </div>
		        <div class="form-actions">
		          <button type="submit" class="btn btn-primary"><?php echo $this->lang->line('messages_reply'); ?></button>
		        </div>
	</fieldset>
</script>