<div id="chalan">
    <div class="row" style="display:none;" v-bind:style="{display: cart.length > 0 ? '' : 'none'}">
        <div class="col-md-8 col-md-offset-2">
            <div class="row">
                <div class="col-xs-12">
                    <a href="" v-on:click.prevent="print"><i class="fa fa-print"></i> Print</a>
                </div>
            </div>

            <div id="invoiceContent">
                <div class="row">
                    <div class="col-xs-12 text-center">
                        <div _h098asdh>
                            Delivery Chalan
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-8">
                        <strong>Customer Id:</strong> {{ sales.Customer_Code }}<br>
                        <strong>Customer Name:</strong> {{ sales.Customer_Name }}<br>
                        <strong>Customer Address:</strong> {{ sales.Customer_Address }}<br>
                        <strong>Customer Mobile:</strong> {{ sales.Customer_Mobile }}
                    </div>
                    <div class="col-xs-4 text-right">
                        <strong>Sales by:</strong> {{ sales.AddBy }}<br>
                        <strong>Invoice No.:</strong> {{ sales.SaleMaster_InvoiceNo }}<br>
                        <strong>Sales Date:</strong> {{ sales.SaleMaster_SaleDate }}
                        {{ formatDateTime(sales.AddTime, 'h:mm a') }} <br>
                        <strong>Reference:</strong> {{ sales.reference }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div _d9283dsc></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <table _a584de>
                            <thead>
                                <tr>
                                    <td>Sl.</td>
                                    <td>Description</td>
                                    <td>Qnty</td>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- <tr v-for="(product, sl) in cart">
                                    <td>{{ sl + 1 }}</td>
                                    <td>{{ product.Product_Name }}
                                        <br><span v-for="(IMEI, ind) in product.imei.filter(sl => sl.sales_details_id == product.SaleDetails_SlNo) ">
                                            {{IMEI.ps_imei_number}} <span>,</span>
                                        </span>
                                    </td>
                                    <td>
                                        {{ product.SaleDetails_TotalQuantity }}
                                    </td>
                                </tr> -->

                                <template v-for="(product, sl) in cart">
                                    <tr v-if="product.imei.length > 140">
                                        <td>{{ sl + 1 }}</td>
                                        <td v-if="product.imei.length > 0">{{ product.Product_Name }} <br>
                                            ({{ product.imei.slice(0,140).map(obj => obj.ps_imei_number).join(', ') }}
                                        </td>
                                        <td v-else>{{ product.Product_Name }}</td>
                                        <td>{{ product.SaleDetails_TotalQuantity }}</td>
                                    </tr>
                                    <tr v-if="product.imei.length > 140">
                                        <td></td>
                                        <td>{{ product.imei.slice(141,10000).map(obj => obj.ps_imei_number).join(', ') }})
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr v-if="product.imei.length < 140">
                                        <td>{{ sl + 1 }}</td>
                                        <td v-if="product.imei.length > 0">{{ product.Product_Name }} <br>
                                            ({{ product.imei.map(obj => obj.ps_imei_number).join(', ') }}
                                        </td>
                                        <td v-else>{{ product.Product_Name }}</td>
                                        <td>{{ product.SaleDetails_TotalQuantity }}</td>
                                    </tr>
                                </template>

                                <tr>
                                    <td></td>
                                    <td style="text-align:right">Total Qty : </td>
                                    <td>{{cart.reduce((prev,cur)=>{
				                  return prev+parseFloat(cur.SaleDetails_TotalQuantity)},0) }}</td>

                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-xs-12">
                        <span> Note: </span> {{ sales.SaleMaster_Description }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url(); ?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/vue-select.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/moment.min.js"></script>

<script>
    new Vue({
        el: '#chalan',
        data() {
            return {
                sales: {
                    SaleMaster_SlNo: parseInt('<?php echo $saleId; ?>'),
                    SaleMaster_InvoiceNo: null,
                    SalseCustomer_IDNo: null,
                    SaleMaster_SaleDate: null,
                    Customer_Name: null,
                    Customer_Address: null,
                    Customer_Mobile: null,
                    SaleMaster_TotalSaleAmount: null,
                    SaleMaster_TotalDiscountAmount: null,
                    SaleMaster_TaxAmount: null,
                    SaleMaster_Freight: null,
                    SaleMaster_SubTotalAmount: null,
                    SaleMaster_PaidAmount: null,
                    SaleMaster_DueAmount: null,
                    SaleMaster_Previous_Due: null,
                    SaleMaster_Description: null,
                    AddBy: null
                },
                cart: [],
                style: null,
                companyProfile: null,
                currentBranch: null
            }
        },
        created() {
            this.setStyle();
            this.getSales();
            this.getCompanyProfile();
            this.getCurrentBranch();
        },
        methods: {
            getSales() {
                axios.post('/get_sales', {
                    salesId: this.sales.SaleMaster_SlNo
                }).then(res => {
                    this.sales = res.data.sales[0];
                    this.cart = res.data.saleDetails;
                })
            },
            getCompanyProfile() {
                axios.get('/get_company_profile').then(res => {
                    this.companyProfile = res.data;
                })
            },
            getCurrentBranch() {
                axios.get('/get_current_branch').then(res => {
                    this.currentBranch = res.data;
                })
            },
            formatDateTime(datetime, format) {
                return moment(datetime).format(format);
            },
            setStyle() {
                this.style = document.createElement('style');
                this.style.innerHTML = `
                div[_h098asdh]{
                    background-color:#e0e0e0;
                    font-weight: bold;
                    font-size:15px;
                    margin-bottom:15px;
                    padding: 5px;
                }
                div[_d9283dsc]{
                    padding-bottom:25px;
                    border-bottom: 1px solid #ccc;
                    margin-bottom: 15px;
                }
                table[_a584de]{
                    width: 100%;
                    text-align:center;
                }
                table[_a584de] thead{
                    font-weight:bold;
                }
                table[_a584de] td{
                    padding: 3px;
                    border: 1px solid #ccc;
                }
                table[_t92sadbc2]{
                    width: 100%;
                }
                table[_t92sadbc2] td{
                    padding: 2px;
                }
            `;
                document.head.appendChild(this.style);
            },
            async print() {
                let reportContent = `
					<div class="container">
						<div class="row">
							<div class="col-xs-12">
								${document.querySelector('#invoiceContent').innerHTML}
							</div>
						</div>
					</div>
				`;

                var reportWindow = window.open('', 'PRINT', `height=${screen.height}, width=${screen.width}`);
                reportWindow.document.write(`
					<?php $this->load->view('Administrator/reports/reportHeader.php'); ?>
				`);

                reportWindow.document.body.innerHTML += reportContent;

                if (this.searchType == '' || this.searchType == 'user') {
                    let rows = reportWindow.document.querySelectorAll('.record-table tr');
                    rows.forEach(row => {
                        row.lastChild.remove();
                    })
                }

                let invoiceStyle = reportWindow.document.createElement('style');
                invoiceStyle.innerHTML = this.style.innerHTML;
                reportWindow.document.head.appendChild(invoiceStyle);

                reportWindow.focus();
                await new Promise(resolve => setTimeout(resolve, 1000));
                reportWindow.print();
                reportWindow.close();
            }
        }
    })
</script>