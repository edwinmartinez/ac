

<div class="container-fluid">
	<div class="row-fluid">
		
		<div class="span2" style="border: 1px solid #ccc;">
			something here
			<ul>
				<div>[picture]</div>
				
				<li><?php echo $this->lang->line('common_recent_visits'); ?></li>
				<li><?php echo $this->lang->line('common_my_photos'); ?></li>
			</ul>
			
			
			<div class="row">
				<h3><?php echo $this->lang->line('common_new_members'); ?></h3>
			</div>
			
			<div class="row">
				<h3><?php echo $this->lang->line('common_my_fav'); ?></h3>
			</div>
			
			<div class="row">
				<?php echo $this->lang->line('common_search'); ?>
			</div>
		</div>
		
  		<div class="span10" style="background-color: #CCCCCC;">
  			<h2>Hola, <?php echo $this->session->userdata('username'); ?>!</h2>
  			[the wall]
  		</div>
	  

	</div><!--<div class="row-fluid">-->
</div>