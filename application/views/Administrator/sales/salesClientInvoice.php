<div id="invoice">
    <div class="row">
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
                            Sales Invoice
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
                                    <td style="width:10%;">G / W</td>
                                    <td>Qnty</td>
                                    <td>Unit Price</td>
                                    <td>Discount</td>
                                    <td>Total</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(product, ind) in cart">
                                    <td>{{ ind + 1 }}</td>
                                    <td>{{ product.Product_Name }}
                                        <br><span
                                            v-for="(IMEI, ind) in product.imei.filter(s => s.sales_details_id == product.SaleDetails_SlNo) ">
                                            {{IMEI.ps_imei_number}} <span>,</span>
                                        </span>
                                    </td>
                                    <td>G - {{ product.Guarantee }} days <br>
                                        W - {{ product.Warranty }} days</td>
                                    <td>
                                        {{ product.SaleDetails_TotalQuantity }}
                                    </td>
                                    <td>{{ product.SaleDetails_Rate }}</td>
                                    <td>{{ product.SaleDetails_Discount }}</td>
                                    <td align="right">{{ product.SaleDetails_TotalAmount }}</td>
                                </tr>

                                <tr>
                                    <td></td>
                                    <td colspan="2">Total : </td>
                                    <td>{{ cart.reduce((pre,next)=>{  return pre+ parseFloat(next.SaleDetails_TotalQuantity) },0) }}
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>

                            </tbody>
                        </table>
                    </div>

                    <div class="col-xs-12">
                        <span> Note: </span> {{ sales.SaleMaster_Description }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6">
                        <br>
                        <table class="pull-left">
                            <tr>
                                <td><strong>Previous Due:</strong></td>

                                <td style="text-align:right">
                                    {{ sales.SaleMaster_Previous_Due == null ? '0.00' : sales.SaleMaster_Previous_Due  }}
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Current Due:</strong></td>

                                <td style="text-align:right">
                                    {{ sales.SaleMaster_DueAmount == null ? '0.00' : sales.SaleMaster_DueAmount  }}</td>
                            </tr>
                            <tr>
                                <td colspan="2" style="border-bottom: 1px solid #484848;"></td>
                            </tr>
                            <tr>
                                <td><strong>Total Due:</strong></td>

                                <td style="text-align:right">
                                    {{ (parseFloat(sales.SaleMaster_Previous_Due) + parseFloat(sales.SaleMaster_DueAmount == null ? 0.00 : sales.SaleMaster_DueAmount)).toFixed(2) }}
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-xs-6">
                        <table _t92sadbc2>
                            <tr>
                                <td><strong>Sub Total:</strong></td>
                                <td style="text-align:right">{{ sales.SaleMaster_SubTotalAmount }}</td>
                            </tr>
                            <tr>
                                <td><strong>VAT:</strong></td>
                                <td style="text-align:right">{{ sales.SaleMaster_TaxAmount }}</td>
                            </tr>
                            <tr>
                                <td><strong>Discount:</strong></td>
                                <td style="text-align:right">{{ sales.SaleMaster_TotalDiscountAmount }}</td>
                            </tr>
                            <tr>
                                <td><strong>Transport Cost:</strong></td>
                                <td style="text-align:right">{{ sales.SaleMaster_Freight }}</td>
                            </tr>
                            <tr>
                                <td colspan="2" style="border-bottom: 1px solid #484848"></td>
                            </tr>
                            <tr>
                                <td><strong>Total:</strong></td>
                                <td style="text-align:right">{{ sales.SaleMaster_TotalSaleAmount }}</td>
                            </tr>
                            <!--<tr>
                                <td><strong>Paid:</strong></td>
                                <td style="text-align:right">{{ sales.SaleMaster_PaidAmount }}</td>
                            </tr> -->
                            <tr v-if="sales.cashPaid > 0">
                                <td><strong>cashPaid:</strong></td>
                                <td style="text-align:right">{{ sales.cashPaid }}</td>
                            </tr>
                            <tr v-if="sales.bankPaid > 0">
                                <td><strong>bankPaid:</strong></td>
                                <td style="text-align:right">{{ sales.bankPaid }}</td>
                            </tr>
                            <tr>
                                <td colspan="2" style="border-bottom: 1px solid #484848"></td>
                            </tr>
                            <tr>
                                <td><strong>Due:</strong></td>
                                <td style="text-align:right">{{ sales.SaleMaster_DueAmount }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <strong>In Word: </strong> {{ convertNumberToWords(sales.SaleMaster_TotalSaleAmount) }}<br><br>
                        <strong>Note: </strong>
                        <p style="white-space: pre-line">{{ sales.SaleMaster_Description }}</p>
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
const app = new Vue({
    el: '#invoice',
    data() {
        return {
            sales: {
                SaleMaster_SlNo: parseInt('<?php echo $saleId;?>'),
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
    async created() {
        this.setStyle();
        await this.getSales();
        this.getCompanyProfile();
    },
    methods: {
        async getSales() {
            await axios.post('/get_sales_invoice', {
                salesId: this.sales.SaleMaster_SlNo
            }).then(res => {
                this.sales = res.data.sales[0];
                // this.cart.push(res.data.saleDetails)
                this.cart = res.data.saleDetails;
            })
        },
        getCompanyProfile() {
            axios.get('/get_company_profile').then(res => {
                this.companyProfile = res.data;
            })
        },

        getCurrentBranch() {
            axios.get("/get_current_branch").then((res) => {
                this.currentBranch = res.data;
            });
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
                    font-size:20px;
                    margin-bottom:15px;
                    padding: 5px;
                }
                div[_d9283dsc]{
                    padding-bottom:25px;
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

        convertNumberToWords(amountToWord) {
            var words = new Array();
            words[0] = "";
            words[1] = "One";
            words[2] = "Two";
            words[3] = "Three";
            words[4] = "Four";
            words[5] = "Five";
            words[6] = "Six";
            words[7] = "Seven";
            words[8] = "Eight";
            words[9] = "Nine";
            words[10] = "Ten";
            words[11] = "Eleven";
            words[12] = "Twelve";
            words[13] = "Thirteen";
            words[14] = "Fourteen";
            words[15] = "Fifteen";
            words[16] = "Sixteen";
            words[17] = "Seventeen";
            words[18] = "Eighteen";
            words[19] = "Nineteen";
            words[20] = "Twenty";
            words[30] = "Thirty";
            words[40] = "Forty";
            words[50] = "Fifty";
            words[60] = "Sixty";
            words[70] = "Seventy";
            words[80] = "Eighty";
            words[90] = "Ninety";
            amount = amountToWord == null ? "0.00" : amountToWord.toString();
            var atemp = amount.split(".");
            var number = atemp[0].split(",").join("");
            var n_length = number.length;
            var words_string = "";
            if (n_length <= 9) {
                var n_array = new Array(0, 0, 0, 0, 0, 0, 0, 0, 0);
                var received_n_array = new Array();
                for (var i = 0; i < n_length; i++) {
                    received_n_array[i] = number.substr(i, 1);
                }
                for (var i = 9 - n_length, j = 0; i < 9; i++, j++) {
                    n_array[i] = received_n_array[j];
                }
                for (var i = 0, j = 1; i < 9; i++, j++) {
                    if (i == 0 || i == 2 || i == 4 || i == 7) {
                        if (n_array[i] == 1) {
                            n_array[j] = 10 + parseInt(n_array[j]);
                            n_array[i] = 0;
                        }
                    }
                }
                value = "";
                for (var i = 0; i < 9; i++) {
                    if (i == 0 || i == 2 || i == 4 || i == 7) {
                        value = n_array[i] * 10;
                    } else {
                        value = n_array[i];
                    }
                    if (value != 0) {
                        words_string += words[value] + " ";
                    }
                    if (
                        (i == 1 && value != 0) ||
                        (i == 0 && value != 0 && n_array[i + 1] == 0)
                    ) {
                        words_string += "Crores ";
                    }
                    if (
                        (i == 3 && value != 0) ||
                        (i == 2 && value != 0 && n_array[i + 1] == 0)
                    ) {
                        words_string += "Lakhs ";
                    }
                    if (
                        (i == 5 && value != 0) ||
                        (i == 4 && value != 0 && n_array[i + 1] == 0)
                    ) {
                        words_string += "Thousand ";
                    }
                    if (
                        i == 6 &&
                        value != 0 &&
                        n_array[i + 1] != 0 &&
                        n_array[i + 2] != 0
                    ) {
                        words_string += "Hundred and ";
                    } else if (i == 6 && value != 0) {
                        words_string += "Hundred ";
                    }
                }
                words_string = words_string.split("  ").join(" ");
            }
            return words_string + " only";
        },
        async print() {
            let invoiceContent = document.querySelector("#invoiceContent").innerHTML;
            let printWindow = window.open(
                "",
                "PRINT",
                `width=${screen.width}, height=${screen.height}, left=0, top=0`
            );


            printWindow.document.write(`
                <!DOCTYPE html>
                <html lang="en">
                  <head>
                      <meta charset="UTF-8">
                      <meta name="viewport" content="width=device-width, initial-scale=1.0">
                      <meta http-equiv="X-UA-Compatible" content="ie=edge">
                      <title>Invoice</title>
                      <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
                      <style>
                          body, table{
                              font-size: 13px;
                              margin:0px auto !important;
                          }

                  .logo{
                    width: 30%;
                    float:left;
                    overflow:hidden;
                }

                .logo img{
                    margin-left:55px;
                }

                .heading {
                    width: 70%;
                    float:left;
                    padding-top:25px;
                }

                  .heading h2
                  {
                    font-size: 27px;
                  }
                      </style>
                  </head>
                  <body>
                      <div class="container">
                        <div class="row">
                            <div class="logo">
                                <img src="/uploads/company_profile_thum/aall_red_png.png" style="height:138px; width:170px;"/>
                            </div>
                            <div class="heading">
                                <h2> ALL SKY TECH LTD</h2>
                                <span> 
                                    Mobile: +8801767695233
                                    Email: aallskytech@gmail.com <br/>
                                    38/C Mayakanon Road, Bashabo, Shabujbag, Bangladesh.
                                </span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <div style="border-bottom: 4px double #454545;margin-top:7px;margin-bottom:7px;"></div>
                            </div>
                        </div>
                        </div>
                        <div class="container">
                            <div class="row">
                                <div class="col-xs-12">
                                    ${invoiceContent}
                                </div>
                            </div>
                        </div>
                        <div class="container" style="position:sticky-top;bottom:15px;width:100%;margin-top:50px;">
                            <div class="row" style="border-bottom:1px solid #484848;margin-bottom:5px;padding-bottom:6px;">
                                <div class="col-xs-6">
                                    <span style="text-decoration:overline;">Received by</span><br><br>
                                </div>
                                <div class="col-xs-6 text-right">
                                    <span style="text-decoration:overline;">Authorized Signature</span>
                                </div>
                                <div class="col-xs-12 text-center">
                                    ** THANK YOU FOR YOUR BUSINESS **
                                </div>
                            </div>

                            <div class="row" style="font-size:12px;">
                                <div class="col-xs-12 text-center">
                                    Print Date: ${moment().format(
                                      "DD-MM-YYYY h:mm a"
                                    )}, Printed by: ${this.sales.AddBy}
                                </div>
                              <!--  <div class="col-xs-6 text-right">
                                    Developed by: Link-Up Technologoy, Contact no: 01911978897
                                </div> -->
                            </div>
                        </div>
                    </body>
                </html>
                `);
            let invoiceStyle = printWindow.document.createElement("style");
            invoiceStyle.innerHTML = this.style.innerHTML;
            printWindow.document.head.appendChild(invoiceStyle);
            printWindow.moveTo(0, 0);

            printWindow.focus();
            await new Promise((resolve) => setTimeout(resolve, 1000));
            printWindow.print();
            printWindow.close();
        },
    }
})
</script>