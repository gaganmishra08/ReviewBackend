<?php echo $form->messages(); ?>

<div class="row">

	<div class="col-md-6">
		<div class="box box-primary">
			<div class="box-header">
				<h3 class="box-title">App Settings</h3>
			</div>
			<div class="box-body">
				<?php echo form_open_multipart('admin/settings/termsprivacy'); ?>

					<?php echo $form->bs3_textarea('Terms', 'croot_value', $settings->terms); ?>

          <?php echo $form->bs3_textarea('Privacy', 'user_vu_size_limit', $settings->privacy); ?>

					<?php echo $form->bs3_submit(); ?>
				<?php echo $form->close(); ?>
			</div>
		</div>
	</div>

</div>
