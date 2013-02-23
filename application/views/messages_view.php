<div class="container-fluid">
	<div class="row-fluid">
		
		<div class="span2 l-sidebar-left" style="border: 1px solid #ccc;">
			<ul>
				<li><?php echo $this->lang->line('messages_inbox'); ?></li>
				<li><?php echo $this->lang->line('messages_unread'); ?></li>
			</ul>
			
			<div class="row-fluid">
			<?php  /*foreach ($messages as $message) {
					echo '<div class="messageUnit" style="margin-bottom: 20px;">';
					echo "<div class=\"messageUnit-from\"><a href=\"/profile/".$message['from_username'].'">'.$message['from_username'].'</a></div>'."\n";
					echo '<div class="messageUnit-message">'.$message['msg_text'].'</div>'."\n";
					echo '</div>';	
									
			  }*/	
			?>	
			</div>
		</div>
		
  		<div class="span10">
  			<h2><?php echo $this->lang->line('messages_messages'); ?></h2>
  					
	  		<div id="main-content">
	  			<div id="messagesInit"><?php echo $this->lang->line('messages_there_are_no_selected_conversations'); ?></div>
	
	  		</div>
	  		<div id="#messagesRespond">
	  			form here
	  		</div>
  		
  		</div>
	  

	</div><!--<div class="row-fluid">-->
</div>

<script type="text/x-mustache-template" id="messageUnit-template">
	<div class="messageUnit">
		<div class="messageUnit-from">{{ from_username }}</div>
		<div class="messageUnit-message">{{ msg_text }}</div>
	</div>
</script>