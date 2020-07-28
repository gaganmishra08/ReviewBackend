<div class="row">
	<div class="col-lg-12">
<?php echo $form->open(); ?>

	<?php echo $form->messages(); ?>


	<div class="form-group">
	   <label for="password">Password</label>
		 <input class="form-control" name="password" id="password" type="password" placeholder="New Password" required data-validation-required-message="Please enter your password.">
		 <p class="help-block text-danger"></p>
	</div>
	<div class="form-group">
		<label for="retype_password">Retype Password</label>
		<input class="form-control" name="retype_password" id="retype_password" type="password" placeholder="Re-Type Password" required data-validation-required-message="Please re-enter your password.">
	</div>
	<input type="submit" id="submit" name="submit" value="Submit" class="btn btn-primary btn-xl text-uppercase" />

	<?php echo $form->close(); ?>
	<div>
<div>
