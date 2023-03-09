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
            <form class="form-inline" id="searchForm" @submit.prevent="getSearchResult">
                <div class="form-group">
                    <label>Search Type</label>
                    <select class="form-control" v-model="searchType" @change="onChangeSearchType">
                        <option value="">All</option>
                        <option value="category">By Category</option>
                        <option value="quantity">By Quantity</option>
                        <option value="user">By User</option>
                        <option value="supplier">By Supplier</option>
                    </select>
                </div>

                <div class="form-group" style="display:none;" v-bind:style="{display: searchType == 'supplier' ? '' : 'none'}">
                    <label>Supplier</label>
                    <v-select v-bind:options="suppliers" v-model="selectedSupplier" label="display_name"></v-select>
                </div>

                <div class="form-group" style="display:none;" v-bind:style="{display: searchType == 'quantity' && products.length > 0 ? '' : 'none'}">
                    <label>Product</label>
                    <v-select v-bind:options="products" v-model="selectedProduct" label="display_text"></v-select>
                </div>

                <div class="form-group" style="display:none;" v-bind:style="{display: searchType == 'category' && categories.length > 0 ? '' : 'none'}">
                    <label>Category</label>
                    <v-select v-bind:options="categories" v-model="selectedCategory" label="ProductCategory_Name">
                    </v-select>
                </div>

                <div class="form-group" style="display:none;" v-bind:style="{display: searchType == 'user' && users.length > 0 ? '' : 'none'}">
                    <label>User</label>
                    <v-select v-bind:options="users" v-model="selectedUser" label="FullName"></v-select>
                </div>

                <div class="form-group" v-bind:style="{display: searchType == '' || searchType == 'user' ? '' : 'none'}">
                    <label>Record Type</label>
                    <select class="form-control" v-model="recordType" @change="purchases = []">
                        <option value="without_details">Without Details</option>
                        <option value="with_details">With Details</option>
                    </select>
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

    <div class="row" style="margin-top:15px;display:none;" v-bind:style="{display: purchases.length > 0 ? '' : 'none'}">
        <div class="col-md-12" style="margin-bottom: 10px;">
            <a href="" @click.prevent="print"><i class="fa fa-print"></i> Print</a>
            <div style="float: right; display:inline;">
                <button v-on:click.prvent="pdf" style="background-color: #9aef06;border: 1px solid #9aef06;border-radius: 4px;margin-right: 5px;">
                    PDF </button>
                <button onclick="fnExcelReport();" style="background-color: rgb(140 239 232);border: 1px solid rgb(140 239 232);border-radius: 4px;">
                    EXPORT </button>
            </div>

        </div>
        <!-- <div class="col-md-6 text-right">
			<button class="btn btn-sm btn-success" onclick="exportTableToCSV('purchase-record.csv')">Export To CSV</button>
		</div> -->
        <div class="col-md-12" style="margin-top: 5px;">
            <div class="table-responsive" id="reportContent">
                <table id="table" class="record-table" v-if="(searchType == '' || searchType == 'user') && recordType == 'with_details'" style="display:none" v-bind:style="{display: (searchType == '' || searchType == 'user') && recordType == 'with_details' ? '' : 'none'}">
                    <thead>
                        <tr>
                            <th>Invoice No.</th>
                            <th>Date</th>
                            <th>Supplier Name</th>
                            <th>Reference</th>
                            <th>Product Name</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Discount%</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="purchase in purchases">
                            <tr>
                                <td>{{ purchase.PurchaseMaster_InvoiceNo }}</td>
                                <td>{{ purchase.PurchaseMaster_OrderDate }}</td>
                                <td>{{ purchase.Supplier_Name }}</td>
                                <td>{{ purchase.reference }}</td>
                                <td>{{ purchase.purchaseDetails[0].Product_Name }}</td>
                                <td style="text-align:right;">{{ purchase.purchaseDetails[0].PurchaseDetails_Rate }}
                                </td>
                                <td style="text-align:center;">
                                    {{ purchase.purchaseDetails[0].PurchaseDetails_TotalQuantity }}
                                </td>
                                <td style="text-align:center;">
                                    {{ purchase.purchaseDetails[0].PurchaseDetails_Discount }}
                                </td>
                                <td style="text-align:right;">
                                    {{ purchase.purchaseDetails[0].PurchaseDetails_TotalAmount }}
                                </td>
                                <td style="text-align:center;">
                                    <a href="" title="Purchase Invoice" v-bind:href="`/purchase_invoice_print/${purchase.PurchaseMaster_SlNo}`" target="_blank"><i class="fa fa-file-text"></i></a>
                                    <?php if ($this->session->userdata('accountType') != 'u') { ?>
                                        <a href="" title="Edit Purchase" v-bind:href="`/purchase/${purchase.PurchaseMaster_SlNo}`"><i class="fa fa-edit"></i></a>
                                        <a href="" title="Delete" @click.prevent="deletePurchase(purchase.PurchaseMaster_SlNo)"><i class="fa fa-trash"></i></a>
                                    <?php } ?>
                                </td>
                            </tr>
                            <tr v-for="(product, sl) in purchase.purchaseDetails.slice(1)">
                                <td colspan="3" v-bind:rowspan="purchase.purchaseDetails.length - 1" v-if="sl == 0">
                                </td>
                                <td></td>
                                <td>{{ product.Product_Name }}</td>
                                <td style="text-align:right;">{{ product.PurchaseDetails_Rate }}</td>
                                <td style="text-align:center;">{{ product.PurchaseDetails_TotalQuantity }}</td>
                                <td style="text-align:center;">{{ product.PurchaseDetails_Discount }}</td>
                                <td style="text-align:right;">{{ product.PurchaseDetails_TotalAmount }}</td>
                                <td></td>
                            </tr>
                            <tr style="font-weight:bold;">
                                <td colspan="6">Note:
                                    <span style="font-weight: normal;">{{ purchase.PurchaseMaster_Description }}
                                    </span>
                                </td>

                                <td style="text-align:center;">Total
                                    Quantity<br>{{ purchase.purchaseDetails.reduce((prev, curr) => {return prev + parseFloat(curr.PurchaseDetails_TotalQuantity)}, 0) }}
                                </td>

                                <td></td>
                                <td style="text-align:right;">
                                    Total: {{ purchase.PurchaseMaster_TotalAmount }}<br>
                                    Paid: {{ purchase.PurchaseMaster_PaidAmount }}<br>
                                    Due: {{ purchase.PurchaseMaster_DueAmount }}
                                </td>
                                <td></td>
                            </tr>
                        </template>
                        <tr>
                            <td colspan="12" style="text-align:right; font-weight: bold;">
                                <span> Total Amount : </span>
                                {{ purchases.reduce((prev, curr) => { return prev + +curr.PurchaseMaster_TotalAmount},0)  }}
                            </td>
                        </tr>
                    </tbody>
                </table>

                <table id="table" class="record-table" v-if="(searchType == '' || searchType == 'user' || searchType == 'supplier') && recordType == 'without_details'" style="display:none" v-bind:style="{display: (searchType == '' || searchType == 'user' || searchType == 'supplier') && recordType == 'without_details' ? '' : 'none'}">
                    <thead>
                        <tr>
                            <th>Invoice No.</th>
                            <th>Date</th>
                            <th>Supplier Name</th>
                            <th>Reference</th>
                            <th>Quantity</th>
                            <th>Sub Total</th>
                            <th>VAT</th>
                            <th>Discount</th>
                            <th>Transport Cost</th>
                            <th>Total</th>
                            <th>Paid</th>
                            <th>Due</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="purchase in purchases">
                            <td>{{ purchase.PurchaseMaster_InvoiceNo }}</td>
                            <td>{{ purchase.PurchaseMaster_OrderDate }}</td>
                            <td>{{ purchase.Supplier_Name }}</td>
                            <td>{{ purchase.reference }}</td>
                            <td style="text-align:right;">{{ purchase.purchaseDetails.reduce((prev,curr)=>{
								return prev+parseFloat(curr.PurchaseDetails_TotalQuantity)
							    },0) }}</td>
                            <td style="text-align:right;">{{ purchase.PurchaseMaster_SubTotalAmount }}</td>
                            <td style="text-align:right;">{{ purchase.PurchaseMaster_Tax }}</td>
                            <td style="text-align:right;">{{ purchase.PurchaseMaster_DiscountAmount }}</td>
                            <td style="text-align:right;">{{ purchase.PurchaseMaster_Freight }}</td>
                            <td style="text-align:right;">{{ purchase.PurchaseMaster_TotalAmount }}</td>
                            <td style="text-align:right;">{{ purchase.PurchaseMaster_PaidAmount }}</td>
                            <td style="text-align:right;">{{ purchase.PurchaseMaster_DueAmount }}</td>
                            <td style="text-align:center;">
                                <a href="" title="Purchase Invoice" v-bind:href="`/purchase_invoice_print/${purchase.PurchaseMaster_SlNo}`" target="_blank"><i class="fa fa-file-text"></i></a>
                                <?php if ($this->session->userdata('accountType') != 'u') { ?>
                                    <a href="" title="Edit Purchase" @click.prevent="editPurchase(purchase)"><i class="fa fa-edit"></i></a>
                                    <a href="" title="Delete" @click.prevent="deletePurchase(purchase.PurchaseMaster_SlNo)"><i class="fa fa-trash"></i></a>
                                <?php } ?>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr style="font-weight:bold;">
                            <td colspan="4" style="text-align:right;">Total</td>

                            <!--<td colspan="1" style="text-align:right;"></td>-->

                            <td style="text-align:right;">

                                {{ purchases.reduce((prev, curr)=>{
								return  prev + curr.purchaseDetails.reduce((prevn,currn)=>{
									return prevn + parseFloat(currn.PurchaseDetails_TotalQuantity);
								},0);
								}, 0)}}
                            </td>

                            <td style="text-align:right;">
                                {{ purchases.reduce((prev, curr)=>{return prev + parseFloat(curr.PurchaseMaster_SubTotalAmount)}, 0) }}
                            </td>
                            <td style="text-align:right;">
                                {{ purchases.reduce((prev, curr)=>{return prev + parseFloat(curr.PurchaseMaster_Tax)}, 0) }}
                            </td>
                            <td style="text-align:right;">
                                {{ purchases.reduce((prev, curr)=>{return prev + parseFloat(curr.PurchaseMaster_DiscountAmount)}, 0) }}
                            </td>
                            <td style="text-align:right;">
                                {{ purchases.reduce((prev, curr)=>{return prev + parseFloat(curr.PurchaseMaster_Freight)}, 0) }}
                            </td>
                            <td style="text-align:right;">
                                {{ purchases.reduce((prev, curr)=>{return prev + parseFloat(curr.PurchaseMaster_TotalAmount)}, 0) }}
                            </td>
                            <td style="text-align:right;">
                                {{ purchases.reduce((prev, curr)=>{return prev + parseFloat(curr.PurchaseMaster_PaidAmount)}, 0) }}
                            </td>
                            <td style="text-align:right;">
                                {{ purchases.reduce((prev, curr)=>{return prev + parseFloat(curr.PurchaseMaster_DueAmount)}, 0) }}
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>

                <table id="table" class="record-table" v-if="searchType == 'category' || searchType == 'quantity'" style="display:none;" v-bind:style="{display: searchType == 'category' || searchType == 'quantity' ? '' : 'none'}">
                    <thead>
                        <tr>
                            <th>Invoice No.</th>
                            <th>Date</th>
                            <th>Supplier Name</th>
                            <th>Reference</th>
                            <th>Product Name</th>
                            <th>Purchases Rate</th>
                            <th>Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="purchase in purchases">
                            <td>{{ purchase.PurchaseMaster_InvoiceNo }}</td>
                            <td>{{ purchase.PurchaseMaster_OrderDate }}</td>
                            <td>{{ purchase.Supplier_Name }}</td>
                            <td>{{ purchase.reference }}</td>
                            <td>{{ purchase.Product_Name }}</td>
                            <td style="text-align:right;">{{ purchase.PurchaseDetails_Rate }}</td>
                            <td style="text-align:right;">{{ purchase.PurchaseDetails_TotalQuantity }}</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr style="font-weight:bold;">
                            <td colspan="6" style="text-align:right;">Total Quantity</td>
                            <td style="text-align:right;">
                                {{ purchases.reduce((prev, curr) => { return prev + parseFloat(curr.PurchaseDetails_TotalQuantity)}, 0) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url(); ?>assets/js/vue/vue.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/vue-select.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/moment.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/export-to-csv.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.4.1/jspdf.min.js"></script>

<script type="text/javascript">
    function fnExcelReport() {
        var tab_text = "<table border='2px'><tr bgcolor='#87AFC6'>";
        var textRange;
        var j = 0;
        tab = document.getElementById('table'); // id of table

        for (j = 0; j < tab.rows.length; j++) {
            tab_text = tab_text + tab.rows[j].innerHTML + "</tr>";
            //tab_text=tab_text+"</tr>";
        }

        tab_text = tab_text + "</table>";
        tab_text = tab_text.replace(/<A[^>]*>|<\/A>/g, ""); //remove if u want links in your table
        tab_text = tab_text.replace(/<img[^>]*>/gi, ""); // remove if u want images in your table
        tab_text = tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params

        var ua = window.navigator.userAgent;
        var msie = ua.indexOf("MSIE ");

        if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) // If Internet Explorer
        {
            txtArea1.document.open("txt/html", "replace");
            txtArea1.document.write(tab_text);
            txtArea1.document.close();
            txtArea1.focus();
            sa = txtArea1.document.execCommand("SaveAs", true, "purchase_record.xls");
        } else //other browser not tested on IE 11
            sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));

        return (sa);
    }
</script>


<script>
    Vue.component('v-select', VueSelect.VueSelect);
    new Vue({
        el: '#purchaseRecord',
        data() {
            return {
                searchType: '',
                recordType: 'without_details',
                dateFrom: moment().format('YYYY-MM-DD'),
                dateTo: moment().format('YYYY-MM-DD'),
                suppliers: [],
                selectedSupplier: null,
                products: [],
                selectedProduct: null,
                users: [],
                selectedUser: null,
                categories: [],
                selectedCategory: null,
                purchases: [],
                companylogo: 'NafGroup-Logo.jpg',
                Company_Name: 'NagGroup',
                Repot_Heading: 'This is a Heading of the PDF',
                AddBy: 'Jewel'
            }
        },
        methods: {
            onChangeSearchType() {
                this.purchases = [];
                if (this.searchType == 'quantity') {
                    this.getProducts();
                    this.getSuppliers();
                } else if (this.searchType == 'user') {
                    this.getUsers();
                } else if (this.searchType == 'category') {
                    this.getCategories();
                } else if (this.searchType == 'supplier') {
                    this.getSuppliers();
                }
            },
            getProducts() {
                axios.get('/get_products').then(res => {
                    this.products = res.data;
                })
            },
            getSuppliers() {
                axios.get('/get_suppliers').then(res => {
                    this.suppliers = res.data;
                })
            },
            getUsers() {
                axios.get('/get_users').then(res => {
                    this.users = res.data;
                })
            },
            getCategories() {
                axios.get('/get_categories').then(res => {
                    this.categories = res.data;
                })
            },
            getSearchResult() {
                if (this.searchType != 'user') {
                    this.selectedUser = null;
                }

                if (this.searchType != 'quantity') {
                    this.selectedProduct = null;
                }

                if (this.searchType != 'category') {
                    this.selectedCategory = null;
                }
                if (this.searchType != 'supplier') {
                    this.selectedSupplier = null;
                }

                if (this.searchType == '' || this.searchType == 'user' || this.searchType == 'supplier') {
                    this.getPurchaseRecord();
                } else {
                    this.getPurchaseDetails();
                }
            },
            getPurchaseRecord() {
                let filter = {
                    userFullName: this.selectedUser == null || this.selectedUser.FullName == '' ? '' : this
                        .selectedUser.FullName,
                    supplierId: this.selectedSupplier == null || this.selectedSupplier.Supplier_SlNo == '' ?
                        '' : this.selectedSupplier.Supplier_SlNo,
                    dateFrom: this.dateFrom,
                    dateTo: this.dateTo
                }

                let url = '/get_purchases';
                //if(this.recordType == 'with_details'){
                url = '/get_purchase_record';
                //}

                axios.post(url, filter)
                    .then(res => {
                        // if(this.recordType == 'with_details'){
                        this.purchases = res.data;
                        // } else {
                        // 	this.purchases = res.data.purchases;
                        // }
                    })
                    .catch(error => {
                        if (error.response) {
                            alert(`${error.response.status}, ${error.response.statusText}`);
                        }
                    })
            },
            getPurchaseDetails() {
                let filter = {
                    categoryId: this.selectedCategory == null || this.selectedCategory.ProductCategory_SlNo ==
                        '' ? '' : this.selectedCategory.ProductCategory_SlNo,
                    productId: this.selectedProduct == null || this.selectedProduct.Product_SlNo == '' ? '' : this.selectedProduct.Product_SlNo,
                    dateFrom: this.dateFrom,
                    dateTo: this.dateTo
                }

                axios.post('/get_purchasedetails', filter)
                    .then(res => {
                        this.purchases = res.data;
                    })
                    .catch(error => {
                        if (error.response) {
                            alert(`${error.response.status}, ${error.response.statusText}`);
                        }
                    })
            },
            editPurchase(purchase) {
                let deleteConf = confirm('Are you sure to edit?');
                if (deleteConf == false) {
                    return;
                }
                axios.post('/get_purchase_returns', {
                        purchaseInvoice: purchase.PurchaseMaster_InvoiceNo
                    })
                    .then(res => {
                        if (res.data.returns.length == 0) {
                            window.location.href = 'purchase/' + purchase.PurchaseMaster_SlNo;
                        } else {
                            alert('Return Founded. Edit not possible.');
                        }
                    })
                    .catch(error => {
                        if (error.response) {
                            alert(`${error.response.status}, ${error.response.statusText}`);
                        }
                    })
            },
            deletePurchase(purchaseId) {
                let deleteConf = confirm('Are you sure?');
                if (deleteConf == false) {
                    return;
                }
                axios.post('/delete_purchase', {
                        purchaseId: purchaseId
                    })
                    .then(res => {
                        let r = res.data;
                        alert(r.message);
                        if (r.success) {
                            this.getPurchaseRecord();
                        }
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
                    dateText =
                        `Statemenet from <strong>${this.dateFrom}</strong> to <strong>${this.dateTo}</strong>`;
                }

                let userText = '';
                if (this.selectedUser != null && this.selectedUser.FullName != '' && this.searchType ==
                    'user') {
                    userText = `<strong>Sold by: </strong> ${this.selectedUser.FullName}`;
                }

                let supplierText = '';
                if (this.selectedSupplier != null && this.selectedSupplier.Supplier_SlNo != '' && this
                    .searchType == 'quantity') {
                    supplierText = `<strong>Supplier: </strong> ${this.selectedSupplier.Supplier_Name}<br>`;
                }

                let productText = '';
                if (this.selectedProduct != null && this.selectedProduct.Product_SlNo != '' && this
                    .searchType == 'quantity') {
                    productText = `<strong>Product: </strong> ${this.selectedProduct.Product_Name}`;
                }

                let categoryText = '';
                if (this.selectedCategory != null && this.selectedCategory.ProductCategory_SlNo != '' && this
                    .searchType == 'category') {
                    categoryText = `<strong>Category: </strong> ${this.selectedCategory.ProductCategory_Name}`;
                }


                let reportContent = `
					<div class="container">
						<div class="row">
							<div class="col-xs-12 text-center">
								<h3>Purchase Record</h3>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-6">
								${userText} ${supplierText} ${productText} ${categoryText}
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
            },
            async pdf() {

                var pdf = new jsPDF("l", "mm", 'a4');
                let header = `
					<div class="row">
						<div class="col-xs-2"><img src="/uploads/company_profile_thum/${this.companylogo}" alt="Logo" style="height:80px;" /></div>
						<div class="col-xs-10" style="padding-top:20px;">
							<strong style="font-size:18px;">${this.Company_Name}</strong><br>
							<p style="white-space:pre-line;">${this.Repot_Heading}</p>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12">
							<div style="border-bottom: 4px double #ccc;margin-top:7px;margin-bottom:7px;"></div>
						</div>
					</div>
				`;
                let footer = `
					<div style="width:100%;height:80px;">&nbsp;</div>
					<div class="row" style="border-bottom:1px solid #ccc;margin-bottom:5px;padding-bottom:6px;">
						<div class="col-xs-6">
							<span style="text-decoration:overline;">Received by</span><br><br>
							* THANK YOU FOR YOUR BUSINESS *
						</div>
						<div class="col-xs-6 text-right">
							<span style="text-decoration:overline;">Authorized by</span>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-6" style="font-size:10px;">
							Print Date: ${moment().format('DD-MM-YYYY h:mm a')}, Printed by: ${this.AddBy}
						</div>
						<div class="col-xs-6 text-right" style="font-size:10px;">
						
						</div>
					</div>
				`;

                // document.getElementById("invoiceHeader").innerHTML = header;
                // // document.getElementById("invoiceFooter").innerHTML = footer;
                var element = document.getElementById('reportContent');

                var width = element.style.width;
                var height = element.style.height;

                await html2canvas(element).then(canvas => {
                    var imgData = canvas.toDataURL('image/png');
                    var imgWidth = 200;
                    var pageHeight = 295;
                    var imgHeight = canvas.height * imgWidth / canvas.width;
                    var heightLeft = imgHeight;
                    var doc = new jsPDF('p', 'mm', 'a4');
                    var position = 5;

                    doc.addImage(imgData, 'PNG', 5, position, imgWidth, (imgHeight - 10));
                    heightLeft -= pageHeight;

                    while (heightLeft >= 0) {
                        position = heightLeft - imgHeight;
                        doc.addPage();
                        doc.addImage(imgData, 'PNG', 5, position, imgWidth, imgHeight);
                        // doc.addImage(imgData, 'PNG', 5, position, imgWidth, imgHeight);
                        heightLeft -= pageHeight;
                    }
                    doc.save('purchase_record.pdf');

                });

            },
        }
    })
</script>