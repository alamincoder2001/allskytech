<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div class="form-horizontal">

			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Varient Name </label>
				<label class="col-sm-1 control-label no-padding-right">:</label>
				<div class="col-sm-8">
					<input type="text" id="varient_name" name="varient_name" placeholder="Varient Name" value="<?php echo set_value('varient_name'); ?>" class="col-xs-10 col-sm-4" />
					<span id="msg"></span>
					<?php echo form_error('varient_name'); ?>
					<span style="color:red;font-size:15px;">
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"></label>
				<label class="col-sm-1 control-label no-padding-right"></label>
				<div class="col-sm-4 col-xs-10">
					<button type="button" class="btn btn-sm btn-success" onclick="submit()" name="btnSubmit">
						Submit
						<i class="ace-icon fa fa-arrow-right icon-on-right bigger-110"></i>
					</button>
				</div>
			</div>

		</div>
	</div>
</div>



<div class="row">
	<div class="col-xs-12">
		<div class="clearfix">
			<div class="pull-right tableTools-container"></div>
		</div>
		<div class="table-header">
			Varient Information
		</div>
		<!-- div.table-responsive -->

		<!-- div.dataTables_borderWrap -->
		<div id="saveResult">
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
						<th>Varient Name</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$BRANCHid = $this->session->userdata('BRANCHid');
					$query = $this->db->query("SELECT * FROM tbl_varients where status='a' AND branch_id = '$BRANCHid' order by varient_name asc");
					$row = $query->result();
					?>
					<?php $i = 1;
					foreach ($row as $row) { ?>
						<tr>
							<td class="center" style="display:none;">
								<label class="pos-rel">
									<input type="checkbox" class="ace" />
									<span class="lbl"></span>
								</label>
							</td>
							<td><?php echo $i++; ?></td>
							<td><a href="#"><?php echo $row->varient_name; ?></a></td>
							<td>
								<div class="hidden-sm hidden-xs action-buttons">
									<a class="blue" href="#">
										<i class="ace-icon fa fa-search-plus bigger-130"></i>
									</a>

									<a class="green" href="<?php echo base_url() ?>edit_varient/<?php echo $row->id; ?>" title="Eidt" onclick="return confirm('Are you sure you want to Edit this item?');">
										<i class="ace-icon fa fa-pencil bigger-130"></i>
									</a>

									<a class="red" href="#" onclick="deleted(<?php echo $row->id; ?>)">
										<i class="ace-icon fa fa-trash-o bigger-130"></i>
									</a>
								</div>
							</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>



<script type="text/javascript">
	function submit() {
		var varient_name = $("#varient_name").val();
		if (varient_name == "") {
			$("#msg").html("Required Filed").css("color", "red");
			return false;
		}
		let formdata = new FormData();
		formdata.append("varient_name", varient_name)
		var urldata = "<?php echo base_url(); ?>insert_varient";
		$.ajax({
			type: "POST",
			url: urldata,
			data: formdata,
			processData: false,
			contentType: false,
			success: function(data) {
				if (data == "false") {
					alert("This Name Allready Exists");
				} else {
					alert("Save Success");
					location.reload();
					document.getElementById("varient_name").value = '';
				}
			}
		});
	}
</script>

<script type="text/javascript">
	function deleted(id) {
		var deletedd = id;
		var inputdata = 'deleted=' + deletedd;
		var confirmation = confirm("are you sure you want to delete this ?");
		var urldata = "<?php echo base_url() ?>delete_varient";
		if (confirmation) {
			$.ajax({
				type: "POST",
				url: urldata,
				data: inputdata,
				success: function(data) {
					//$("#saveResult").html(data);
					alert("Delete Success");
					window.location.href = '/varient';
				}
			});
		};
	}
</script>
<!-- <script src="<?php echo base_url(); ?>assets/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/dataTables.select.min.js"></script>

<script type="text/javascript">
	$('#dynamictable').dataTable({
		select: {
			style: 'multi'
		},
		bAutoWidth: false,
		"aoColumnDefs": [{
			"aTargets": ['_all'],
			"bSortable": true
		}],

		"aaSorting": [],
		"lengthMenu": [
			[10, 25, 50, 100, -1],
			[10, 25, 50, 100, "All"]
		],
		"pageLength": 25
	});
</script> -->