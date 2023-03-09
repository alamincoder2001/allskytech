<style>
.v-select {
    margin-bottom: 5px;
}

.v-select .dropdown-toggle {
    padding: 0px;
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

#branchDropdown .vs__actions button {
    display: none;
}

#branchDropdown .vs__actions .open-indicator {
    height: 15px;
    margin-top: 7px;
}
</style>

<div id="leave">
    <div class="row">
        <div class="col-md-12">
            <div class="widget-box">

                <div class="widget-header">
                    <h4 class="widget-title">Leave Entry</h4>
                    <div class="widget-toolbar">
                        <a href="#" data-action="collapse">
                            <i class="ace-icon fa fa-chevron-up"></i>
                        </a>
                        <a href="#" data-action="close">
                            <i class="ace-icon fa fa-times"></i>
                        </a>
                    </div>
                </div>

                <div class="widget-body">
                    <div class="widget-main">
                        <div class="row" style="margin-bottom: 14px;">
                            <form v-on:submit.prevent="saveLeave">

                                <div class="col-sm-6">
                                    <div class="form-group clearfix">
                                        <label class="col-sm-4 control-label"> Date : </label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" v-model="leave.date" readonly />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label no-padding-right"> Employee </label>
                                        <div class="col-sm-7 mobile-left">
                                            <v-select v-bind:options="employees" label="display_name"
                                                v-model="selectedEmployee" v-on:input="employeeOnChange"></v-select>
                                        </div>
                                        <div class="col-sm-1 mobile-center" style="padding: 0;">
                                            <a href="<?= base_url('employee') ?>" class="btn btn-xs btn-danger"
                                                style="height: 25px; border: 0; width: 27px; margin-left: 5px;"
                                                target="_blank" title="Add New Employee"><i class="fa fa-plus"
                                                    aria-hidden="true" style="margin-top: 5px;"></i></a>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-4 control-label no-padding-right"> Leave Note </label>
                                        <div class="col-sm-7 mobile-left">
                                            <v-select v-bind:options="notes" label="display_note"
                                                v-model="selectedNote"></v-select>
                                        </div>
                                        <div class="col-sm-1 mobile-center" style="padding: 0;">
                                            <a href="<?= base_url('note') ?>" class="btn btn-xs btn-danger"
                                                style="height: 25px; border: 0; width: 27px; margin-left: 5px;"
                                                target="_blank" title="Add New Note"><i class="fa fa-plus"
                                                    aria-hidden="true" style="margin-top: 5px;"></i></a>
                                        </div>
                                    </div>

                                    <!-- <div class="form-group">
                                        <label class="col-sm-4 control-label no-padding-right"> Designation </label>
                                        <div class="col-sm-7 mobile-left">
                                            <v-select v-bind:options="designations" label="Designation_Name"
                                                v-model="selectedDesignation"></v-select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-4 control-label no-padding-right"> Department </label>
                                        <div class="col-sm-7 mobile-left">
                                            <v-select v-bind:options="departments" label="Department_Name"
                                                v-model="selectedDepartment"></v-select>
                                        </div>
                                    </div> -->
                                </div>

                                <div class="col-sm-6">


                                    <div class="form-group clearfix">
                                        <label class="control-label col-sm-4"> Date From : </label>
                                        <div class="col-sm-8">
                                            <input type="date" class="form-control" v-model="leave.date_from"
                                                @change='getTotalDays' min='<?php echo date('Y-m-d') ?>'>
                                        </div>
                                    </div>

                                    <div class="form-group clearfix">
                                        <label class="control-label col-sm-4"> Date To: </label>
                                        <div class="col-sm-8">
                                            <input type="date" class="form-control" v-model="leave.date_to"
                                                @change='getTotalDays'>
                                        </div>
                                    </div>

                                    <div class="form-group clearfix">
                                        <label class="control-label col-sm-4"> Total Days</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" v-model="leave.total_days" disabled>
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <div class="col-sm-12" style="margin-top: 10px;text-align:right">
                                            <input type="submit" class="btn btn-success btn-sm" value="Save">
                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
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
                <datatable :columns="columns" :data="leaves" :filter-by="filter">
                    <template scope="{ row }">
                        <tr>
                            <td>{{ row.leave_SlNo }}</td>
                            <td>{{ row.Employee_Name }}</td>
                            <td>{{ row.Designation_Name }}</td>
                            <td>{{ row.Department_Name }}</td>
                            <td>{{ row.date_from }}</td>
                            <td>{{ row.date_to }}</td>
                            <td>{{ row.total_days }}</td>
                            <td>{{ row.display_note }}</td>
                            <td>{{ row.status == 'a' ? 'Approved' : 'Pending' }}</td>
                            <td>
                                <a href="" title="Edit" @click.prevent="editData(row)">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <a href="" title="Approved" @click.prevent="updateApprove(row)"
                                    style="color:green; font-weight:bold;">
                                    Approved
                                </a>
                                <a href="" class="button" @click.prevent="deleteService(row.leave_SlNo )">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </td>
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
Vue.component('v-select', VueSelect.VueSelect);
new Vue({
    el: '#leave',

    data() {
        return {
            leave: {
                leave_SlNo: '',
                leave_id: '<?php echo $leave_id ?>',
                date: moment().format('YYYY-MM-DD'),
                date_to: '',
                date_from: '',
                Employee_ID: '',
                note_id: '',
                // Designation_ID: '',
                // Department_ID: '',
                total_days: '',
            },
            employees: [],
            notes: [],
            vehicleService: [],
            designations: [],
            departments: [],
            leaves: [],
            selectedEmployee: null,
            selectedNote: null,
            // selectedDesignation: null,
            // selectedDepartment: null,


            columns: [{
                    label: 'Leave Id',
                    field: 'leave_SlNo',
                    align: 'center',
                    filterable: false
                },
                {
                    label: 'Employee Name',
                    field: 'Employee_Name',
                    align: 'center',
                    filterable: false
                },

                {
                    label: 'Designation',
                    field: 'designation',
                    align: 'center'
                },
                {
                    label: 'Department',
                    field: 'Department_Name',
                    align: 'center'
                },

                {
                    label: 'Date From',
                    field: 'date_from',
                    align: 'center'
                },

                {
                    label: 'Date To',
                    field: 'date_to',
                    align: 'center'
                },
                {
                    label: 'Total Days',
                    field: 'total_days',
                    align: 'center'
                },
                {
                    label: 'Leave Information',
                    field: 'display_note',
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
        this.getEmployee();
        this.getNotes();
        this.getLeaveDetails();

        if (this.leave.leave_id != '') {
            this.editLeave();
        }

    },
    methods: {

        getEmployee() {
            axios.get('/get_employees').then(res => {
                this.employees = res.data;
            })
        },

        getNotes() {
            axios.get('/get_notes').then(res => {
                this.notes = res.data;
            })
        },

        getDesignation() {
            axios.get('/get_designation').then(res => {
                this.designations = res.data;
            })
        },
        employeeOnChange() {
            this.getDesignation();
            this.getDepartment();
        },

        // dateOnChange() {
        //     this.getTotalDays();
        // },

        getDepartment() {
            axios.get('/get_department').then(res => {
                this.departments = res.data;
            })
        },

        getTotalDays() {

            const date1 = new Date(this.leave.date_to);
            const date2 = new Date(this.leave.date_from);
            const diffTime = Math.abs(date1 - date2);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            this.leave.total_days = diffDays + 1;
        },


        getLeaveDetails() {

            axios.get('/get_leave_details').then(res => {
                this.leaves = res.data;
            })

        },

        updateApprove(row) {
            let conf = confirm('Are you sure to process?');
            if (conf) {
                axios.post('/update_status_leave', {
                    leave_id: row.leave_SlNo,
                    status: 'a'
                }).then(res => {
                    if (res.data.success) {
                        alert(res.data.message);
                        this.getLeaveDetails();
                    }
                })
            }
        },

        saveLeave() {

            if (this.selectedEmployee != null && this.selectedEmployee.Employee_SlNo == '') {
                alert('Please select a Employee');
                return;
            }

            if (this.selectedNote != null && this.selectedNote.Note_SlNo == '') {
                alert('Please select a Note');
                return;
            }


            // if (this.selectedDesignation != null && this.selectedDesignation.Designation_SlNo == '') {
            //     alert('Please select Designation');
            //     return;
            // }

            // if (this.selectedDepartment != null && this.selectedDepartment.Department_SlNo == '') {
            //     alert('Please select Department');
            //     return;
            // }


            if (this.date_to == '') {
                alert('Please Give Date To ');
                return;
            }


            if (this.date_from == '') {
                alert('Please Give Date From ');
                return;
            }





            this.leave.Employee_ID = this.selectedEmployee.Employee_SlNo;
            this.leave.note_id = this.selectedNote.Note_SlNo;
            // this.leave.Designation_ID = this.selectedDesignation.Designation_SlNo;
            // this.leave.Department_ID = this.selectedDepartment.Department_SlNo;


            let url = '/save_leave_entry';

            axios.post(url, this.leave).then(res => {
                let r = res.data;
                alert(r.message);
                if (r.success) {
                    this.getLeaveDetails();
                    this.clearForm();
                }
            })

        },

        editLeave() {
            axios.post('/get_leave_edit', {
                leave_id: this.leave.leave_id
            }).then(res => {

                let data = res.data[0];
                //console.log(data.date_to);
                this.leave.leave_SlNo = data.leave_SlNo;
                this.leave.date = data.date;
                this.leave.date_to = data.date_to;
                this.leave.date_from = data.date_from;
                this.leave.total_days = data.total_days;
                this.leave.leave_info_details = data.leave_info_details;

                this.selectedEmployee = {
                    Employee_SlNo: data.Employee_ID,
                    display_name: data.Employee_Name,
                }

                this.selectedNote = {
                    Note_SlNo: data.note_id,
                    display_note: data.display_note,
                }

            })
        },

        editData(data) {

            this.leave.leave_SlNo = data.leave_SlNo;
            this.leave.date = data.date;
            this.leave.date_to = data.date_to;
            this.leave.date_from = data.date_from;
            this.leave.total_days = data.total_days;
            this.leave.leave_info_details = data.leave_info_details;

            this.selectedEmployee = {
                Employee_SlNo: data.Employee_ID,
                display_name: data.Employee_Name,
            }

            this.selectedNote = {
                Note_SlNo: data.note_id,
                display_note: data.display_note,
            }

            // this.selectedDesignation = {
            //     Designation_SlNo: data.Designation_ID,
            //     Designation_Name: data.Designation_Name,
            // }

            // this.selectedDepartment = {
            //     Department_SlNo: data.Department_ID,
            //     Department_Name: data.Department_Name
            // }

        },
        deleteService(id) {
            let deleteConfirm = confirm('Are Your Sure to delete the item?');
            if (deleteConfirm == false) {
                return;
            }
            axios.post('/delete_leave_entry', {
                leave_id: id
            }).then(res => {
                let r = res.data;
                alert(r.message);
                if (r.success) {
                    this.getLeaveDetails();
                }
            })
        },
        clearForm() {


            this.leave = {
                leave_SlNo: '',
                date: moment().format('YYYY-MM-DD'),
                date_to: '',
                date_from: '',
                leave_info_details: '',
                Employee_ID: '',
                // Designation_ID: '',
                // Department_ID: '',
                total_days: '',
            }
            this.selectedEmployee = null
            this.selectedNote = null
            // this.selectedDesignation = null
            // this.selectedDepartment = null
        }
    }

})
</script>