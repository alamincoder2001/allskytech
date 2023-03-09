<style>
    #searchForm .form-group {
        margin-right: 5px;
    }

    #searchForm * {
        font-size: 13px;
    }

    .record-table {
        width: 100%;
        border-collapse: collapse;
    }

    .record-table thead {
        background-color: #0097df;
        color: white;
    }

    .record-table th,
    .record-table td {
        padding: 3px;
        border: 1px solid #454545;
    }

    .record-table th {
        text-align: center;
    }

    .v-select {
        margin-bottom: 5px;
    }

    .v-select .dropdown-toggle {
        padding: 0px;
        width: 150px !important;
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
</style>
<div id="quotationRecord">
    <div class="row" style="border-bottom: 1px solid #ccc;padding: 3px 0;">
        <div class="col-md-12">


            <form class="form-inline" id="searchForm" @submit.prevent="getSearchResult">
                <div class="form-group">
                    <label>Search Type</label>
                    <select class="form-control" v-model="searchType" @change="onChangeSearchType">
                        <option value="">All</option>
                        <option value="employee">By Employee</option>
                    </select>
                </div>

                <div class="form-group" :style="{display: this.searchType != 'employee'?'none':''}">
                    <v-select :options="employees" v-model="selectedEmployee" label="display_name">
                    </v-select>
                </div>

                <div class="form-group">
                    <input type="date" class="form-control" v-model="dateFrom">
                </div>

                <div class="form-group">
                    <input type="date" class="form-control" v-model="dateTo">
                </div>

                <div class="form-group" style="margin-top: -5px;">
                    <input type="submit" value="Search">
                </div>
            </form>
        </div>
    </div>

    <div class="row" style="margin-top:15px;display:none;" v-bind:style="{display: leaves.length > 0 ? '' : 'none'}">
        <div class="col-md-12" style="margin-bottom: 10px;">
            <a href="" @click.prevent="print"><i class="fa fa-print"></i> Print</a>
        </div>
        <div class="col-md-12">
            <div class="table-responsive" id="reportContent">
                <table class="record-table">
                    <thead>
                        <tr>
                            <th>Leave Id.</th>
                            <th>Employee Name</th>
                            <th>Designation</th>
                            <th>Department</th>
                            <th>Date From</th>
                            <th>Date To </th>
                            <th>Leave Note</th>
                            <th>created Date Time</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="leave in leaves">
                            <td>{{ leave.leave_SlNo }}</td>
                            <td>{{ leave.Employee_Name }}</td>
                            <td>{{ leave.Designation_Name }}</td>
                            <td>{{ leave.Department_Name }}</td>
                            <td>{{ leave.date_from }}</td>
                            <td>{{ leave.date_to}}</td>
                            <td>{{ leave.leave_info_details}}</td>
                            <td>{{ moment(leave.AddTime).format('YYYY-MM-DD hh:mm a')}}</td>
                            <td style="text-align:center;">
                                <?php if ($this->session->userdata('accountType') != 'u') { ?>
                                    <a href="" title="Edit leave" v-bind:href="`/leave_entry/${leave.leave_SlNo}`"><i class="fa fa-edit"></i> Edit</a>
                                    <!-- <a href="" title="Delete Sale" @click.prevent="deleteSale(leave.leave_SlNo)"><i
                                        class="fa fa-trash"></i></a> -->
                                <?php } ?>
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
<script src="<?php echo base_url(); ?>assets/js/vue/vue-select.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/moment.min.js"></script>

<script>
    Vue.component('v-select', VueSelect.VueSelect);
    new Vue({
        el: '#quotationRecord',
        data() {
            return {
                dateFrom: moment().format('YYYY-MM-DD'),
                dateTo: moment().format('YYYY-MM-DD'),
                leaves: [],
                employees: [],
                selectedEmployee: null,
                searchType: ''
            }
        },

        methods: {

            getEmployees() {
                axios.get('/get_employees').then(res => {
                    this.employees = res.data;
                })
            },

            getSearchResult() {
                if (this.searchType != 'employee') {
                    this.selectedEmployee = null;
                }
                this.getLeaveReport();
            },

            getLeaveReport() {

                let filter = {
                    dateFrom: this.dateFrom,
                    dateTo: this.dateTo,
                    EmployeeId: this.selectedEmployee == null || this.selectedEmployee.Employee_SlNo == '' ?
                        '' : this.selectedEmployee.Employee_SlNo
                }

                axios.post('/get_leave_report', filter)
                    .then(res => {
                        this.leaves = res.data;
                    })
            },

            onChangeSearchType() {
                this.quation = [];
                if (this.searchType == 'employee') {
                    this.getEmployees();
                }
            },


            deleteQuotation(quotationId) {
                let deleteConfirm = confirm('Are you sure?');
                if (deleteConfirm == false) {
                    return;
                }
                axios.post('/delete_quotation', {
                    quotationId: quotationId
                }).then(res => {
                    let r = res.data;
                    alert(r.message);
                    if (r.success) {
                        this.getLeaveReport();
                    }
                })
            },
            async print() {
                let dateText = '';
                if (this.filter.dateFrom != '' && this.filter.dateTo != '') {
                    dateText =
                        `Statemenet from <strong>${this.filter.dateFrom}</strong> to <strong>${this.filter.dateTo}</strong>`;
                }
                let reportContent = `
					<div class="container">
						<div class="row">
							<div class="col-xs-12 text-center">
								<h3>Quotation Record</h3>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-12 text-right">
								${dateText}
							</div>
						</div>
						<div class="row">
							<div class="col-xs-12">
								${document.querySelector('#reportContent').innerHTML}
							</div>
						</div>
					</div>
				`;

                var reportWindow = window.open('', 'PRINT', `height=${screen.height}, width=${screen.width}`);
                reportWindow.document.write(`
					<?php $this->load->view('Administrator/reports/reportHeader.php'); ?>
				`);

                reportWindow.document.head.innerHTML += `
					<style>
						.record-table{
							width: 100%;
							border-collapse: collapse;
						}
						.record-table thead{
							background-color: #0097df;
							color:white;
						}
						.record-table th, .record-table td{
							padding: 3px;
							border: 1px solid #454545;
						}
						.record-table th{
							text-align: center;
						}
					</style>
				`;
                reportWindow.document.body.innerHTML += reportContent;

                let rows = reportWindow.document.querySelectorAll('.record-table tr');
                rows.forEach(row => {
                    row.lastChild.remove();
                })


                reportWindow.focus();
                await new Promise(resolve => setTimeout(resolve, 1000));
                reportWindow.print();
                reportWindow.close();
            }
        }
    })
</script>