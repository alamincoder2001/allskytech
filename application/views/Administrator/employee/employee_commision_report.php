<style>
    .v-select {
        margin-top: -2.5px;
        float: right;
        min-width: 180px;
        margin-left: 5px;
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

    #searchForm select {
        padding: 0;
        border-radius: 4px;
    }

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
</style>
<div id="salesRecord">
    <div class="row" style="border-bottom: 1px solid #ccc;padding: 3px 0;">
        <div class="col-md-12">
            <form class="form-inline" id="searchForm" @submit.prevent="getSearchResult">
                <!-- <div class="form-group">
                    <label>Search Type</label>
                    <select class="form-control" v-model="searchType" @change="onChangeSearchType">
                        <option value="">All</option>
                        <option value="employee">By Employee</option>
                    </select>
                </div> -->

                <div class="form-group">
                    <label>Employee</label>
                    <v-select v-bind:options="employees" v-model="selectedEmployee" label="Employee_Name"></v-select>
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

    <div class="row">
        <div class="col-md-12" style="margin-bottom: 10px;">
            <a href="" @click.prevent="print"><i class="fa fa-print"></i> Print</a>
        </div>
        <div class="col-md-12">
            <div class="table-responsive" id="reportContent">
                <table class="record-table">
                    <thead>
                        <tr>
                            <th>Employee Id</th>
                            <th>Employee Name</th>
                            <th>Year</th>
                            <th>Month</th>
                            <th>Total Sale</th>
                            <th>Commission %</th>
                            <th>Total Commission</th>
                            <th>Commission Withdraw</th>
                            <th>Due Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="comm in reportDatas">
                            <td>{{ selectedEmployee.Employee_ID}}</td>
                            <td>{{ selectedEmployee.Employee_Name}}</td>
                            <td>{{ comm.year}}</td>
                            <td>{{ comm.month_name}}</td>
                            <td style="text-align: right;">{{ comm.total}}</td>
                            <td style="text-align: right;">{{ comm.com_percent}}</td>
                            <td style="text-align: right;">{{ ((comm.total * comm.com_percent)/100).toFixed(2) }}</td>
                            <td style="text-align: right;">{{ comm.com_payment }}</td>
                            <td style="text-align: right;">{{ (((comm.total * comm.com_percent)/100) - +comm.com_payment).toFixed(2) }}</td>
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
<script src="<?php echo base_url(); ?>assets/js/lodash.min.js"></script>

<script>
    Vue.component('v-select', VueSelect.VueSelect);
    new Vue({
        el: '#salesRecord',
        data() {
            return {
                // searchType: '',
                dateFrom: moment().format('YYYY-MM-DD'),
                dateTo: moment().format('YYYY-MM-DD'),
                employees: [],
                selectedEmployee: {
                    Employee_SlNo: '',
                    Employee_Name: '',
                    Employee_ID: '',
                },
                reportDatas: [],
                commissions: [],
            }
        },
        created() {
            this.getEmployees();
        },
        methods: {
            // onChangeSearchType() {
            //     this.commissions = [];
            //     if (this.searchType == 'employee') {
            //         this.getEmployees();
            //     } else {
            //         this.selectedEmployee = null
            //     }
            // },
            getEmployees() {
                axios.get('/get_employees').then(res => {
                    this.employees = res.data;
                })
            },

            getSearchResult() {

                // if (this.searchType != 'employee') {
                //     this.selectedEmployee = null;
                // }
                getMonth = ['', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']

                let filter = {
                    employeeId: this.selectedEmployee.Employee_SlNo == '' ? '' : this.selectedEmployee.Employee_SlNo,
                    dateFrom: this.dateFrom,
                    dateTo: this.dateTo
                }

                axios.post("/get_employee_sale_commission", filter)
                    .then(res => {
                        this.reportDatas = res.data.map(ele => {
                            ele.month_name = getMonth[parseInt(ele.month)];
                            return ele;
                        });
                    })
                    .catch(error => {
                        if (error.response) {
                            alert(`${error.response.status}, ${error.response.statusText}`);
                        }
                    })
            },

            async print() {
                let dateText = '';
                if (this.dateFrom != '' && this.dateTo != '') {
                    dateText = `Statement from <strong>${this.dateFrom}</strong> to <strong>${this.dateTo}</strong>`;
                }

                let employeeText = '';
                if (this.selectedEmployee != null && this.selectedEmployee.Employee_SlNo != '' && this.searchType == 'employee') {
                    employeeText = `<strong>Employee: </strong> ${this.selectedEmployee.Employee_Name}<br>`;
                }

                let reportContent = `
					<div class="container">
						<div class="row">
							<div class="col-xs-12 text-center">
								<h3>Sales Record</h3>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-6">
								${employeeText}
							</div>
							<div class="col-xs-6 text-right">
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

                if (this.searchType == '' || this.searchType == 'user') {
                    let rows = reportWindow.document.querySelectorAll('.record-table tr');
                    rows.forEach(row => {
                        row.lastChild.remove();
                    })
                }


                reportWindow.focus();
                await new Promise(resolve => setTimeout(resolve, 1000));
                reportWindow.print();
                reportWindow.close();
            }
        }
    })
</script>