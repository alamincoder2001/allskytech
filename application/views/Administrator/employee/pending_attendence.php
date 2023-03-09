<style>
table tr td {
    font-family: sans-serif;
}

.v-select {
    margin-bottom: 5px;
}

.v-select.open .dropdown-toggle {
    border-bottom: 1px solid #ccc;
}

.v-select .dropdown-toggle {
    padding: 0px;
    height: 25px;
}

.v-select input[type=search],
.v-select input[type=search]:focus {
    margin: 0px;
}

.v-select .vs__selected-options {
    overflow: hidden;
    flex-wrap: nowrap;
}

.v-select .selected-tag {
    margin: 2px 0px;
    white-space: nowrap;
    position: absolute;
    left: 0px;
}

.v-select .vs__actions {
    margin-top: -5px;
}

.v-select .dropdown-menu {
    width: auto;
    overflow-y: auto;
}

#employeeSalary label {
    font-size: 15px;
}

#employeeSalary select {
    border-radius: 3px;
}

#employeeSalary .add-button {
    padding: 2.5px;
    width: 28px;
    background-color: #298db4;
    display: block;
    text-align: center;
    color: white;
}

#employeeSalary .add-button:hover {
    background-color: #41add6;
    color: white;
}

label {
    text-align: left;
}

.btn_pending {
    background: #ffb000;
    color: #fff;
    border: 1px solid #a37103;
    border-radius: 2px;
}

.btn_present {
    background: #1ec300;
    color: #fff;
    border: 1px solid #0f6000;
    border-radius: 2px;
}

.btn_rejected {
    background: #FF0000;
    color: #fff;
    border: 1px solid #0f6000;
    border-radius: 2px;
}
</style>
<div id="employeeSalary">
    <!-- <div class="form-inline">
		<fieldset style="border: 1px solid #ddd;margin-bottom: 20px;padding: 20px; ">
			<div class="form-group col-sm-1">
				<label>Status</label>
			</div>
			<div class="form-group col-sm-2">
				<select v-model="status" class="form-control" disabled :disabled="isDisabled" style="width: 150px;padding: 0px 6px;">
					<option value="">Select</option>
					<option value="p">Pending</option>
					<option value="a">Present</option>
				</select>
			</div>
			<div class="form-group col-sm-2">
				<button class="btn btn-primary" style="padding: 0px 17px;
    border-radius: 4px;" disabled :disabled="isDisabled" v-on:click.prevent="updateStatus">Update</button>
			</div>

		</fieldset>
	</div> -->
    <div class="row" style="margin-top: 20px;">
        <!-- <div class="col-sm-12 form-inline">
			<div class="form-group">
				<label for="filter" class="sr-only">Filter</label>
				<input type="text" class="form-control" v-model="filter" placeholder="Filter">
			</div>
		</div> -->
        <div class="col-md-12" style="display: none;" :style="{display: attendences.length > 0 ? '' : 'none'}">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr style="text-transform:uppercase;font-family: sans-serif;">
                            <th>#</th>
                            <th>Branch Name</th>
                            <th>User Name</th>
                            <th>Date</th>
                            <th>Att. In</th>
                            <th>Lunch Out</th>
                            <th>Lunch In</th>
                            <th>Att. Out</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(attendence,index) in attendences"
                            v-bind:style="{color: attendence.status == 'Present' ? 'green' : 'blue'}">
                            <td>{{ index+1 }}</td>
                            <td>{{ attendence.Brunch_name }}</td>
                            <td>{{ attendence.FullName }}</td>
                            <td>{{ attendence.date }}</td>
                            <td>{{ attendence.attendence_in }}</td>
                            <td>{{ attendence.lunch_out }}</td>
                            <td>{{ attendence.lunch_in }}</td>
                            <td>{{ attendence.attendence_out }}</td>
                            <td>{{ attendence.status == 'Present' ? 'Approved' : attendence.status }}</td>
                            <td>
                                <!-- <button type="button" class="button edit" @click="editStatus(attendence)">
									<i class="fa fa-pencil"></i>
								</button> -->
                                <button type="button" class="button btn_pending"
                                    v-on:click.prevent="updateStatusPending(attendence)">
                                    Pending
                                </button>
                                <button type="button" class="button btn_present"
                                    v-on:click.prevent="updateStatusPresent(attendence)">
                                    Approved
                                </button>

                                <button type="button" class="button btn_rejected"
                                    v-on:click.prevent="deleteStatusPending(attendence)">
                                    Rejected
                                </button>

                            </td>
                        </tr>
                    </tbody>
                </table>
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
Vue.component('v-select', VueSelect.VueSelect);
new Vue({
    el: '#employeeSalary',
    data() {
        return {
            attendences: [],
            // isDisabled: true
        }
    },
    created() {
        this.getAttendences();
    },
    methods: {
        getAttendences() {
            axios.get('/get_all_attendence').then(res => {
                // console.log(res.data);
                this.attendences = res.data;
            })
        },
        // editStatus(data) {
        // 	this.id = data.id;
        // 	this.isDisabled = false;
        // 	this.status = data.db_status;
        // },
        updateStatusPending(attendence) {
            let conf = confirm('Are you sure to process?');
            if (conf) {
                axios.post('/update_status_attendence', {
                    id: attendence.id,
                    status: 'p'
                }).then(res => {
                    if (res.data.success) {
                        alert(res.data.message);
                        this.getAttendences();
                    }
                })
            }
        },

        deleteStatusPending(attendence) {
            let conf = confirm('Are you sure to delete?');
            if (conf) {
                axios.post('/delete_status_attendence', {
                    id: attendence.id
                }).then(res => {
                    if (res.data.success) {
                        alert(res.data.message);
                        this.getAttendences();
                    }
                })
            }
        },

        updateStatusPresent(attendence) {
            let conf = confirm('Are you sure to process?');
            if (conf) {
                axios.post('/update_status_attendence', {
                    id: attendence.id,
                    status: 'a'
                }).then(res => {
                    if (res.data.success) {
                        alert(res.data.message);
                        this.getAttendences();
                    }
                })
            }
        }
    }
})
</script>