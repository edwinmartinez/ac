
	<div class="row-fluid">
		<div class="span3">
			<div>
			[picture]<br />
			<?php echo $this->lang->line('common_messages'); ?><br />
			<?php echo $this->lang->line('common_recent_visits'); ?><br />
			<?php echo $this->lang->line('common_my_profile'); ?><br />
			<?php echo $this->lang->line('common_edit_my_profile'); ?><br />
			<?php echo $this->lang->line('common_my_photos'); ?><br />
			<?php echo $this->lang->line('common_my_account'); ?><br />
			<?php echo $this->lang->line('common_my_password'); ?><br />
			<?php echo $this->lang->line('common_logout'); ?><br />
			</div>
			
			<div>
				<?php echo $this->lang->line('common_my_fav'); ?><br />
			</div>
			
			<div>
				<?php echo $this->lang->line('common_search'); ?><br />
			</div>
		</div>
		
  		<div class="span9">
  			<h2>Hola, <?php echo $this->session->userdata('username'); ?>!</h2>
  			[the wall]
  		</div>
	  

	</div><!--<div class="row-fluid">-->