<div class="container">

	<h3><?php echo $title; ?></h3>
	<div>
		<form name="editemail_form" id="editaccount_form">
		<fieldset id="customer_basic_info">		
		
		<div class="row clearfix">
		<?php echo form_label($this->lang->line('common_email').':', 'user_email'); ?>
			<div class='col-lg-4'>
			<?php echo form_input(array(
				'name'=>'user_email',
				'id'=>'user_email',
				'class' => 'form-control',
				'placeholder' => $this->lang->line('common_email'),
				'value'=>$user_info->user_email)
			);?>
			</div>
			<div class="col-lg-3">
				<button class="btn btn-primary" type="submit"><?php echo $this->lang->line('common_save'); ?></button>
			</div>
		</div>
		
		<div class="row">
			<form name="changepassword_form">
			<hr>
			<div><h3><?php echo $this->lang->line('common_password_change'); ?></h3></div>
			<label><?php echo $this->lang->line('common_current_password'); ?></label>
			<input type="text" name="old_password" class="form-control input-large" />
			<label><?php echo $this->lang->line('common_new_password'); ?></label>
			<input type="text" name="new_password" class="form-control input-large" />
			<label><?php echo $this->lang->line('common_retype_password'); ?></label>
			<input type="text" name="new_password_retype" class="form-control input-large" />
			<div>
			<button class="btn btn-primary" type="submit" name="change_password"><?php echo $this->lang->line('common_save'); ?></button>
			</div>
			</form>
		</div>
	
		
		<div class="row clearfix">
			<hr>
			<form name="cancelaccount_form">
			<h3><?php echo $this->lang->line('common_cancel_account'); ?></h3>
			<div>
				<?php echo $this->lang->line('common_cancel_account_instructions'); ?>
			</div>
			<div>
				<label><?php echo $this->lang->line('common_cancel_account_reason'); ?></label>
			<textarea name="cancel_account_reason" id="cancel_account_reason"></textarea>
			</div>
			<div>
				
			<button type="submit" class="btn btn-default" name="cancel_account"><?php echo $this->lang->line('common_cancel_account'); ?></button>
			
			</div>
			</form>
			</div>
		</fieldset>
		</form>
	</div>
	
</div>