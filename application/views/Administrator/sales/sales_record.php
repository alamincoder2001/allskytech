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
                <div class="form-group">
                    <label>Search Type</label>
                    <select class="form-control" v-model="searchType" @change="onChangeSearchType">
                        <option value="">All</option>
                        <option value="customer">By Customer</option>
                        <option value="area">By Area</option>
                        <option value="employee">By Employee</option>
                        <option value="category">By Category</option>
                        <option value="quantity">By Quantity</option>
                        <option value="brand">By Brand</option>
                        <option value="user">By User</option>
                    </select>
                </div>

                <div class="form-group" style="display:none;" v-bind:style="{display: searchType == 'customer' && customers.length > 0 ? '' : 'none'}">
                    <label>Customer</label>
                    <v-select v-bind:options="customers" v-model="selectedCustomer" label="display_name"></v-select>
                </div>

                <div class="form-group" style="display:none;" v-bind:style="{display: searchType == 'employee' && employees.length > 0 ? '' : 'none'}">
                    <label>Employee</label>
                    <v-select v-bind:options="employees" v-model="selectedEmployee" label="Employee_Name"></v-select>
                </div>

                <div class="form-group" style="display:none;" v-bind:style="{display: searchType == 'category' && categories.length > 0 || searchType == 'customer' ? '' : 'none'}">
                    <label>Category</label>
                    <v-select v-bind:options="categories" v-model="selectedCategory" label="ProductCategory_Name"></v-select>
                </div>

                <div class="form-group" style="display:none;" v-bind:style="{display: searchType == 'brand' && brands.length > 0  || searchType == 'customer' ? '' : 'none'}">
                    <label>Brand</label>
                    <v-select v-bind:options="brands" v-model="selectedBrand" label="brand_name"></v-select>
                </div>

                <div class="form-group" style="display:none;" v-bind:style="{display: searchType == 'quantity' && products.length > 0 || searchType == 'customer' ? '' : 'none'}">
                    <label>Product</label>
                    <v-select v-bind:options="products" v-model="selectedProduct" label="display_text" @input="sales = []"></v-select>
                </div>


                <div class="form-group" style="display:none;" v-bind:style="{display: searchType == 'area' && areas.length > 0 ? '' : 'none'}">
                    <label>Area</label>
                    <v-select v-bind:options="areas" v-model="selectedArea" label="District_Name"></v-select>
                </div>

                <div class="form-group" style="display:none;" v-bind:style="{display: searchType == 'user' && users.length > 0 ? '' : 'none'}">
                    <label>User</label>
                    <v-select v-bind:options="users" v-model="selectedUser" label="FullName"></v-select>
                </div>

                <div class="form-group" v-bind:style="{display: searchTypesForRecord.includes(searchType) ? '' : 'none'}">
                    <label>Record Type</label>
                    <select class="form-control" v-model="recordType" @change="sales = []">
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

    <div class="row" style="margin-top:15px;display:none;" v-bind:style="{display: sales.length > 0 ? '' : 'none'}">
        <div class="col-md-6" style="margin-bottom: 10px;">
            <a href="" @click.prevent="print"><i class="fa fa-print"></i> Print</a>
        </div>

        <div class="col-md-6" style="margin-bottom: 10px; display:none" v-if="(searchTypesForRecord.includes(searchType)) && recordType == 'without_details'" v-bind:style="{display: (searchTypesForRecord.includes(searchType)) && recordType == 'without_details' ? '' : 'none'}">
            <div class="form-group" style="width:37%; float:right;">
                <label for="filter" class="sr-only">Filter</label>
                <input type="text" class="form-control" v-model="filter" placeholder="Search Record">
            </div>
        </div>

        <div class="col-md-12">
            <div class="table-responsive" id="reportContent">

                <table class="table table-striped table-bordered table-hover" v-if="(searchTypesForRecord.includes(searchType)) && recordType == 'with_details'" style="display:none" v-bind:style="{display: (searchTypesForRecord.includes(searchType)) && recordType == 'with_details' ? '' : 'none'}">
                    <thead>
                        <tr>
                            <th>SL No</th>
                            <th>Invoice No.</th>
                            <th>Date</th>
                            <!-- <th>Area</th> -->
                            <th>Customer Name</th>
                            <th>Employee Name</th>
                            <th>Saved By</th>
                            <th>Product Name</th>
                            <th>Brand</th>
                            <th>W/G</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="(sale,sl) in sales">
                            <tr>
                                <td>{{ sl + 1 }}</td>
                                <td>{{ sale.SaleMaster_InvoiceNo }}</td>
                                <td>{{ sale.SaleMaster_SaleDate }}</td>
                                <!-- <td>{{ sale.area }}</td> -->
                                <td>{{ sale.Customer_Name }}</td>
                                <td>{{ sale.Employee_Name }}</td>
                                <td>{{ sale.AddBy }}</td>
                                <td>{{ sale.saleDetails[0].Product_Name }}</td>
                                <td>{{ sale.saleDetails[0].brand_name }}</td>
                                <td>W - {{ sale.saleDetails[0].Warranty }} days <br>
                                    G - {{ sale.saleDetails[0].Guarantee }} days</td>
                                <td style="text-align:right;">{{ sale.saleDetails[0].SaleDetails_Rate }}</td>
                                <td style="text-align:center;">{{ sale.saleDetails[0].SaleDetails_TotalQuantity }}</td>
                                <td style="text-align:right;">{{ sale.saleDetails[0].SaleDetails_TotalAmount }}</td>
                                <td style="text-align:center;">
                                    <a href="" title="Sale Invoice" v-bind:href="`/sale_invoice_print/${sale.SaleMaster_SlNo}`" target="_blank"><i class="fa fa-file"></i></a>
                                    <a href="" title="Chalan" v-bind:href="`/chalan/${sale.SaleMaster_SlNo}`" target="_blank"><i class="fa fa-barcode"></i></a>
                                    <?php if ($this->session->userdata('accountType') != 'u') { ?>
                                        <a href="" title="Edit Sale" v-bind:href="`/sales/${sale.is_service == 'true' ? 'service' : 'product'}/${sale.SaleMaster_SlNo}`"><i class="fa fa-edit"></i></a>
                                        <a href="" title="Delete Sale" @click.prevent="deleteSale(sale.SaleMaster_SlNo)"><i class="fa fa-trash"></i></a>
                                    <?php } ?>
                                </td>
                            </tr>
                            <tr v-for="(product, sl) in sale.saleDetails.slice(1)">
                                <td colspan="7" v-bind:rowspan="sale.saleDetails.length - 1" v-if="sl == 0"></td>
                                <td>{{ product.Product_Name }}</td>
                                <td>{{ product.brand_name }}</td>
                                <td style="text-align:right;">{{ product.SaleDetails_Rate }}</td>
                                <td style="text-align:center;">{{ product.SaleDetails_TotalQuantity }}</td>
                                <td style="text-align:right;">{{ product.SaleDetails_TotalAmount }}</td>
                                <td></td>
                            </tr>
                            <tr style="font-weight:bold;">
                                <td colspan="10" style="font-weight:normal;"><strong>Note: </strong>{{ sale.SaleMaster_Description }}</td>
                                <td style="text-align:center;">Total Quantity<br>{{ sale.saleDetails.reduce((prev, curr) => {return prev + parseFloat(curr.SaleDetails_TotalQuantity)}, 0) }}</td>
                                <td style="text-align:right;">
                                    Total: {{ sale.SaleMaster_TotalSaleAmount }}<br>
                                    Paid: {{ sale.SaleMaster_PaidAmount }}<br>
                                    Due: {{ sale.SaleMaster_DueAmount }}
                                </td>
                                <td></td>
                            </tr>
                        </template>
                    </tbody>
                </table>

                <datatable :columns="columns" :data="sales" :filter-by="filter" v-if="(searchTypesForRecord.includes(searchType)) && recordType == 'without_details'" style="margin-bottom: 0; display:none; " v-bind:style="{display: (searchTypesForRecord.includes(searchType)) && recordType == 'without_details' ? '' : 'none'}">
                    <template scope="{ row }">
                        <tr>
                            <td>{{ row.SaleMaster_InvoiceNo }}</td>
                            <td>{{ row.SaleMaster_SaleDate }}</td>
                            <td>{{ row.Customer_Name }}</td>
                            <td>{{ row.Employee_Name }}</td>
                            <td>{{ row.AddBy }}</td>
                            <td>{{ row.SaleMaster_SubTotalAmount }}</td>
                            <td>{{ row.SaleMaster_TaxAmount }}</td>
                            <td>{{ row.SaleMaster_TotalDiscountAmount }}</td>
                            <td>{{ row.SaleMaster_Freight }}</td>
                            <td>{{ row.SaleMaster_TotalSaleAmount }}</td>
                            <td>{{ row.SaleMaster_PaidAmount }}</td>
                            <td>{{ row.SaleMaster_DueAmount }}</td>
                            <td>{{ row.SaleMaster_Description }}</td>
                            <td>
                                <a href="" title="Sale Invoice" v-bind:href="`/sale_invoice_print/${row.SaleMaster_SlNo}`" target="_blank"><i class="fa fa-file"></i></a>
                                <a href="" title="Chalan" v-bind:href="`/chalan/${row.SaleMaster_SlNo}`" target="_blank"><i class="fa fa-file-o"></i></a>
                                <?php if ($this->session->userdata('accountType') != 'u') { ?>
                                    <a href="" title="Edit Sale" v-bind:href="`/sales/${row.is_service == 'true' ? 'service' : 'product'}/${row.SaleMaster_SlNo}`"><i class="fa fa-edit"></i></a>
                                    <a href="" title="Delete Sale" @click.prevent="deleteSale(row.SaleMaster_SlNo)"><i class="fa fa-trash"></i></a>
                                <?php } ?>
                            </td>
                        </tr>
                    </template>
                </datatable>

                <!-- <datatable :columns="allColumns" :data="rows">
                    <template slot="footer" scope="{ rows }">
                        <tr>
                            <td colspan="5"><strong>Average Age:</strong></td>
                            <td></td>
                            <td></td>
                            <td align="center"><strong>54405</strong></td>
                            <td align="center"><strong>5440</strong></td>
                            <td align="center"><strong>54450</strong></td>
                            <td align="center"><strong>54450</strong></td>
                            <td align="center"><strong>54450</strong></td>
                            <td align="center"><strong>54450</strong></td>
                            <td align="center"><strong>544050</strong></td>
                            <td></td>
                            <td></td>
                  
                        </tr>   
                    </template>
                </datatable> -->


                <div style="width:90%; float:left;">
                    <table border="1" style="width: 100%; border-color:#eaeaea; display:none" cellspadding="0" v-if="(searchTypesForRecord.includes(searchType)) && recordType == 'without_details'" v-bind:style="{display: (searchTypesForRecord.includes(searchType)) && recordType == 'without_details' ? '' : 'none'}">
                        <tr>
                            <td style="text-align:center; width:43%; font-weight:bold;">Total</td>
                            <td style="text-align:center; width:5%;">{{ sales.reduce((prev, curr)=>{return prev + parseFloat(curr.SaleMaster_SubTotalAmount)}, 0) }}</td>
                            <td style="text-align:center; width:4%;">{{ sales.reduce((prev, curr)=>{return prev + parseFloat(curr.SaleMaster_TaxAmount)}, 0) }}</td>
                            <td style="text-align:center; width:6%;">{{ sales.reduce((prev, curr)=>{return prev + parseFloat(curr.SaleMaster_TotalDiscountAmount)}, 0) }}</td>
                            <td style="text-align:center; width:9%;"> {{ sales.reduce((prev, curr)=>{return prev + parseFloat(curr.SaleMaster_Freight)}, 0) }} </td>
                            <td style="text-align:center; width:5%;">{{ sales.reduce((prev, curr)=>{return prev + parseFloat(curr.SaleMaster_TotalSaleAmount)}, 0) }}</td>
                            <td style="text-align:center; width:5%;">{{ sales.reduce((prev, curr)=>{return prev + parseFloat(curr.SaleMaster_PaidAmount)}, 0) }}</td>
                            <td style="text-align:center; width:5%;">{{ sales.reduce((prev, curr)=>{return prev + parseFloat(curr.SaleMaster_DueAmount)}, 0) }}</td>
                        </tr>
                    </table>
                </div>

                <datatable-pager v-model="page" type="abbreviated" :per-page="per_page" style="margin-bottom: 50px;"></datatable-pager>

                <template v-if="searchTypesForDetails.includes(searchType)" style="display:none;" v-bind:style="{display: searchTypesForDetails.includes(searchType) ? '' : 'none'}">
                    <table class="record-table" v-if="selectedProduct != null">
                        <thead>
                            <tr>
                                <th>Invoice No.</th>
                                <th>Date</th>
                                <th>Customer Name</th>
                                <th>Product Name</th>
                                <th>Brand</th>
                                <th>Sales Rate</th>
                                <th>Quantity</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="sale in sales">
                                <td>{{ sale.SaleMaster_InvoiceNo }}</td>
                                <td>{{ sale.SaleMaster_SaleDate }}</td>
                                <td>{{ sale.Customer_Name }}</td>
                                <td>{{ sale.Product_Name }}</td>
                                <td>{{ sale.brand_name }}</td>
                                <td style="text-align:right;">{{ sale.SaleDetails_Rate }}</td>
                                <td style="text-align:right;">{{ sale.SaleDetails_TotalQuantity }}</td>
                                <td style="text-align:right;">{{ sale.SaleDetails_TotalAmount }}</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr style="font-weight:bold;">
                                <td colspan="6" style="text-align:right;">Total Quantity</td>
                                <td style="text-align:right;">{{ sales.reduce((prev, curr) => { return prev + parseFloat(curr.SaleDetails_TotalQuantity)}, 0) }}</td>
                                <td style="text-align:right;">{{ sales.reduce((prev, curr) => { return prev + parseFloat(curr.SaleDetails_TotalAmount)}, 0) }}</td>
                            </tr>
                        </tfoot>
                    </table>

                    <table class="table table-striped table-bordered table-hover" v-if="selectedProduct == null">
                        <thead>
                            <tr>
                                <th>Product Id</th>
                                <th>Product Information</th>
                                <th>Brand</th>
                                <th>Quantity</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template v-for="sale in sales">
                                <tr>
                                    <td colspan="5" style="text-align:center;background: #ccc;">{{ sale.category_name }}</td>
                                </tr>
                                <tr v-for="product in sale.products">
                                    <td>{{ product.product_code }}</td>
                                    <td>{{ product.product_name }}</td>
                                    <td>{{ product.brand_name }}</td>
                                    <td style="text-align:right;">{{ product.quantity }}</td>
                                    <td style="text-align:right;">{{ product.total }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" style="text-align: right;"><strong>Total</strong></td>
                                    <td style="text-align: right;"><strong>{{ sale.products.reduce((p, c) => {return +p + +c.quantity}, 0) }}</strong></td>
                                    <td style="text-align: right;"><strong>{{ sale.products.reduce((p, c) => {return +p + +c.total}, 0) }}</strong></td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </template>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url(); ?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/vue-select.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/vuejs-datatable.js"></script>
<script src="<?php echo base_url(); ?>assets/js/moment.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/lodash.min.js"></script>

<script>
    Vue.component('v-select', VueSelect.VueSelect);
    new Vue({
        el: '#salesRecord',
        data() {
            return {
                searchType: '',
                recordType: 'without_details',
                dateFrom: '',
                dateTo: moment().format('YYYY-MM-DD'),
                customers: [],
                selectedCustomer: null,
                employees: [],
                selectedEmployee: null,
                products: [],
                selectedProduct: null,
                users: [],
                selectedUser: null,
                categories: [],
                selectedCategory: null,
                areas: [],
                selectedArea: null,
                brands: [],
                selectedBrand: null,
                sales: [],
                searchTypesForRecord: ['', 'customer', 'user', 'area', 'employee'],
                searchTypesForDetails: ['quantity', 'category', 'brand'],

                columns: [{
                        label: 'Invoice No.',
                        field: 'SaleMaster_InvoiceNo',
                        align: 'center'
                    },
                    {
                        label: 'Date',
                        field: 'SaleMaster_SaleDate',
                        align: 'center'
                    },
                    {
                        label: 'Customer Name',
                        field: 'Customer_Name',
                        align: 'center'
                    },
                    {
                        label: 'Employee Name',
                        field: 'Employee_Name',
                        align: 'center'
                    },
                    {
                        label: 'Saved By',
                        field: 'AddBy',
                        align: 'center'
                    },
                    {
                        label: 'Sub Total',
                        field: '',
                        align: 'center'
                    },
                    {
                        label: 'VAT',
                        field: 'SaleMaster_TaxAmount',
                        align: 'center'
                    },
                    {
                        label: 'Discount',
                        field: 'SaleMaster_TotalDiscountAmount',
                        align: 'center'
                    },
                    {
                        label: 'Transport Cost',
                        field: 'SaleMaster_Freight',
                        align: 'center'
                    },
                    {
                        label: 'Total',
                        field: 'SaleMaster_TotalSaleAmount',
                        align: 'center'
                    },
                    {
                        label: 'Paid',
                        field: 'SaleMaster_PaidAmount',
                        align: 'center'
                    },
                    {
                        label: 'Due',
                        field: 'SaleMaster_DueAmount',
                        align: 'center'
                    },
                    {
                        label: 'Note',
                        field: 'SaleMaster_Description',
                        align: 'center'
                    },
                    {
                        label: 'Action',
                        align: 'center',
                        filterable: false
                    }
                ],
                allColumns: [],
                rows: {
                    user: 'tarekul',
                    id: 1
                },
                page: 1,
                per_page: 10,
                filter: '',
            }
        },
        created() {
            this.getSearchResult()
        },
        methods: {
            onChangeSearchType() {
                this.sales = [];
                if (this.searchType == 'quantity') {
                    this.getProducts();
                } else if (this.searchType == 'user') {
                    this.getUsers();
                } else if (this.searchType == 'category') {
                    this.getCategories();
                } else if (this.searchType == 'customer') {
                    this.getCustomers();
                    this.getProducts();
                    this.getBrands();
                    this.getCategories();
                    this.selectedProduct = null;
                    this.selectedBrand = null;
                    this.selectedCategory = null;
                } else if (this.searchType == 'employee') {
                    this.getEmployees();
                } else if (this.searchType == 'area') {
                    this.getAreas();
                } else if (this.searchType == 'brand') {
                    this.getBrands();
                }
            },
            getAreas() {
                axios.get('/get_districts')
                    .then(res => {
                        this.areas = res.data;
                    })
            },
            getProducts() {
                axios.get('/get_products').then(res => {
                    this.products = res.data;
                })
            },
            getCustomers() {
                axios.get('/get_customers').then(res => {
                    this.customers = res.data;
                })
            },
            getEmployees() {
                axios.get('/get_employees').then(res => {
                    this.employees = res.data;
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
            getBrands() {
                axios.get('/get_brands')
                    .then(res => {
                        this.brands = res.data;
                    })
            },
            getSearchResult() {
                if (this.searchType != 'customer') {
                    this.selectedCustomer = null;
                }

                if (this.searchType != 'area') {
                    this.selectedArea = null;
                }

                if (this.searchType != 'employee') {
                    this.selectedEmployee = null;
                }

                if (this.searchType != 'quantity' && this.searchType != 'customer') {
                    this.selectedProduct = null;
                }

                if (this.searchType != 'category' && this.searchType != 'customer') {
                    this.selectedCategory = null;
                }

                if (this.searchType != "brand" && this.searchType != 'customer') {
                    this.selectedBrand = null;
                }

                if (this.searchTypesForRecord.includes(this.searchType)) {
                    this.getSalesRecord();
                } else {
                    this.getSaleDetails();
                }

            },
            getSalesRecord() {

                const dateFrom = new Date();
                dateFrom.setDate(dateFrom.getDate() - 30);

                let filter = {
                    userFullName: this.selectedUser == null || this.selectedUser.FullName == '' ? '' : this.selectedUser.FullName,
                    customerId: this.selectedCustomer == null || this.selectedCustomer.Customer_SlNo == '' ? '' : this.selectedCustomer.Customer_SlNo,
                    areaId: this.selectedArea == null || this.selectedArea.District_SlNo == '' ? '' : this.selectedArea.District_SlNo,
                    employeeId: this.selectedEmployee == null || this.selectedEmployee.Employee_SlNo == '' ? '' : this.selectedEmployee.Employee_SlNo,
                    dateFrom: this.dateFrom != null ? this.dateFrom : dateFrom,
                    dateTo: this.dateTo
                }

                let url = '/get_sales';
                if (this.recordType == 'with_details') {
                    url = '/get_sales_record';
                }

                axios.post(url, filter)
                    .then(res => {
                        if (this.recordType == 'with_details') {
                            this.sales = res.data;
                        } else {
                            this.sales = res.data.sales;
                        }
                    })
                    .catch(error => {
                        if (error.response) {
                            alert(`${error.response.status}, ${error.response.statusText}`);
                        }
                    })
            },
            getSaleDetails() {
                let filter = {
                    categoryId: this.selectedCategory == null || this.selectedCategory.ProductCategory_SlNo == '' ? '' : this.selectedCategory.ProductCategory_SlNo,
                    productId: this.selectedProduct == null || this.selectedProduct.Product_SlNo == '' ? '' : this.selectedProduct.Product_SlNo,
                    brandId: this.selectedBrand == null || this.selectedBrand.brand_SiNo == '' ? '' : this.selectedBrand.brand_SiNo,
                    dateFrom: this.dateFrom,
                    dateTo: this.dateTo
                }

                axios.post('/get_saledetails', filter)
                    .then(res => {
                        let sales = res.data;

                        if (this.selectedProduct == null) {
                            sales = _.chain(sales)
                                .groupBy('ProductCategory_ID')
                                .map(sale => {
                                    return {
                                        category_name: sale[0].ProductCategory_Name,
                                        products: _.chain(sale)
                                            .groupBy('Product_IDNo')
                                            .map(product => {
                                                return {
                                                    product_code: product[0].Product_Code,
                                                    product_name: product[0].Product_Name,
                                                    brand_name: product[0].brand_name,
                                                    quantity: _.sumBy(product, item => Number(item.SaleDetails_TotalQuantity)),
                                                    total: _.sumBy(product, item => Number(item.SaleDetails_TotalAmount))
                                                }
                                            })
                                            .value()
                                    }
                                })
                                .value();
                        }
                        this.sales = sales;
                    })
                    .catch(error => {
                        if (error.response) {
                            alert(`${error.response.status}, ${error.response.statusText}`);
                        }
                    })
            },
            deleteSale(saleId) {
                let deleteConf = confirm('Are you sure?');
                if (deleteConf == false) {
                    return;
                }
                axios.post('/delete_sales', {
                        saleId: saleId
                    })
                    .then(res => {
                        let r = res.data;
                        alert(r.message);
                        if (r.success) {
                            this.getSalesRecord();
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
                    dateText = `Statement from <strong>${this.dateFrom}</strong> to <strong>${this.dateTo}</strong>`;
                }

                let userText = '';
                if (this.selectedUser != null && this.selectedUser.FullName != '' && this.searchType == 'user') {
                    userText = `<strong>Sold by: </strong> ${this.selectedUser.FullName}`;
                }

                let customerText = '';
                if (this.selectedCustomer != null && this.selectedCustomer.Customer_SlNo != '' && this.searchType == 'customer') {
                    customerText = `<strong>Customer: </strong> ${this.selectedCustomer.Customer_Name}<br>`;
                }

                let employeeText = '';
                if (this.selectedEmployee != null && this.selectedEmployee.Employee_SlNo != '' && this.searchType == 'employee') {
                    employeeText = `<strong>Employee: </strong> ${this.selectedEmployee.Employee_Name}<br>`;
                }

                let productText = '';
                if (this.selectedProduct != null && this.selectedProduct.Product_SlNo != '' && this.searchType == 'quantity') {
                    productText = `<strong>Product: </strong> ${this.selectedProduct.Product_Name}`;
                }

                let categoryText = '';
                if (this.selectedCategory != null && this.selectedCategory.ProductCategory_SlNo != '' && this.searchType == 'category') {
                    categoryText = `<strong>Category: </strong> ${this.selectedCategory.ProductCategory_Name}`;
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
								${userText} ${customerText} ${employeeText} ${productText} ${categoryText}
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