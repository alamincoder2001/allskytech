<div class="form-horizontal">
	<form method="POST" id="assetsFormUpdate" action="#">
		<div class="row">
			<div class="col-xs-5 col-xs-offset-1">
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="assetsname"> Asset Id </label>
					<label class="col-sm-1 control-label no-padding-right">:</label>
					<div class="col-sm-7">
						<input type="text" name="assetserial" class="form-control" value="<?= $edit->asset_id ?>" readonly />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="assetsname"> Assets Name </label>
					<label class="col-sm-1 control-label no-padding-right">:</label>
					<div class="col-sm-7">
						<input type="text" id="assetsname" value="<?= $edit->as_name; ?>" required name="assetsname" placeholder="Assets Name" class="form-control" />
						<span id="error"></span>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="serial"> Asset Serials </label>
					<label class="col-sm-1 control-label no-padding-right">:</label>
					<div class="col-sm-7">
						<input type="text" id="serial" required name="serial" value="<?= $edit->serial; ?>" placeholder="Serials" class="form-control" />
						<span id="error"></span>
					</div>
				</div>
			</div>
			<div class="col-xs-5">
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="rate"> Rate </label>
					<label class="col-sm-1 control-label no-padding-right">:</label>
					<div class="col-sm-7">
						<input type="number" id="rate" value="<?= $edit->as_rate; ?>" required name="rate" placeholder="Rate" class="form-control" />
						<span id="error"></span>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="qty"> Quantity </label>
					<label class="col-sm-1 control-label no-padding-right">:</label>
					<div class="col-sm-7">
						<input type="number" id="qty" value="<?= $edit->as_qty; ?>" required name="qty" placeholder="Quantity" onblur="TotalAmount()" class="form-control" />
						<span id="error"></span>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="amount"> Amount </label>
					<label class="col-sm-1 control-label no-padding-right">:</label>
					<div class="col-sm-7">
						<input type="number" id="amount" value="<?= $edit->as_amount; ?>" readonly name="amount" placeholder="Amount" class="form-control" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1"></label>
					<label class="col-sm-1 control-label no-padding-right"></label>
					<div class="col-sm-7">
						<button type="button" class="btn btn-sm btn-success" onclick="UpdateAssets(<?= $edit->as_id; ?>)" name="btnSubmit">
							Update
							<i class="ace-icon fa fa-arrow-right icon-on-right bigger-110"></i>
						</button>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>