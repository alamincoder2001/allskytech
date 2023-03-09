<style>
table tr td {
    font-family: sans-serif;
}

/* .v-select {
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
	} */
label {
    text-align: right;
    padding-right: 0px !important;
    padding-top: 3px;
}
</style>
<div id="salesRecord">
    <div class="row" style="border-bottom: 1px solid #ccc;padding: 3px 0;">
        <div class="col-md-12">
            <form v-on:submit.prevent="searchAtt">
                <div class="form-group row">

                    <div class="col-md-2">
                        <select v-model="brunchId" id="" class="form-control" style="padding: 0px 6px;">
                            <option value="">Select Brunch</option>
                            <option v-for="brunch in branches" :value="brunch.brunch_id ">{{ brunch.Brunch_name }}
                            </option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <select v-model="userId" id="" class="form-control" style="padding: 0px 6px;">
                            <option value="">Select User</option>
                            <option v-for="user in users" :value="user.User_SlNo">{{ user.FullName }} -
                                {{ user.Brunch_name }}</option>
                        </select>
                    </div>
                    <label class="col-sm-1">Date From</label>
                    <div class="col-md-2">
                        <input type="date" class="form-control" v-model="dateFrom">
                    </div>
                    <label class="col-sm-1">Date To</label>
                    <div class="col-md-2">
                        <input type="date" class="form-control" v-model="dateTo">
                    </div>
                    <input type="submit" value="Search">
                </div>
            </form>
        </div>
    </div>

    <div class="row" style="margin:10px;display:none;" v-bind:style="{display: attendences.length > 0 ? '' : 'none'}">
        <div class="row" style="margin-top:15px;">
            <div class="col-md-6" style="margin-bottom: 10px;">
                <a href="" @click.prevent="print"><i class="fa fa-print"></i> Print</a>
            </div>
            <!-- <div class="col-md-6 text-right pt-1">
			<button class="btn btn-sm btn-success" onclick="exportTableToCSV('sales-record.csv')">Export To CSV</button>
		</div> -->
            <div class="col-md-12" style="margin-top: 5px;">
                <div class="table-responsive" id="reportContent">
                    <div id="invoiceHeader"></div>

                    <table class="table table-bordered record-table">
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
                                <th>Approve By</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template v-for="(attendence,index) in attendences">
                                <tr style="background: #eee;" v-if="attendence.status == 'Friday'">
                                    <td>{{ index+1 }}</td>
                                    <td colspan="2"> Friday </td>
                                    <td>{{ attendence.date }}</td>
                                    <td colspan="6"> Friday </td>
                                </tr>
                                <tr style="color:red;" v-else-if="attendence.status == 'Absence'">
                                    <td>{{ index+1 }}</td>
                                    <td></td>
                                    <td></td>
                                    <td>{{ attendence.date }}</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="">{{ attendence.status == 'Absence' ? 'Absence' : '' }}</td>
                                </tr>
                                <tr v-else v-bind:style="{color: attendence.status == 'Approved' ? 'green' : 'blue'}">
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ attendence.Brunch_name }}</td>
                                    <td>{{ attendence.User_Name }}</td>
                                    <td>{{ attendence.date }}</td>
                                    <td>{{ attendence.attendence_in }}</td>
                                    <td>{{ attendence.lunch_out }}</td>
                                    <td>{{ attendence.lunch_in }}</td>
                                    <td>{{ attendence.attendence_out }}</td>
                                    <td>{{ attendence.attendence_out }}</td>
                                    <td>{{ attendence.status }}</td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="<?php echo base_url();?>assets/js/vue/vue.min.js"></script>
    <script src="<?php echo base_url();?>assets/js/vue/axios.min.js"></script>
    <script src="<?php echo base_url();?>assets/js/vue/vue-select.min.js"></script>
    <script src="<?php echo base_url();?>assets/js/moment.min.js"></script>
    <script src="<?php echo base_url();?>assets/js/export-to-csv.js"></script>


    <script>
    Vue.component('v-select', VueSelect.VueSelect);
    new Vue({
        el: '#salesRecord',
        data() {
            return {
                userId: '',
                brunchId: '',
                dateFrom: moment().format('YYYY-MM-DD'),
                dateTo: moment().format('YYYY-MM-DD'),
                attendences: [],
                users: [],
                branches: [],
            }
        },
        created() {
            this.getUsers();
            this.getBranches();
        },
        methods: {
            getUsers() {
                axios.get('/get_users').then(res => {
                    // console.log(res);
                    this.users = res.data;
                })
            },

            getBranches() {
                axios.get('/get_branches').then(res => {
                    this.branches = res.data;
                });
            },

            searchAtt() {
                let data = {
                    userId: this.userId,
                    dateFrom: this.dateFrom,
                    dateTo: this.dateTo,
                }

                axios.post("/search-attendence", data).then(res => {
                    // this.attendences = res.data;

                    if (this.userId != '') {
                        let atten = res.data
                        let attarray = [];
                        let difference = (new Date(this.dateTo).getUTCDate() - new Date(this.dateFrom)
                            .getUTCDate());

                        for (let i = 0; i <= difference; i++) {
                            let date = moment(this.dateFrom).add(i, 'days').format('YYYY-MM-DD');
                            let d = new Date(date);
                            let weekday = d.getDay();

                            if (weekday == 5) {
                                let rowdata = {
                                    id: '',
                                    date: date,
                                    status: 'Friday'
                                }
                                attarray.push(rowdata);
                            } else {
                                let rowdata = {
                                    id: '',
                                    date: date,
                                    attendence_in: '',
                                    attendence_out: '',
                                    lunch_out: '',
                                    lunch_in: '',
                                    status: 'Absence'
                                }
                                attarray.push(rowdata);
                            }
                        }

                        atten.forEach(ele => {
                            for (let i = 0; i <= difference; i++) {
                                let date = moment(this.dateFrom).add(i, 'days').format(
                                    'YYYY-MM-DD');
                                if (ele.date == date) {
                                    attarray.splice(i, 1, ele);
                                }
                            }
                        })

                        this.attendences = attarray;
                    } else {
                        this.attendences = res.data;
                    }
                })
            },

            async print() {
                let dateText = '';
                if (this.dateFrom != '' && this.dateTo != '') {
                    dateText =
                        `Statemenet from <strong>${this.dateFrom}</strong> to <strong>${this.dateTo}</strong>`;
                }

                let userText = '';
                if (this.userId != null && this.userId != '') {
                    userText = `<strong>User Name: </strong> ${this.userId}`;
                }

                let reportContent = `
					<div class="container">
						<div class="row">
							<div class="col-xs-12 text-center">
								<h3>Employee Attendence</h3>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-6"></div>
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

                var reportWindow = window.open('', 'PRINT',
                    `height=${screen.height}, width=${screen.width}`);
                reportWindow.document.write(`
					<?php $this->load->view('Administrator/reports/reportHeader.php');?>
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
                // rows.forEach(row => {
                // 	row.lastChild.remove();
                // })


                reportWindow.focus();
                await new Promise(resolve => setTimeout(resolve, 1000));
                reportWindow.print();
                reportWindow.close();
            }
        }
    })
    </script>