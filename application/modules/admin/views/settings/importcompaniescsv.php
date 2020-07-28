<div class="row">

	<div class="col-md-6">
		<div class="box box-primary">
			<div class="box-header">
				<h3 class="box-title">Import companies list from uploaded CSV</h3>
			</div>
			<div class="box-body">
          <form method="post" action="<?php echo base_url() ?>admin/settings/importcompaniescsv" enctype="multipart/form-data">
              <input type="file" name="userfile" ><br><br>
              <input type="submit" name="submit" value="UPLOAD" class="btn btn-primary">
          </form>
			</div>
		</div>
	</div>

</div>
