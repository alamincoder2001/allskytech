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
                employees: [],
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
        created() {
            this.getEmployees();
        },
        methods: {
            getEmployees() {
                axios.get('/get_employees').then(res => {
                    this.employees = res.data;
                })
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

        }
    })
</script>