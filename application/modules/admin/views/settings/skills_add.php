<?php echo $form->messages(); ?>

<div class="row">

	<div class="col-md-10">
		<div class="box box-primary">

			<div class="box-body">
				<?php echo $form->open(); ?>
					<?php echo $form->bs3_text('Skill Name', 'name', ''); ?>
					<?php echo $form->bs3_submit(); ?>
				<?php echo $form->close(); ?>
			</div>
		</div>
	</div>

</div>
