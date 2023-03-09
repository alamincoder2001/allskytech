<style>
	.saveBtn {
		padding: 7px 22px;
		background-color: #00acb5 !important;
		border-radius: 2px !important;
		border: none;
	}

	.saveBtn:hover {
		padding: 7px 22px;
		background-color: #06777c !important;
		border-radius: 2px !important;
		border: none;
	}

	select.form-control {
		padding: 1px;
	}
</style>
<div id="vehicle">
	<div class="row" style="margin-top: 10px;margin-bottom:15px;border-bottom: 1px solid #ccc;padding-bottom: 15px;">
		<form class="form-horizontal" v-on:submit.prevent="saveDate">
			<div class="col-md-5 col-md-offset-3">
				<div class="form-group">
					<label class="control-label col-md-4">Referance Name</label>
					<label class="col-md-1" style="text-align: right;">:</label>
					<div class="col-md-7">
						<input type="text" class="form-control" v-model="inputField.referance_name">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-4">Mobile No</label>
					<label class="col-md-1" style="text-align: right;">:</label>
					<div class="col-md-7">
						<input type="text" class="form-control" v-model="inputField.mobile_no">
					</div>
				</div>
				<div class="form-group" style="display: none;" :style="{display: inputField.referance_id != '' ? '' : 'none'}">
					<label class="control-label col-md-4">status</label>
					<label class="col-md-1" style="text-align: right;">:</label>
					<div class="col-md-7">
						<select class="form-control" v-model="inputField.status">
							<option value="" selected>Select---</option>
							<option value="a">Active</option>
							<option value="d">Inactive</option>
						</select>
					</div>
				</div>

				<div class="form-group clearfix">
					<div class="col-md-12" style="text-align: right;">
						<input type="submit" class="btn saveBtn" :disabled="saveProcess" value="Add">
					</div>
				</div>
			</div>
		</form>
	</div>

	<div class="row">
		<div class="col-sm-12 form-inline">
			<div class="form-group">
				<label for="filter" class="sr-only">Filter</label>
				<input type="text" class="form-control" v-model="filter" placeholder="Filter">
			</div>
		</div>
		<div class="col-md-12">
			<div class="table-responsive">
				<datatable :columns="columns" :data="allReferance" :filter-by="filter">
					<template scope="{ row }">
						<tr :style="{color: row.status == 'd' ? 'red' :''}">
							<td>{{ row.referance_id }}</td>
							<td>{{ row.referance_name }}</td>
							<td>{{ row.mobile_no }}</td>
							<td>{{ row.status == 'a' ? 'Active' : 'Inactive' }}</td>
							<td>
								<?php if ($this->session->userdata('accountType') != 'u') {
								?>
									<a href="" v-on:click.prevent=" editItem(row)"><i class="fa fa-pencil"></i></a>&nbsp;
									<a href="" class="button" v-on:click.prevent="deleteItem(row.referance_id )"><i class="fa fa-trash"></i></a>
								<?php  }
								?>
							</td>
							<td v-else></td>
						</tr>
					</template>
				</datatable>
				<datatable-pager v-model="page" type="abbreviated" :per-page="per_page"></datatable-pager>
			</div>
		</div>
	</div>
</div>


<script src="<?php echo base_url(); ?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/vuejs-datatable.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/vue-select.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/moment.min.js"></script>
<script>
	new Vue({
		el: '#vehicle',
		data() {
			return {
				inputField: {
					referance_id: '',
					referance_name: '',
					mobile_no: '',
				},
				saveProcess: false,
				allReferance: [],

				columns: [{
						label: 'SL',
						field: 'referance_id',
						align: 'center'
					},
					{
						label: 'Referance Name',
						field: 'referance_name',
						align: 'center'
					},
					{
						label: 'Address',
						field: 'address',
						align: 'center'
					},
					{
						label: 'Status',
						field: 'status',
						align: 'center'
					},
					{
						label: 'Action',
						align: 'center',
						filterable: false
					}
				],
				page: 1,
				per_page: 10,
				filter: ''
			}
		},
		created() {
			this.getReferances();
		},
		methods: {
			getReferances() {
				axios.post('/get-referances', {
					status: ''
				}).then(res => {
					this.allReferance = res.data;
				})
			},
			saveDate() {
				if (this.inputField.referance_name == '') {
					alert('Referance Name is Required!');
					return;
				}
				if (this.inputField.mobile_no == '') {
					alert('Mobile required!');
					return;
				}

				let url = '/save-referance';

				this.saveProcess = true;

				axios.post(url, this.inputField).then(res => {
					let r = res.data;
					alert(r.message);
					if (r.success) {
						this.saveProcess = false;
						// this.getClientCode();
						this.getReferances();
						this.clearForm();
					} else {
						this.saveProcess = false;
					}
				})
			},
			editItem(data) {
				this.inputField.referance_id = data.referance_id;
				this.inputField.referance_name = data.referance_name;
				this.inputField.mobile_no = data.mobile_no;
				this.inputField.status = data.status;
			},
			deleteItem(id) {
				let deleteConfirm = confirm('Are Your Sure to delete the item?');
				if (deleteConfirm == false) {
					return;
				}
				axios.post('/delete-referance', {
					referance_id: id
				}).then(res => {
					let r = res.data;
					alert(r.message);
					if (r.success) {
						this.getReferances();
					}
				})
			},
			clearForm() {
				this.inputField.referance_id = '';
				this.inputField.referance_name = '';
				this.inputField.mobile_no = '';

				delete this.inputField.status;
			}

		}
	})
</script>