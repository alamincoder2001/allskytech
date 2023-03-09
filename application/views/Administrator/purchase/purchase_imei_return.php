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

<div id="purchaseRecord">
    <div class="row" style="border-bottom: 1px solid #ccc;padding: 3px 0;">
        <div class="col-md-12">
            <form class="form-inline" v-on:submit.prevent="loadData">
                <div class="form-group">
                    <label>Select IMEI</label>
                    <v-select v-bind:options="allPIEMI" v-model="selectedIEMI" label="ps_imei_number"
                        v-on:input="emptySelectedIEMI"></v-select>
                </div>

                <div class="form-group" style="margin-top: -5px;">
                    <input type="submit" value="Search">
                </div>
            </form>
        </div>
    </div>

    <div class="row" style="margin-top:15px;display:none" :style="{display: showTable ? '' : 'none' }">
        <div class="col-md-12" style="margin-top: 5px;">
            <div class="table-responsive" id="reportContent">
                <table id="table" class="record-table">
                    <thead>
                        <tr>
                            <th>IMEI Number</th>
                            <th>Product Name</th>
                            <th>Purchase Rate</th>
                            <th>Return Amount</th>
                            <th>Note</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="text-align: center;">
                            <td>{{ selectedIEMI.ps_imei_number}}</td>
                            <td>{{ selectedIEMI.Product_Code}} - {{ selectedIEMI.Product_Name}}</td>
                            <td>{{ selectedIEMI.PurchaseDetails_Rate}}</td>
                            <td>
                                <input type="text" style="text-align: center;"
                                    v-model="selectedIEMI.purchse_return_amount">
                            </td>
                            <td>
                                <textarea v-model="selectedIEMI.note" cols="30" rows="2"></textarea>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-warning"
                                    v-on:click.prevent="doReturnProduct">Return</button>
                            </td>

                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url(); ?>assets/js/vue/vue.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/vue-select.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/moment.min.js"></script>

<script>
Vue.component('v-select', VueSelect.VueSelect);
new Vue({
    el: '#purchaseRecord',
    data() {
        return {
            allPIEMI: [],
            selectedIEMI: {
                Product_Code: "",
                Product_Name: "",
                PurchaseDetails_Rate: "",
                ps_imei_number: "",
                purchse_return_amount: ""
            },
            showTable: false,

        }
    },
    created() {
        this.getPurchaseIMEI();

    },
    methods: {
        getPurchaseIMEI() {
            axios.get('/get_purchase_serials').then(res => {
                this.allPIEMI = res.data;
            })
        },
        emptySelectedIEMI() {
            if (this.selectedIEMI.ps_imei_number != '') {
                this.showTable = false;
            }
        },
        loadData() {
            this.showTable = true;
        },
        doReturnProduct() {

            axios.post("/purchase_imei_return", this.selectedIEMI).then(res => {
                alert(res.data.message);
                if (res.data.success) {
                    this.getPurchaseIMEI();
                    this.showTable = false;
                    this.selectedIEMI = {
                        Product_Code: "",
                        Product_Name: "",
                        PurchaseDetails_Rate: "",
                        ps_imei_number: "",
                        purchse_return_amount: ""
                    }
                }
            })
        }
    }
})
</script>