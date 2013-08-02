

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
  			
  			
  			
  		<div id="feeds-container">Loading...</div>
  		
  		
  		</div>
	  

	</div><!--<div class="row-fluid">-->
</div>

<script type="text/x-mustache-template" id="user-list-template">
		
</script>

<script type="text/x-mustache-template" id="user-unit-template">
<div class="userUnit">
		<div class="userUnit-imgDiv"><img class="userUnit-img" src="{{profile_img}}" /></div>
		<div class="userUnit-info">
			<div class="userUnit-age">{{age}}</div>
			<div class="userUnit-username">{{username}}</div>
		</div>
</div>
</script>

<script type="text/x-mustache-template" id="wall-list-template">
	
</script>

<script type="text/x-mustache-template" id="wall-unit-template">
	<div>{{username}}</div>
	<div>{{statusfeed_content}}</div>
</script>

<!-- status form -->
<script type="text/x-mustache-template" id="statusForm-template">
	<fieldset>
		<div class='control-group'>
          <div class='controls'>
            <input type="text" class='input-xlarge threadMessageItem-replyField' id="status_text" name="status_text" />
          </div>
        </div>
		        <div class="form-actions">
		          <button type="submit" class="btn btn-primary"><?php echo $this->lang->line('common_post'); ?></button>
		        </div>
	</fieldset>
</script>
 