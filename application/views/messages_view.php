<div class="container-fluid">
	<div class="row-fluid">
		
		<div class="span2" style="border: 1px solid #ccc;">
			<ul>
				<li><?php echo $this->lang->line('messages_inbox'); ?></li>
				<li><?php echo $this->lang->line('messages_unread'); ?></li>
			</ul>
			
			
			<div class="row">
			<?php foreach ($messages as $message) {
					echo '<div class="messageUnit" style="margin-bottom: 20px;">';
					
					echo $message['username']."<br>\n";
					echo $message['message']."\n";
					echo '</div>';				  
			  }
			?>
				
			</div>

		</div>
		
  		<div class="span10">
  			<h2><?php echo $this->lang->line('messages_messages'); ?></h2>
  			
  			
  			
  		<div id="main-content">
  			<div id="messagesInit"><?php echo $this->lang->line('messages_there_are_no_selected_conversations'); ?></div>

  		</div>
  		
  		
  		</div>
	  

	</div><!--<div class="row-fluid">-->
</div>