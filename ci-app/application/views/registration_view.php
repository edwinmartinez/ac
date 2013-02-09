<div id="content">
	<div class="signup_wrap">
		<div class="signin_form">
		 <?php echo form_open("user/login"); ?>
		  <label for="email">Email:</label>
		  <input type="text" id="email" name="email" value="" />
		  <label for="password">Password:</label>
		  <input type="password" id="pass" name="pass" value="" />
		  <input type="submit" class="" value="Sign in" />
		 <?php echo form_close(); ?>
		</div><!--<div class="signin_form">-->
	</div><!--<div class="signup_wrap">-->
		
	<div class="reg_form">
		
		<div class="form_title"><?php echo $this->lang->line('common_signup'); ?></div>
		 <?php echo validation_errors('<p class="text-error">'); ?>
		 <?php echo form_open("user/registration"); ?>
		
		<div>
			
		<div>
			<label for="country"><?php echo $this->lang->line('users_country_of_residence'); ?></label>
			<select id="country" name="country">
				<option value=""><?php echo $this->lang->line('users_select_your_country'); ?></option>
				<?php foreach ($countries as $country) {
					echo '<option value="'.$country['countries_id'].'">'.$country['countries_name_es'].'</option>'."\n";
				}?>
			</select>
		</div>
		
		<div>
		<label for="gender"><?php echo $this->lang->line('users_select_your_gender'); ?></label>
		<select name="gender">
			<option value=""><?php echo $this->lang->line('users_select_your_gender_placeholder'); ?></option>
			<option value="2"><?php echo $this->lang->line('users_female'); ?></option>
			<option value="1"><?php echo $this->lang->line('users_male'); ?></option>
		</select>
		</div>
		
		<div>
		<label><?php echo $this->lang->line('users_dob'); ?></label>
		<select name="birth_day">
			<option value=""><?php echo $this->lang->line('users_day'); ?></option>
			<?php for ($i=1; $i <= 31; $i++) {
				$did = $i;
				if($did<10) $did = "0".$did; 
				echo '<option valu="'.$did.'">'.$i.'</option>'."\n";
			}?>
		</select>
		<select name="birth_month">
			<option value=""><?php echo $this->lang->line('users_month'); ?></option>
			<option value="01"><?php echo $this->lang->line('users_jan'); ?></option>
			<option value="02"><?php echo $this->lang->line('users_feb'); ?></option>
			<option value="03"><?php echo $this->lang->line('users_mar'); ?></option>
			<option value="04"><?php echo $this->lang->line('users_apr'); ?></option>
			<option value="05"><?php echo $this->lang->line('users_may'); ?></option>
			<option value="06"><?php echo $this->lang->line('users_jun'); ?></option>
			<option value="07"><?php echo $this->lang->line('users_jul'); ?></option>
			<option value="08"><?php echo $this->lang->line('users_aug'); ?></option>
			<option value="09"><?php echo $this->lang->line('users_sep'); ?></option>
			<option value="10"><?php echo $this->lang->line('users_oct'); ?></option>
			<option value="11"><?php echo $this->lang->line('users_nov'); ?></option>
			<option value="12"><?php echo $this->lang->line('users_dec'); ?></option>
		</select>
		<select name="birth_year">
			<option value=""><?php echo $this->lang->line('users_year'); ?></option>
		</select>
		</div>
		  <p>
		  <label for="username"><?php echo $this->lang->line('users_username'); ?></label>
		  <input type="text" id="username" name="username" value="<?php echo set_value('username'); ?>" />
		  </p>
		  <p>
		  <label for="email_address"><?php echo $this->lang->line('users_email'); ?>:</label>
		  <input type="text" id="email_address" name="email_address" value="<?php echo set_value('email_address'); ?>" />
		  </p>
		  <p>
		  <label for="password"><?php echo $this->lang->line('users_password'); ?>:</label>
		  <input type="password" id="password" name="password" value="<?php echo set_value('password'); ?>" />
		  </p>
		  <p>
		  <label for="confirm_password"><?php echo $this->lang->line('users_confirm_password'); ?>:</label>
		  <input type="password" id="confirm_password" name="confirm_password" value="<?php echo set_value('confirm_password'); ?>" />
		  </p>
		  <p>
		  <input type="submit" class="greenButton" value="Submit" />
		  </p>
	 <?php echo form_close(); ?>
	</div><!--<div class="reg_form">-->
</div><!--<div id="content">-->