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
                    <v-select v-bind:options="allSIEMI" v-model="selectedIEMI" label="ps_imei_number"
                        v-on:input="emptySelectedIEMI"></v-select>
                </div>

                <div class="form-group" style="margin-top: -5px;">
                    <input type="submit" value="Search">
                </div>
            </form>
        </div>
    </div>

    <div class="row" style="margin-top:15px;display:none" :style="{display: showTable ? '' : 'none' }">
        <div class="col-md-6" style="margin-top: 5px;">
            <h5 style="text-align:center; font-weight:bold;">Purchase Serial Details </h5>
            <div class="table-responsive" id="reportContent">
                <table id="table" class="record-table">
                    <tbody>
                        <tr>
                            <th>Supplier Name</th>
                            <td>{{iemi_histories.Supplier_Name}}</td>
                        </tr>

                        <tr>
                            <th>Supplier Code</th>
                            <td>{{iemi_histories.Supplier_Code}}</td>
                        </tr>
                        <tr>
                            <th>Supplier Moiblie</th>
                            <td>{{iemi_histories.Supplier_Mobile}}</td>
                        </tr>

                        <tr>
                            <th>Product Code</th>
                            <td>{{iemi_histories.Product_Code}}</td>
                        </tr>
                        <tr>
                            <th>Product Name</th>
                            <td>{{iemi_histories.Product_Name}}</td>
                        </tr>

                        <tr>
                            <th>Purchase Invoice</th>
                            <td>{{iemi_histories.PurchaseMaster_InvoiceNo}}</td>
                        </tr>

                        <tr>
                            <th>Purchase Date</th>
                            <td>{{iemi_histories.PurchaseMaster_OrderDate}}</td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-6" style="margin-top: 5px;">
            <h5 style="text-align:center; font-weight:bold;">Sale Serial Details </h5>
            <div class="table-responsive" id="reportContent">
                <table id="table" class="record-table">
                    <tbody>
                        <tr>
                            <th>Customer Name</th>
                            <td>{{iemi_histories.Customer_Name}}</td>
                        </tr>

                        <tr>
                            <th>Customer Code</th>
                            <td>{{iemi_histories.Customer_Code}}</td>
                        </tr>

                        <tr>
                            <th> Customer Mobile</th>
                            <td> {{iemi_histories.Customer_Mobile}}</td>
                        </tr>

                        <tr>
                            <th>Product Code</th>
                            <td>{{iemi_histories.Product_Code}}</td>
                        </tr>

                        <tr>
                            <th>Product Name</th>
                            <td>{{iemi_histories.Product_Name}}</td>
                        </tr>

                        <tr>
                            <th>Sales Invoice</th>
                            <td>{{iemi_histories.SaleMaster_InvoiceNo}}</td>
                        </tr>
                        <tr>
                            <th> Sale Date </th>
                            <td> {{iemi_histories.SaleMaster_SaleDate}}</td>
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
            allSIEMI: [],
            selectedIEMI: null,
            showTable: false,
            iemi_histories: [],
        }
    },
    created() {
        this.getPurchaseIMEI();

    },
    methods: {
        getPurchaseIMEI() {
            axios.get('/get_all_serials').then(res => {
                this.allSIEMI = res.data;
            })
        },
        emptySelectedIEMI() {
            if (this.selectedIEMI.ps_imei_number != '') {
                this.showTable = false;
            }
        },
        loadData() {
            this.showTable = true;
            this.getIemiHistory();
        },


        getIemiHistory() {

            let filter = {
                ps_id: this.selectedIEMI.ps_id
            }

            axios.post("/get_imei_history", filter).then(res => {
                console.log(res.data);
                this.iemi_histories = res.data[0];

            })
        }
    }
})
</script>