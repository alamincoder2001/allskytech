<script>
	function editMonth(id) {
		var id = id;
		var inputdata = 'id=' + id;
		var urldata = "<?php echo base_url(); ?>editMonth/" + id;
		$.ajax({
			type: "POST",
			url: urldata,
			data: inputdata,
			success: function(data) {
				$("#saveResult").html(data);
			}
		});
	}
</script>
<div class="row">
	<div class="col-sm-4 col-sm-offset-4">
		<!-- PAGE CONTENT BEGINS -->
		<span id="saveResult">
			<div class="form-group">
				<label class="col-sm-4 control-label no-padding-right" for="form-field-1"> Select Year </label>
				<label class="col-sm-1 control-label no-padding-right">:</label>
				<div class="col-sm-7">
					<select class="form-control" id="year" name="year" style="padding: 1px;border-radius:4px">
						<option value="">Select</option>
						<?php
						for ($i = date("Y") - 3; $i
							<= date("Y") + 5; $i++) {
							echo '<option value="' . $i . '">' . $i . '</option>' . PHP_EOL;
						}
						?>
					</select>
					<span id="year_error"></span>
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-4 control-label no-padding-right" for="form-field-1"> Month Name </label>
				<label class="col-sm-1 control-label no-padding-right">:</label>
				<div class="col-sm-7">
					<input type="text" id="month" name="month" placeholder="Month Name" value="" class="form-control" />
					<span id="msc"></span>
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-4 control-label no-padding-right" for="form-field-1"></label>
				<label class="col-sm-1 control-label no-padding-right"></label>
				<div class="col-sm-7 text-right">
					<button type="button" class="btn btn-success" style="border: none;" onclick="Submitdata()" name="btnSubmit">
						Submit
						<i class="ace-icon fa fa-arrow-right icon-on-right bigger-110"></i>
					</button>
				</div>
			</div>
		</span>
	</div>
</div>



<div class="row">
	<div class="col-xs-12">

		<div class="clearfix">
			<div class="pull-right tableTools-container"></div>
		</div>
		<div class="table-header">
			Month Information
		</div>

		<!-- div.table-responsive -->

		<!-- div.dataTables_borderWrap -->
		<div id="">
			<table id="dynamic-table" class="table table-striped table-bordered table-hover">
				<thead>
					<tr>
						<th class="center" style="display:none;">
							<label class="pos-rel">
								<input type="checkbox" class="ace" />
								<span class="lbl"></span>
							</label>
						</th>
						<th>SL No</th>
						<th>Year</th>
						<th>Month Name</th>
						<th class="hidden-480">Description</th>

						<th>Action</th>
						<th></th>
						<th></th>
					</tr>
				</thead>

				<tbody>
					<?php
					$query = $this->db->query("SELECT * FROM tbl_month order by month_id ASC ");
					$row = $query->result();
					$i = 1;
					foreach ($row as $row) { ?>
						<tr>
							<td class="center" style="display:none;">
								<label class="pos-rel">
									<input type="checkbox" class="ace" />
									<span class="lbl"></span>
								</label>
							</td>

							<td><?php echo $i++; ?></td>
							<td><a href="#"><?php echo $row->year; ?></a></td>
							<td><a href="#"><?php echo $row->month_name; ?></a></td>
							<td class="hidden-480"><?php echo $row->month_name; ?></td>
							<td>
								<?php if ($this->session->userdata('accountType') != 'u') { ?>
									<div class="hidden-sm hidden-xs action-buttons">

										<span class="green" style="cursor:pointer;" onclick="editMonth(<?php echo $row->month_id; ?>);" title="Edit">
											<i class="ace-icon fa fa-pencil bigger-130"></i>
										</span>
									</div>
								<?php } ?>
							</td>

							<td class="hidden-480">
								<span class="label label-sm label-info arrowed arrowed-righ"><?php //echo $row->ProductCategory_Name; 
																								?></span>
							</td>

							<td></td>
						</tr>

					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script type="text/javascript">
	function updatedata() {
		var month_id = $('#month_id').val();
		var month = $('#month').val();
		var year = $('#year').val();

		if (month == "") {
			$('#msc').html('Required Field').css("color", "red");
			return false;
		}
		if (year == "") {
			$('#year_error').html('Required Field').css("color", "red");
			return false;
		}
		var inputdata = 'month_id='
		month_id + '&month=' + month;
		//alert(inputdata);
		var urldata = "<?php echo base_url(); ?>updateMonth";
		$.ajax({
			type: "POST",
			url: urldata,
			data: inputdata,
			success: function(data) {
				if (data) {
					alert(data);
				} else {
					return false;
				}
				location.reload();
			}
		});
	}
</script>
<script type="text/javascript">
	function Submitdata() {
		var month = $('#month').val();
		var year = $('#year').val();


		if (year == "") {
			$('#year_error').html('Required Field').css("color", "red");
			return false;
		} else {
			$('#year_error').html('');
		}
		if (month == "") {
			$('#msc').html('Required Field').css("color", "red");
			return false;
		} else {
			$('#msc').html('');
		}
		var succes = "";
		if (succes == "") {
			var inputdata = 'month=' + month + '& year=' + year;
			//alert(inputdata);
			var urldata = "<?php echo base_url(); ?>insertMonth";
			$.ajax({
				type: "POST",
				url: urldata,
				data: inputdata,
				success: function(data) {
					//$('#success').html('Save Success').css("color","green");
					//$('#Search_Resultsmonth').html(data);
					//alert("ok");
					//setTimeout(function() {$.fancybox.close()}, 500);
					if (data) {
						alert(data);
					} else {
						return false;
					}
					location.reload();
				}
			});
		}
	}
</script>