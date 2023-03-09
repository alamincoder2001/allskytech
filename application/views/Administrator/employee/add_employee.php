<style>
    select.form-control {
        padding: 1px;
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

    #employee label {
        font-size: 13px;
    }

    #employee select {
        border-radius: 3px;
    }

    #employee .add-button {
        padding: 2.5px;
        width: 28px;
        background-color: #298db4;
        display: block;
        text-align: center;
        color: white;
    }

    #employee .add-button:hover {
        background-color: #41add6;
        color: white;
    }

    #employee input[type="file"] {
        padding: 1px;
    }

    #employee .custom-file-upload {
        border: 1px solid #ccc;
        display: inline-block;
        padding: 5px 12px;
        cursor: pointer;
        margin-top: 5px;
        background-color: #298db4;
        border: none;
        color: white;
    }

    #employee .custom-file-upload:hover {
        background-color: #41add6;
    }

    #customerImage {
        height: 60px;
    }

    .addcartitem {
        padding: 3px 8px;
        margin-left: 3px;
        border: none;
        background: #298db4;
        color: #fff;
    }

    .delcartitem {
        padding: 3px 9px;
        margin-left: 3px;
        background: red;
        color: #fff;
        font-weight: bold;
        border: none;
    }
</style>
<div id="employee">
    <form @submit.prevent="saveCustomer">
        <div class="row" style="margin-top: 10px;margin-bottom:15px;border-bottom: 1px solid #ccc;padding-bottom:15px;">
            <div class="col-md-5 col-md-offset-1">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-sm-12"> <strong>Job Information</strong></div>
                        <hr />
                        <div class="form-group clearfix">
                            <label class="control-label col-md-4">Employee ID:</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" v-model="employee.Employee_ID" required readonly>
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <label class="control-label col-md-4">Employee Name: <span style="color: red;">*</span></label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" placeholder="Employee name" v-model="employee.Employee_Name" required>
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <label class="control-label col-md-4">Designation: <span style="color: red;">*</span></label>
                            <div class="col-md-7">
                                <v-select v-bind:options="designations" v-model="selectedDesignation" label="Designation_Name"></v-select>
                            </div>
                            <div class="col-md-1" style="padding:0;margin-left: -15px;">
                                <a href="/designation" target="_blank" class="add-button"><i class="fa fa-plus"></i></a>
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <label class="control-label col-md-4">Department: <span style="color: red;">*</span></label>
                            <div class="col-md-7">
                                <v-select v-bind:options="departments" v-model="selectedDepartment" label="Department_Name"></v-select>
                            </div>
                            <div class="col-md-1" style="padding:0;margin-left: -15px;">
                                <a href="/depertment" target="_blank" class="add-button"><i class="fa fa-plus"></i></a>
                            </div>
                        </div>


                        <div class="form-group clearfix">
                            <label class="control-label col-md-4">Joint Date: <span style="color: red;">*</span></label>
                            <div class="col-md-8">
                                <input type="date" class="form-control" v-model="employee.Joint_Date" required>
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <label class="control-label col-md-4">Salary Range: <span style="color: red;">*</span></label>
                            <div class="col-md-8">
                                <input type="number" placeholder="Salary Range" class="form-control" v-model="employee.Salary_Range" required>
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <label class="control-label col-md-4">Activation Status: <span style="color: red;">*</span></label>
                            <div class="col-md-8">
                                <select class="form-control" v-model="employee.Activation_Status" required>
                                    <option value="">Select</option>
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row" style="margin-top: 20px;">
                    <div class="col-md-12">
                        <div class="col-sm-12"> <strong>Personal Information</strong></div>
                        <hr />
                        <div class="form-group clearfix">
                            <label class="control-label col-md-4">Father's Name: <span style="color: red;">*</span></label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" placeholder="Father's name" v-model="employee.Fathers_Name" required>
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <label class="control-label col-md-4">Mother's Name:</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" placeholder="Mother's name" v-model="employee.Mothers_Name">
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <label class="control-label col-md-4">Gender: <span style="color: red;">*</span></label>
                            <div class="col-md-8">
                                <select class="form-control" v-model="employee.Gender" required>
                                    <option value="">Select</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Others">Others</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <label class="control-label col-md-4">Date_of_Birth:</label>
                            <div class="col-md-8">
                                <input type="date" class="form-control" v-model="employee.Date_of_Birth">
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <label class="control-label col-md-4">Marital Status:</label>
                            <div class="col-md-8">
                                <select class="form-control" v-model="employee.Marital_Status">
                                    <option value="">Select</option>
                                    <option value="Married">Married</option>
                                    <option value="Unmarried">Unmarried</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-5">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-sm-12"> <strong>Contact Information</strong></div>
                        <hr />
                        <div class="form-group clearfix">
                            <label class="control-label col-md-4">Present Address: <span style="color: red;">*</span></label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" placeholder="Present Address" v-model="employee.Present_Address" required>
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <label class="control-label col-md-4">Permanent Address:</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" placeholder="Permanent Address" v-model="employee.Permanent_Address">
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <label class="control-label col-md-4">Contact No: <span style="color: red;">*</span></label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" placeholder="Contact No" v-model="employee.Contact_No" required>
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <label class="control-label col-md-4">E-mail:</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" placeholder="E-mail" v-model="employee.Email">
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <label class="control-label col-md-4">Employee Image:</label>
                            <div class="col-md-8">
                                <input type="file" class="form-control" v-model="employee.Employee_Image" @change="previewImage">
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <label class="control-label col-md-4"></label>
                            <div class="col-md-8">
                                <div style="width: 62px;height: 62px;border: 1px solid rgb(204, 204, 204);">
                                    <img id="customerImage" v-if="imageUrl == '' || imageUrl == null" src="/assets/no_image.gif">
                                    <img id="customerImage" v-if="imageUrl != '' && imageUrl != null" v-bind:src="imageUrl">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row" style="margin-top: 20px;">
                    <div class="col-md-12">
                        <div class="col-sm-12"> <strong>Additional Information</strong></div>
                        <hr />
                        <div class="form-group clearfix">
                            <label class="control-label col-md-4">Commission %:</label>
                            <div class="col-md-8">
                                <table class="table">
                                    <tr v-for=" (item,index) in commissions">
                                        <td style="padding:0px; border:none">
                                            <input type="number" class="form-control" v-model="item.start" placeholder="Start">
                                        </td>
                                        <td style="padding:0px; border:none">
                                            <input type="number" class="form-control" v-model="item.end" placeholder="end">
                                        </td>
                                        <td style="padding:0px; border:none">
                                            <input type="number" class="form-control" v-model="item.commission" placeholder="com. %">
                                        </td>
                                        <td style="padding:0px; border:none">
                                            <button v-if="index == 0" class="addcartitem" v-on:click.prevent="AddCommissionItem"> + </button>
                                            <button v-else class="delcartitem" v-on:click.prevent="delCommissionItem(index)"> - </button>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <div class="form-group clearfix">
                                <label class="control-label col-md-4">Education Details:</label>
                                <div class="col-md-8">
                                    <textarea class="form-control" cols="30" rows="5" v-model="employee.Education_Details" placeholder="Education Details"></textarea>
                                </div>
                            </div>

                            <div class="form-group clearfix">
                                <div class="col-md-8 col-md-offset-4 text-right">
                                    <input type="submit" class="btn btn-success btn-sm" value="Save">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="row">
        <div class="col-sm-12 form-inline">
            <div class="form-group">
                <label for="filter" class="sr-only">Filter</label>
                <input type="text" class="form-control" v-model="filter" placeholder="Filter">
            </div>
        </div>
        <div class="col-md-12">
            <div class="table-responsive">
                <datatable :columns="columns" :data="employees" :filter-by="filter" style="margin-bottom: 5px;">
                    <template scope="{ row }">
                        <tr>
                            <td v-if="row.Employee_Pic_thum != null ">
                                <img :src="'/uploads/employee/'+ row.Employee_Pic_thum" alt="Picture" style="width:60px;">
                            </td>
                            <td v-else>
                                <img src="/uploads/employee/sample.jpg" alt="Picture" style="width:60px;">
                            </td>
                            <td>{{ row.Employee_ID }}</td>
                            <td>{{ row.Employee_Name }}</td>
                            <td>{{ row.Designation_Name }}</td>
                            <td>{{ row.Department_Name }}</td>
                            <td>{{ row.Employee_ContactNo }}</td>
                            <td>
                                <?php if ($this->session->userdata('accountType') != 'u') { ?>
                                    <button type="button" class="button edit" @click="editItem(row)">
                                        <i class="fa fa-pencil"></i>
                                    </button>
                                    <button type="button" class="button" @click="deleteItem(row.Employee_SlNo)">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                <?php } ?>
                            </td>
                        </tr>
                    </template>
                </datatable>
                <datatable-pager v-model="page" type="abbreviated" :per-page="per_page" style="margin-bottom: 50px;"></datatable-pager>
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
        el: '#employee',
        data() {
            return {
                employee: {
                    Employee_SlNo: '',
                    Employee_ID: '<?= $employeeID ?>',
                    Employee_Name: '',
                    Designation: '',
                    Department: '',
                    Joint_Date: '',
                    Salary_Range: '',
                    Activation_Status: '',
                    Fathers_Name: '',
                    Mothers_Name: '',
                    Gender: '',
                    Date_of_Birth: '',
                    Marital_Status: '',
                    Present_Address: '',
                    Permanent_Address: '',
                    Contact_No: '',
                    Email: '',
                    Employee_Image: '',
                    Education_Details: '',
                },
                commissions: [{
                    start: '',
                    end: '',
                    commission: ''
                }],
                designations: [],
                selectedDesignation: {
                    Designation_SlNo: '',
                    Designation_Name: 'Select'
                },
                departments: [],
                selectedDepartment: {
                    Department_SlNo: '',
                    Department_Name: 'Select'
                },

                employees: [],

                imageUrl: '',
                selectedFile: null,

                columns: [{
                        label: 'Photo',
                        field: 'Employee_Pic_thum',
                        align: 'center',
                        filterable: false
                    },
                    {
                        label: 'Employee Id',
                        field: 'Employee_ID',
                        align: 'center',
                        filterable: false
                    },
                    {
                        label: 'Employee Name',
                        field: 'Employee_Name',
                        align: 'center'
                    },
                    {
                        label: 'Designation Name',
                        field: 'Designation_Name',
                        align: 'center'
                    },
                    {
                        label: 'Department Name',
                        field: 'Department_Name',
                        align: 'center'
                    },
                    {
                        label: 'Contact Number',
                        field: 'Employee_ContactNo',
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
        // filters: {
        //     dateOnly(datetime, format) {
        //         return moment(datetime).format(format);
        //     }
        // },
        created() {
            this.getDesignations();
            this.getDepartments();
            this.getEmployees();
        },
        methods: {
            getDesignations() {
                axios.get('/get_designation').then(res => {
                    this.designations = res.data;
                })
            },
            getDepartments() {
                axios.get('/get_department').then(res => {
                    this.departments = res.data;
                })
            },
            getEmployees() {
                axios.get('/get_employees').then(res => {
                    this.employees = res.data;
                })
            },
            previewImage() {
                if (event.target.files.length > 0) {
                    this.selectedFile = event.target.files[0];
                    this.imageUrl = URL.createObjectURL(this.selectedFile);
                } else {
                    this.selectedFile = null;
                    this.imageUrl = null;
                }
            },
            AddCommissionItem() {
                let item = {
                    start: '',
                    end: '',
                    commission: ''
                }
                this.commissions.push(item);
            },
            delCommissionItem(index) {
                this.commissions.splice(index, 1)
            },

            saveCustomer() {
                if (this.selectedDesignation.Designation_SlNo == '') {
                    alert('Select Designation');
                    return;
                }
                if (this.selectedDepartment.Department_SlNo == '') {
                    alert('Select Department');
                    return;
                }

                this.employee.Designation = this.selectedDesignation.Designation_SlNo;
                this.employee.Department = this.selectedDepartment.Department_SlNo;


                let url = '/employeeInsert';
                if (this.employee.Employee_SlNo != '') {
                    url = '/employeeUpdate';
                }

                let fd = new FormData();
                fd.append('image', this.selectedFile);
                fd.append('data', JSON.stringify(this.employee));
                fd.append('commission', JSON.stringify(this.commissions));

                // console.log(url);
                // return;

                axios.post(url, fd, {
                    onUploadProgress: upe => {
                        let progress = Math.round(upe.loaded / upe.total * 100);
                        console.log(progress);
                    }
                }).then(res => {
                    let r = res.data;
                    alert(r.message);
                    if (r.success) {
                        window.location.href = '/employee'
                    }
                })
            },
            editItem(data) {

                this.employee.Employee_SlNo = data.Employee_SlNo;
                this.employee.Employee_ID = data.Employee_ID;
                this.employee.Employee_Name = data.Employee_Name;
                this.employee.Joint_Date = data.Employee_JoinDate;
                this.employee.Salary_Range = data.salary_range;
                this.employee.Activation_Status = data.status == 'a' ? 'Active' : 'Inactive';
                this.employee.Fathers_Name = data.Employee_FatherName;
                this.employee.Mothers_Name = data.Employee_MotherName;
                this.employee.Gender = data.Employee_Gender;
                this.employee.Date_of_Birth = data.Employee_BirthDate;
                this.employee.Marital_Status = data.Employee_MaritalStatus;
                this.employee.Present_Address = data.Employee_PrasentAddress;
                this.employee.Permanent_Address = data.Employee_PermanentAddress;
                this.employee.Contact_No = data.Employee_ContactNo;
                this.employee.Email = data.Employee_Email;
                this.employee.Education_Details = data.Education_Details;

                if (data.Employee_Pic_thum == null || employee.Employee_Pic_thum == '') {
                    this.imageUrl = null;
                } else {
                    this.imageUrl = '/uploads/employee/' + data.Employee_Pic_thum;
                }

                this.selectedDesignation = {
                    Designation_SlNo: data.Designation_ID,
                    Designation_Name: data.Designation_Name
                }
                this.selectedDepartment = {
                    Department_SlNo: data.Department_ID,
                    Department_Name: data.Department_Name
                }

                if (data.commissions.length == 0) {
                    this.commission = [{
                        start: '',
                        end: '',
                        commission: ''
                    }]
                } else {

                    this.commissions = data.commissions
                }

            },
            deleteItem(empId) {
                let deleteConfirm = confirm('Are you sure?');
                if (deleteConfirm == false) {
                    return;
                }
                axios.post('/employeeDelete', {
                    employeeId: empId
                }).then(res => {
                    let r = res.data;
                    alert(r.message);
                    if (r.success) {
                        this.getEmployees();
                    }
                })
            },
            // resetForm() {
            //     let keys = Object.keys(this.employee);
            //     keys = keys.filter(key => key != "Customer_Type");
            //     keys.forEach(key => {
            //         if (typeof(this.customer[key]) == 'string') {
            //             this.customer[key] = '';
            //         } else if (typeof(this.customer[key]) == 'number') {
            //             this.customer[key] = 0;
            //         }
            //     })
            //     this.imageUrl = '';
            //     this.selectedFile = null;

            // }
        }
    })
</script>