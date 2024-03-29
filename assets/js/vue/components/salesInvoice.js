const salesInvoice = Vue.component("sales-invoice", {
  template: `
        <div>
            <div class="row">
                <div class="col-xs-12">
                    <a href="" v-on:click.prevent="print"><i class="fa fa-print"></i> Print</a>
                </div>
            </div>
            <div id="invoiceContent">
                <div class="row">
                    <div class="col-xs-12 text-center">
                        <div _h098asdh>
                            SALES INVOICE
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-7">
                        <strong>Customer Id:</strong> {{ sales.Customer_Code }}<br>
                        <strong>Customer Name:</strong> {{ sales.Customer_Name }}<br>
                        <strong>Customer Address:</strong> {{ sales.Customer_Address }}<br>
                        <strong>Customer Mobile:</strong> {{ sales.Customer_Mobile }}
                    </div>
                    <div class="col-xs-5 text-right">
                        <strong>Sales by:</strong> {{ sales.AddBy }}<br>
                        <strong>Invoice No.:</strong> {{ sales.SaleMaster_InvoiceNo }}<br>
                        <strong>Sales Date:</strong> {{ sales.SaleMaster_SaleDate }} {{ sales.AddTime | formatDateTime('h:mm a') }}<br>
                        
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
                                    <td style="width:50%;">Description</td>
                                    <td style="width:10%;">G / W</td>
                                    <td>Qnty</td>
                                    <td>Unit Price</td>
                                    <td>Discount</td>
                                    <td>Total</td>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-for="(product, sl) in cart">
                                    <tr v-if="product.imei.length > 99" style="border-bottom:none;">
                                        <td>{{ sl + 1 }}</td>
                                        
                                        <td v-if="product.imei.length > 0" id="fontSize">{{ product.Product_Name }} ({{ product.varient_name }})<br>
                                        ({{ product.imei.slice(0,99).map(obj => obj.ps_imei_number).join(', ') }})
                                        </td>
                                        <td>G - {{ product.Guarantee }} days <br>
                                        W - {{ product.Warranty }} days</td>
                                        <td>{{ product.SaleDetails_TotalQuantity }}</td>
                                        <td>{{ product.SaleDetails_Rate }}</td>
                                        <td>{{ product.SaleDetails_Discount }}</td>
                                        <td align="right">{{ product.SaleDetails_TotalAmount }}</td>
                                    </tr>
                                    <tr v-if="product.imei.length > 99">
                                        <td></td>                                        
                                        <td>({{ product.imei.slice(99,10000).map(obj => obj.ps_imei_number).join(', ') }})</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td align="right"></td>
                                    </tr>

                                    <tr v-if="product.imei.length < 140">
                                        <td>{{ sl + 1 }}</td>
                                        
                                        <td v-if="product.imei.length > 0" id="fontSize">{{ product.Product_Name }} ({{ product.varient_name }})<br>
                                        ({{ product.imei.map(obj => obj.ps_imei_number).join(', ') }})
                                        </td>

                                        <td v-else>{{ product.Product_Name }} ({{ product.varient_name }})</td>

                                        <td>G - {{ product.Guarantee }} days <br>
                                        W - {{ product.Warranty }} days</td>
                                        <td>{{ product.SaleDetails_TotalQuantity }}</td>
                                        <td>{{ product.SaleDetails_Rate }}</td>
                                        <td>{{ product.SaleDetails_Discount }}</td>
                                        <td align="right">{{ product.SaleDetails_TotalAmount }}</td>
                                    </tr>

                                    <tr> 
                                        <td></td>
                                        <td colspan="2">Total : </td>
                                        <td>{{ cart.reduce((pre,next)=>{  return pre+ parseFloat(next.SaleDetails_TotalQuantity) },0) }}</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>   
                                </template>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="6" style="border:none;"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6">
                        <br>
                        <table class="pull-left">
                            <tr>
                                <td><strong>Previous Due:</strong></td>
                                
                                <td style="text-align:right">{{ sales.SaleMaster_Previous_Due == null ? '0.00' : sales.SaleMaster_Previous_Due  }}</td>
                            </tr>
                            <tr>
                                <td><strong>Current Due:</strong></td>
                                
                                <td style="text-align:right">{{ sales.SaleMaster_DueAmount == null ? '0.00' : sales.SaleMaster_DueAmount  }}</td>
                            </tr>
                            <tr>
                                <td colspan="2" style="border-bottom: 1px solid #484848;"></td>
                            </tr>
                            <tr>
                                <td><strong>Total Due:</strong></td>
                                
                                <td style="text-align:right">{{ (parseFloat(sales.SaleMaster_Previous_Due) + parseFloat(sales.SaleMaster_DueAmount == null ? 0.00 : sales.SaleMaster_DueAmount)).toFixed(2) }}</td>
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
                            <tr><td colspan="2" style="border-bottom: 1px solid #484848"></td></tr>
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
                            <tr><td colspan="2" style="border-bottom: 1px solid #484848"></td></tr>
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

                <div class="row">
                    <div class="col-xs-12" style="border:1px solid green; border-radius:5px; padding:5px; width:50%;">
                        <strong style="font-size:18px; font-weight:bold; color:green;"> Invoice Link :- </strong> <span style="font-size:14px; font-weight:bold; color:ciyan;"> https://pos.aallskytech.com/invoice/{{ sales.SaleMaster_SlNo }} </span>
                    </div>
                </div>
            </div>
        </div>
    `,
  props: ["sales_id"],
  data() {
    return {
      sales: {
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
        guaranty_warranty: null,
        AddBy: null,
      },
      cart: [],
      style: null,
      companyProfile: null,
      currentBranch: null,
    };
  },
  filters: {
    formatDateTime(dt, format) {
      return dt == "" || dt == null ? "" : moment(dt).format(format);
    },
  },
  created() {
    this.setStyle();
    this.getSales();
    this.getCurrentBranch();
  },
  methods: {
    getSales() {
      axios.post("/get_sales", { salesId: this.sales_id }).then((res) => {
        this.sales = res.data.sales[0];
        this.cart = res.data.saleDetails;
      });
    },
    getCurrentBranch() {
      axios.get("/get_current_branch").then((res) => {
        this.currentBranch = res.data;
      });
    },
    setStyle() {
      this.style = document.createElement("style");
      this.style.innerHTML = `
                div[_h098asdh]{
                    /*background-color:#e0e0e0;*/
                    font-weight: bold;
                    font-size:30px;
                    margin-bottom:15px;
                    padding: 5px;
                    // border-top: 1px dotted #454545;
                    // border-bottom: 1px dotted #454545;
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
                    border: 1px solid #484848;
                    vertical-align: baseline;
                }
                table[_t92sadbc2]{
                    width: 100%;
                }
                table[_t92sadbc2] td{
                    padding: 2px;
                }
                @media print() {
                    #fontSize {
                        font-size: 8px !important;
                    }
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
      if (this.currentBranch.print_type == "3") {
        printWindow.document.write(`
                    <html>
                        <head>
                            <title>Invoice</title>
                            <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
                            <style>
                                body, table{
                                    font-size:11px;
                                }
                            </style>
                        </head>
                        <body>
                            <div style="text-align:center;">
                                <img src="/uploads/company_profile_thum/${this.currentBranch.Company_Logo_thum}" alt="Logo" style="height:180px;margin:0px;" /><br>
                                <strong style="font-size:18px;">${this.currentBranch.Company_Name}</strong><br>
                                <p style="white-space:pre-line;">${this.currentBranch.Repot_Heading}</p>
                            </div>
                            ${invoiceContent}
                        </body>
                    </html>
                `);
      } else if (this.currentBranch.print_type == "2") {
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
                            html, body{
                                margin:0px auto !important;
                            }
                            body, table{
                                font-size: 13px;
                            }
                        </style>
                    </head>
                    <body>
                        <div class="row">
                            <div class="col-xs-2"><img src="/uploads/company_profile_thum/${
                              this.currentBranch.Company_Logo_thum
                            }" alt="Logo" style="height:80px;" /></div>
                            <div class="col-xs-10" style="padding-top:20px;">
                                <strong style="font-size:18px;">${
                                  this.currentBranch.Company_Name
                                }</strong><br>
                                <p style="white-space:pre-line;">${
                                  this.currentBranch.Repot_Heading
                                }</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <div style="border-bottom: 4px double #454545;margin-top:7px;margin-bottom:7px;"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                ${invoiceContent}
                            </div>

                            
                        </div>

                        <div class="container" style="position:fixed;bottom:15px;width:100%;">
                            <div class="row" style="border-bottom:1px solid #ccc;margin-bottom:5px;padding-bottom:6px;">
                                <div class="col-xs-6">
                                    <span style="text-decoration:overline;">Received by</span><br><br>
                                    ** THANK YOU FOR YOUR BUSINESS **
                                </div>
                                <div class="col-xs-6 text-right">
                                    <span style="text-decoration:overline;">Authorized Signature</span>
                                </div>
                            </div>

                            <div class="row" style="font-size:12px;">
                                <div class="col-xs-6">
                                    Print Date: ${moment().format(
                                      "DD-MM-YYYY h:mm a"
                                    )}, Printed by: ${this.sales.AddBy}
                                </div>
                                <div class="col-xs-6 text-right">
                                    Developed by: Link-Up Technologoy, Contact no: 01911978897
                                </div>
                            </div>
                        </div>
                    </body>
                    </html>
                `);
      } else {
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
                      </style>
                  </head>
                  <body>
                      <div class="container">
                        <div class="row">
                            <div class="logo"><img src="/uploads/company_profile_thum/${
                              this.currentBranch.Company_Logo_thum
                            }" alt="Logo" style="height:138px; width:170px;" /></div>
                            <div class="heading">
                            <span style="font-size:18px;">AALL SKY TECH LTD</span><br>
                                <span style="font-size:18px;">${
                                  this.currentBranch.Company_Name
                                }</span><br>
                                ${this.currentBranch.Repot_Heading}
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
                                    <div class="container" style="margin-top:80px;width:100%;">
                                        <div class="row" style="margin-bottom:5px;padding-bottom:6px;">
                                            <div class="col-xs-6">
                                                <span style="text-decoration:overline;">Received by</span>                        
                                            </div>
                                            <div class="col-xs-6 text-right">
                                                <span style="text-decoration:overline;">Authorized Signature</span>
                                            </div>
                                        </div>
                                        <div class="row" style="font-size:12px;border-top:1px solid #ccc;">
                                            <div class="col-xs-6">                        
                                            ** THANK YOU FOR YOUR BUSINESS **
                                            </div>
                                            <div class="col-xs-6 text-right">
                                            Print Date: ${moment().format(
                                              "DD-MM-YYYY h:mm a"
                                            )}, Printed by: ${this.sales.AddBy}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>                        
                    </body>
                </html>
                `);
      }
      let invoiceStyle = printWindow.document.createElement("style");
      invoiceStyle.innerHTML = this.style.innerHTML;
      printWindow.document.head.appendChild(invoiceStyle);
      printWindow.moveTo(0, 0);

      printWindow.focus();
      await new Promise((resolve) => setTimeout(resolve, 1000));
      printWindow.print();
      printWindow.close();
    },
  },
});
