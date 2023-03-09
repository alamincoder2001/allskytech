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

            <!-- <form class="form-inline" id="searchForm" @submit.prevent="getQuotations">
                <div class="form-group">
                    <input type="date" class="form-control" v-model="filter.dateFrom">
                </div>

                <div class="form-group">
                    <input type="date" class="form-control" v-model="filter.dateTo">
                </div>

                <div class="form-group" style="margin-top: -5px;">
                    <input type="submit" value="Search">
                </div>
            </form> -->

            <form class="form-inline" id="searchForm" @submit.prevent="getSearchResult">
                <div class="form-group">
                    <label>Search Type</label>
                    <select class="form-control" v-model="searchType" @change="onChangeSearchType">
                        <option value="">All</option>
                        <option value="customer">By Customer</option>
                    </select>
                </div>

                <div class="form-group" :style="{display: this.searchType != 'customer'?'none':''}">
                    <v-select :options="customers" v-model="selectedCustomer" label="display_name">
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

    <div class="row" style="margin-top:15px;display:none;" v-bind:style="{display: quotations.length > 0 ? '' : 'none'}">
        <div class="col-md-12" style="margin-bottom: 10px;">
            <a href="" @click.prevent="print"><i class="fa fa-print"></i> Print</a>
        </div>
        <div class="col-md-12">
            <div class="table-responsive" id="reportContent">
                <table class="record-table">
                    <thead>
                        <tr>
                            <th>Invoice No.</th>
                            <th>Date</th>
                            <th>Customer Name</th>
                            <th>Customer Mobile</th>
                            <th>Customer Address</th>
                            <th>Sub Total</th>
                            <th>VAT</th>
                            <th>Discount</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="quotation in quotations">
                            <td>{{ quotation.SaleMaster_InvoiceNo }}</td>
                            <td>{{ quotation.SaleMaster_SaleDate }}</td>
                            <td>{{ quotation.SaleMaster_customer_name }}</td>
                            <td>{{ quotation.SaleMaster_customer_mobile }}</td>
                            <td>{{ quotation.SaleMaster_customer_address }}</td>
                            <td style="text-align:right;">{{ quotation.SaleMaster_SubTotalAmount }}</td>
                            <td style="text-align:right;">{{ quotation.SaleMaster_TaxAmount }}</td>
                            <td style="text-align:right;">{{ quotation.SaleMaster_TotalDiscountAmount }}</td>
                            <td style="text-align:right;">{{ quotation.SaleMaster_TotalSaleAmount }}</td>
                            <td style="text-align:center;">
                                <a href="" v-bind:href="`/quotation_invoice/${quotation.SaleMaster_SlNo}`" title="View Invoice"><i class="fa fa-file"></i></a>
                                <?php if ($this->session->userdata('accountType') != 'u') { ?>
                                    <a href="" v-bind:href="`/quotation/${quotation.SaleMaster_SlNo}`" title="Edit Quotation"><i class="fa fa-edit"></i></a>
                                    <a href="" @click.prevent="deleteQuotation(quotation.SaleMaster_SlNo)" title="Delete Quotation"><i class="fa fa-trash"></i></a>
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
                quotations: [],
                customers: [],
                selectedCustomer: null,
                searchType: ''
            }
        },

        methods: {

            getCustomers() {
                axios.get('/get_customers').then(res => {
                    this.customers = res.data;
                })
            },

            getSearchResult() {

                if (this.searchType != 'customer') {
                    this.selectedCustomer = null;
                }

                this.getQuotations();
            },

            getQuotations() {

                let filter = {
                    dateFrom: this.dateFrom,
                    dateTo: this.dateTo,
                    customerName: this.selectedCustomer == null || this.selectedCustomer.Customer_Name == '' ?
                        '' : this.selectedCustomer.Customer_Name
                }

                axios.post('/get_quotations', filter)
                    .then(res => {
                        this.quotations = res.data.quotations;
                    })
            },

            onChangeSearchType() {
                this.quation = [];
                if (this.searchType == 'customer') {
                    this.getCustomers();
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
                        this.getQuotations();
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