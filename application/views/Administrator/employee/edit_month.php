<form class="form-horizontal" action="<?php echo base_url(); ?>updateMonth" method="post">

	<div class="form-group">
		<label class="col-sm-4 control-label no-padding-right" for="form-field-1"> Select Year </label>
		<label class="col-sm-1 control-label no-padding-right">:</label>
		<div class="col-sm-7">
			<select class="form-control" id="year" name="year" value="<?php echo $row->year; ?>" style="padding: 1px;border-radius:4px">
				<option value="">Select</option>
				<?php for ($i = date("Y") - 3; $i <= date("Y") + 5; $i++) { ?>
					<!-- <option value="' . $i . '"  ' . $row->year == $i  ? "selected" : "" . '>' . $i . '</option>; -->
					<option value="<?= $i ?>" <?= $row->year == $i  ? "selected" : "" ?>><?= $i ?></option>
				<?php } ?>
			</select>
			<span id="year_error"></span>
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-4 control-label no-padding-right" for="form-field-1"> Month Name </label>
		<label class="col-sm-1 control-label no-padding-right">:</label>
		<div class="col-sm-7">
			<input type="text" id="month" name="month" placeholder="Month Name" value="<?php echo $row->month_name; ?>" class="form-control" />
			<input type="hidden" id="month_id" name="month_id" value="<?php echo $row->month_id; ?>" class="col-xs-10 col-sm-4" />
			<span id="msc"></span>
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-4 control-label no-padding-right" for="form-field-1"></label>
		<label class="col-sm-1 control-label no-padding-right"></label>
		<div class="col-sm-7 text-right">
			<input type="submit" value="Update" name="submit" class="btn btn-info" style="border: none;">
			<!-- <button type="button" class="btn btn-info" style="border: none;" name="btnSubmit">
				Update
				<i class="ace-icon fa fa-arrow-right icon-on-right bigger-110"></i>
			</button> -->
		</div>
	</div>
</form>