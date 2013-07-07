

<div class="container-fluid">
	<div class="row-fluid">
		
		<div class="span2" style="border: 1px solid #ccc;">
			something here
			<ul>
				
				<li><?php echo $this->lang->line('common_recent_visits'); ?></li>
				<li><?php echo $this->lang->line('common_my_photos'); ?></li>
			</ul>
			
			
			<div class="row">
				<h4><?php echo $this->lang->line('common_new_members'); ?></h4>
				<div class="new-users-container"></div>
			</div>
			
			<div class="row">
				<h4><?php echo $this->lang->line('common_my_fav'); ?></h4>
			</div>
			
			<div class="row">
				<?php echo $this->lang->line('common_search'); ?>
			</div>
		</div>
		
  		<div class="span10" style="background-color: #CCCCCC;">
  			<h3>Hola, <?php echo $this->session->userdata('username'); ?>!</h3>
  			[the wall]
  			
  			
  			
  		<div id="main-content">Loading...</div>
  		
  		
  		</div>
	  

	</div><!--<div class="row-fluid">-->
</div>

<script type="text/x-mustache-template" id="user-list-template">
		
</script>

<script type="text/x-mustache-template" id="user-unit-template">
		<div class="ThreadMessageItem-text">{{username}}</div>
</script>

<script type="text/template" id="users-template">
	<div>
		<% _.each(newUsers, function(user) { %>
          <div class="userListItem">
          	<% console.log(user); %>
            <%= htmlEncode(user.get('username')) %>
          </div>
        <% }); %>
		
	</div>
</script>
 