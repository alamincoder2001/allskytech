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
    font-family: sans-serif;
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
    font-family: sans-serif;
}

button.btn.btn-primary.btn-sm {
    font-size: 14px;
    /* margin-right: 10px; */
    padding: 3px 12px;
    font-family: sans-serif;
}
</style>
<div id="employeeSalary">
    <form @submit.prevent="savePayment">
        <fieldset style="border: 1px solid #ddd;margin-bottom: 20px;padding: 20px;">
            <div class="row" style="margin-top: 10px;padding-bottom: 15px; text-align:center">
                <div class="form-inline">
                    <div class="col-md-10">
                        <label class="control-label col-md-2">User Name</label>
                        <input type="text" class="form-control col-md-2" v-model="userName" disabled>
                    </div>
                </div>
                <div class="form-inline">
                    <div class="col-md-10" style="margin-bottom: 20px;">
                        <label class="control-label col-md-2">Date</label>
                        <input type="text" class="form-control col-md-2" v-model="date" disabled>
                    </div>
                </div>
                <div class="col-md-10">
                    <label class="control-label col-md-2">Attendence:</label>
                    <div class="col-md-10 text-left" style="padding-left: 0;">
                        <button class="btn btn-primary btn-sm" id="a_in" value="a_in" v-on:click="updateAIn" disabled
                            :disabled="attendence_in">Attendence In</button>
                        <button class="btn btn-primary btn-sm" id="l_out" value="l_out" v-on:click="updateLOut" disabled
                            :disabled="lunch_out">Lunch Out</button>
                        <button class="btn btn-primary btn-sm" id="l_in" value="l_in" v-on:click="updateLIn" disabled
                            :disabled="lunch_in">Lunch In</button>
                        <button class="btn btn-primary btn-sm" id="a_out" value="a_out" v-on:click="updateAOut" disabled
                            :disabled="attendence_out">Attendence Out</button>
                    </div>
                </div>
            </div>
        </fieldset>
    </form>

    <div class="row">
        <div class="col-sm-12 form-inline">
            <!-- <div class="form-group">
				<label for="filter" class="sr-only">Filter</label>
				<input type="text" class="form-control" v-model="filter" placeholder="Filter">
			</div> -->
        </div>
        <div class="col-md-12" style="display: none;" :style="{display: attendences.length > 0 ? '' : 'none'}">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr style="background: #4c4c4c;color: #fff;font-family: sans-serif;">
                            <th>#</th>
                            <th>Date</th>
                            <th>User Name</th>
                            <th>Attendence In Time</th>
                            <th>Lunch Out Time</th>
                            <th>Lunch In Time</th>
                            <th>Attendence Out Time</th>
                            <th>status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="(attendence,index) in attendences">
                            <tr style="background: #eee;" v-if="attendence.status == 'Friday'">
                                <td>{{ index+1 }}</td>
                                <td>{{ attendence.date }}</td>
                                <td colspan="6"> Friday </td>
                            </tr>
                            <tr style="color:red;" v-else-if="attendence.status == 'Absence'">
                                <td>{{ index+1 }}</td>
                                <td>{{ attendence.date }}</td>
                                <td colspan="5"> </td>
                                <td class="">{{ attendence.status == 'Absence' ? 'Absence' : '' }}</td>
                            </tr>
                            <tr v-else v-bind:style="{color: attendence.status == 'a' ? 'green' : 'blue'}">
                                <td>{{ index+1 }}</td>
                                <td>{{ attendence.date }}</td>
                                <td>{{ attendence.FullName }}</td>
                                <td>{{ attendence.attendence_in }}</td>
                                <td>{{ attendence.lunch_out }}</td>
                                <td>{{ attendence.lunch_in }}</td>
                                <td>{{ attendence.attendence_out }}</td>
                                <td class="">{{ attendence.status == 'a' ? 'Present' : 'Pending' }}</td>
                            </tr>
                        </template>
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
            userName: '<?php echo $fullName ?>',
            userId: '<?php echo $userId ?>',
            date: moment().format('YYYY-MM-DD'),
            attendences: [],
            users: [],
            attendence_in: true,
            attendence_out: true,
            lunch_out: true,
            lunch_in: true,
            tableRow: new Date().getUTCDate(),
            styleObj: {
                background: '#eee',
                color: 'red'
            }
        }
    },
    created() {
        this.getAttendences();
        this.getUsers();
    },
    methods: {
        getAttendences() {
            axios.get('/get_attendence').then(res => {
                let atten = res.data
                let attarray = [];

                for (let i = 0; i < this.tableRow; i++) {
                    let date = moment().add(-i, 'days').format('YYYY-MM-DD');

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
                    for (let i = 0; i < this.tableRow; i++) {
                        let date = moment().add(-i, 'days').format('YYYY-MM-DD');
                        if (ele.date == date) {
                            attarray.splice(i, 1, ele);
                        }
                    }
                })

                this.attendences = attarray;
                this.checkTodayAttendence();
            })
        },
        getUsers() {
            axios.get('/get_users').then(res => {
                this.users = res.data;
            })
        },
        checkTodayAttendence() {
            this.attendence_in = false;
            this.attendences.forEach(element => {
                if (element.date == this.date && this.userId == element.user_id) {
                    if (element.attendence_in == null) {
                        this.attendence_in = false;
                    } else {
                        this.attendence_in = true;
                    }
                    if (element.attendence_out == null) {
                        this.attendence_out = false;
                    } else {
                        this.attendence_out = true;
                    }
                    if (element.lunch_out == null) {
                        this.lunch_out = false;
                    } else {
                        this.lunch_out = true;
                    }
                    if (element.lunch_in == null) {
                        this.lunch_in = false;
                    } else {
                        this.lunch_in = true;
                    }
                }
            });
        },
        updateAIn() {
            let aIn = $("#a_in").val();
            axios.post("/update_attendence", {
                value: aIn
            }).then(res => {
                if (res.data.success) {
                    this.attendence_in = true;
                    this.getAttendences();
                    alert('attendence Success');
                }
            })
        },
        updateAOut() {
            let aOut = $("#a_out").val();
            axios.post("/update_attendence", {
                value: aOut
            }).then(res => {
                if (res.data.success) {
                    this.attendence_in = true;
                    this.getAttendences();
                    alert('attendence Success');
                }
            })
        },
        updateLOut() {
            let lOut = $("#l_out").val();
            axios.post("/update_attendence", {
                value: lOut
            }).then(res => {
                if (res.data.success) {
                    this.attendence_in = true;
                    this.getAttendences();
                    alert('attendence Success');
                }
            })
        },
        updateLIn() {
            let lIn = $("#l_in").val();
            axios.post("/update_attendence", {
                value: lIn
            }).then(res => {
                if (res.data.success) {
                    this.attendence_in = true;
                    this.getAttendences();
                    alert('attendence Success');
                }
            })
        }

    }
})
</script>