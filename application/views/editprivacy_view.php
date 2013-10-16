 <div class="container-fluid">


	<h3><?php echo $title; ?></h3>


	<div class="span3">

		<div class="well sidebar-nav">
            <ul class="nav nav-list">
				<li><?php echo $this->lang->line('common_email'); ?></li>
				<li><?php echo $this->lang->line('common_change_password'); ?></li>
				<li><?php echo $this->lang->line('common_cancel_account'); ?></li>
			</ul>
		</div>


	</div>


	<div id="myModal" class="modal hide fade">
	  <div class="modal-header">
	    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	    <h3><?php echo $this->lang->line('common_cancel_account_success'); ?></h3>
	  </div>
	  <div class="modal-body">
	    <p><?php echo $this->lang->line('common_cancel_account_goodbye'); ?></p>
	  </div>
	  <div class="modal-footer">
	  	<button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo $this->lang->line('common_close'); ?></button>
	    <!--<a href="#" class="btn btn-primary">Save changes</a>-->
	  </div>
	</div>
</div>