 <div class="container-fluid">


	<h3><?php echo $title; ?></h3>
	
	<div class="row">
		<div class="span12">
			<div class="row">
				<div class="span3" style="background-color: #CCCCCC;">
				</div>
				
				<div class="9">	
					<div class="find-users-container"></div>
				</div>
	
		</div>
	</div>


</div>

<script type="text/x-mustache-template" id="finduser-list-template">

</script>

<script type="text/x-mustache-template" id="finduser-unit-template">

		<div class="finduserUnit-imgDiv"><img class="finduserUnit-img" src="{{profile_img}}" /></div>
		<div class="finduserUnit-info">
			<div class="finduserUnit-username"><a href="/members/{{username}}">{{username}}</a></div>
			<div><a href="#" alt="<?php echo $this->lang->line('common_send_message'); ?>"><i class="icon-envelope"></i></a> <a href="#" alt="<?php echo $this->lang->line('common_add_friend'); ?>"><i class="icon-plus-sign"></i></a></div>
		</div>
		

</script>