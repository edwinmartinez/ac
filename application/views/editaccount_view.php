<div class="container">

	<h3><?php echo $title; ?></h3>
	<div>
		<form name="editaccount_form" id="editaccount_form">
		<fieldset id="customer_basic_info">		
		
		<div class="field_row clearfix">
		<?php echo form_label($this->lang->line('common_email').':', 'user_email'); ?>
		<div class='form_field'>
		<?php echo form_input(array(
			'name'=>'user_email',
			'id'=>'user_email',
			'class' => 'input-block-level',
			'placeholder' => $this->lang->line('common_email'),
			'value'=>$user_info->user_email)
		);?>
		</div>
		</div>
	
		<button class="btn btn-large btn-primary" type="submit"><?php echo $this->lang->line('common_submit'); ?></button>
		</fieldset>
		</form>
	</div>
	
</div>