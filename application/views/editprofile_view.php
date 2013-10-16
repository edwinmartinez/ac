 <div class="container-fluid">


	<h3><?php echo $title; ?></h3>

	<div class="row">

		<div class="span3">
			<div class="well sidebar-nav">
				<ul class="nav nav-list">
					<li><?php echo $this->lang->line('common_email'); ?></li>
					<li><?php echo $this->lang->line('common_change_password'); ?></li>
					<li><?php echo $this->lang->line('common_cancel_account'); ?></li>
				</ul>
			</div>
		</div>

		<div class="span9">
			<div class="row-fluid">
				<form action="/user/editprofile_submit" name="user-email-form" id="user-email-form">
				<?php echo form_label($this->lang->line('common_email').':', 'user_email'); ?>
				<div class='col-lg-4'>
				<?php echo form_input(
					array(
					'name'=>'user_email',
					'id'=>'user_email',
					'class' => 'form-control',
					'placeholder' => $this->lang->line('common_email'),
					'value'=>$user_info->user_email
					)
				);?>
				</div>

			<div class="row-fluid">
			<label><?php echo $this->lang->line('users_dob'); ?></label>

				<div>
					<select name="birth_day" id="birth_day">
						<option value="0"><?php echo $this->lang->line('users_day'); ?></option>
						<?php
							$dob_days = array('0' => $this->lang->line('users_day'));
							for ($i=1; $i <= 31; $i++) {
							$daynum = $i;
							if($daynum<10) $daynum = "0".$daynum;
							if($daynum == $birth_date)
								echo '<option value="'.$daynum.'" '.set_select('birth_day', $birth_date,TRUE).'>'.$i.'</option>'."\n";
							else
								echo '<option value="'.$daynum.'" '.set_select('birth_day', $birth_date).'>'.$i.'</option>'."\n";
							$dob_days[$daynum] = $daynum;


						}


						//echo form_dropdown('birth_day', $dob_days, $birth_date,'id="birth_day"');
						?>
					</select>

					<select name="birth_month" id="birth_month">
						<option value="0" <?php echo set_select('birth_month', ''); ?>><?php echo $this->lang->line('users_month'); ?></option>
						<option value="01" <?php echo set_select('birth_month', '01',($birth_month == '01')?TRUE:FALSE); ?>><?php echo $this->lang->line('users_jan'); ?></option>
						<option value="02" <?php echo set_select('birth_month', '02',($birth_month == '02')?TRUE:FALSE); ?>><?php echo $this->lang->line('users_feb'); ?></option>
						<option value="03" <?php echo set_select('birth_month', '03',($birth_month == '03')?TRUE:FALSE); ?>><?php echo $this->lang->line('users_mar'); ?></option>
						<option value="04" <?php echo set_select('birth_month', '04',($birth_month == '04')?TRUE:FALSE); ?>><?php echo $this->lang->line('users_apr'); ?></option>
						<option value="05" <?php echo set_select('birth_month', '05',($birth_month == '05')?TRUE:FALSE); ?>><?php echo $this->lang->line('users_may'); ?></option>
						<option value="06" <?php echo set_select('birth_month', '06',($birth_month == '06')?TRUE:FALSE); ?>><?php echo $this->lang->line('users_jun'); ?></option>
						<option value="07" <?php echo set_select('birth_month', '07',($birth_month == '07')?TRUE:FALSE); ?>><?php echo $this->lang->line('users_jul'); ?></option>
						<option value="08" <?php echo set_select('birth_month', '08',($birth_month == '08')?TRUE:FALSE); ?>><?php echo $this->lang->line('users_aug'); ?></option>
						<option value="09" <?php echo set_select('birth_month', '09',($birth_month == '09')?TRUE:FALSE); ?>><?php echo $this->lang->line('users_sep'); ?></option>
						<option value="10" <?php echo set_select('birth_month', '10',($birth_month == '10')?TRUE:FALSE); ?>><?php echo $this->lang->line('users_oct'); ?></option>
						<option value="11" <?php echo set_select('birth_month', '11',($birth_month == '11')?TRUE:FALSE); ?>><?php echo $this->lang->line('users_nov'); ?></option>
						<option value="12" <?php echo set_select('birth_month', '12',($birth_month == '12')?TRUE:FALSE); ?>><?php echo $this->lang->line('users_dec'); ?></option>
					</select>

					<select name="birth_year" id="birth_year">
						<option value="0"><?php echo $this->lang->line('users_year'); ?></option>
						<?php for ($i=2013; $i > 1900; $i--) {
							$did = $i;
							//if($did<10) $did = "0".$did;
							echo '<option value="'.$i.'" '. set_select('birth_year', $i,($i == $birth_year)?TRUE:FALSE).' >'.$i.'</option>'."\n";
						}?>
					</select>
					<label for="birth_day" class="error" style="display:none;"></label>
					<label for="birth_month" class="error" style="display:none;"></label>
					<label for="birth_year" class="error" style="display:none;"></label>
				</div>
			</div>
			
			<div class="row-fluid">
				<?php echo form_label($this->lang->line('common_select_your_country').':', 'country'); ?>
				<div class='col-lg-4'>
				<?php 
				
				$country_menu = array();
				foreach ($countries as $country) {
					$country_menu[$country['countries_id']] = $country['countries_name_es'];
						//echo '<option value="'.$country['countries_id'].'" '. set_select('country', $country['countries_id']).'>'.$country['countries_name_es'].'</option>'."\n";
				}
				echo form_dropdown('country',$country_menu,$user_info->countries_id,'id="country"');
				?>
				</div>
			</div>
			
			<div class="row-fluid">
				<?php echo form_label($this->lang->line('common_state').':', 'state'); ?>
				<div class='col-lg-4'>
				<?php 
				$states = array('#'=> $this->lang->line('common_select_your_state'))+$states;
				//array_unshift($states,array('#'=> $this->lang->line('common_select_your_state')));
				
				echo form_dropdown('state',$states,$user_info->user_state_id,'id="state"');
				?>
				</div>
			</div>

				<div class="col-lg-3">
					<button class="btn btn-primary" type="submit" name="user-email-submit" id="user-email-submit"><?php echo $this->lang->line('common_save'); ?></button>
				</div>
				</form>
			</div>
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