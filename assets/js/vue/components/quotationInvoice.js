const quotationInvoice = Vue.component("quotation-invoice", {
  template: `
        <div>
            <div class="row">
                <div class="col-xs-12">
                    <a href="" v-on:click.prevent="print"><i class="fa fa-print"></i> Print</a>
                </div>
            </div>
            
            <div id="invoiceContent">
                <div class="row">
                    <div  class="col-xs-12 text-center">
                        <div _h098asdh>
                            Quotation
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-8">
                        <strong>Customer Name:</strong> {{ quotation.SaleMaster_customer_name }}<br>
                        <strong>Customer Address:</strong> {{ quotation.SaleMaster_customer_address }}<br>
                        <strong>Customer Mobile:</strong> {{ quotation.SaleMaster_customer_mobile }}
                    </div>
                    <div class="col-xs-4 text-right">
                        <strong>Created by:</strong> {{ quotation.AddBy }}<br>
                        <strong>Quotation No.:</strong> {{ quotation.SaleMaster_InvoiceNo }}<br>
                        <strong>Date:</strong> {{ quotation.SaleMaster_SaleDate }} {{ moment(quotation.AddTime).format('h:mm a') }} <br>
                        <strong>Payment Term:</strong> {{ quotation.payment_term }}
                    </div>
                </div>
                <div class="row">
                   <div _d9283dsc> 
                   </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <table _a584de>
                            <thead>
                                <tr>
                                    <td>Sl.</td>
                                    <td>Product</td>
                                    <td>Unit Price</td>
                                    <td>Net Price</td>
                                    <td style="width:10%;">G / W</td>
                                    <td>Qnty</td>
                                    <td>Discount</td>
                                    <td>Total</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(product, sl) in cart">
                                    <td>{{ sl + 1 }}</td>
                                    <td>{{ product.Product_Name }}</td>
                                    <td>{{ product.SaleDetails_Rate }}</td>
                                    <td>{{ parseFloat(product.SaleDetails_Rate - product.SaleDetails_Discount).toFixed(2) }}</td>
                                    <td>G - {{ product.Guarantee }} days <br>
                                        W - {{ product.Warranty }} days</td>
                                    <td>{{ product.SaleDetails_TotalQuantity }}</td>
                                    <td>{{ product.SaleDetails_Discount }}</td>
                                    <td align="right">{{ product.SaleDetails_TotalAmount }}</td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="text-right">Total Qty</td>
                                    <td class="text-center">{{totalQty}}</td>
                                    <td colspan="2"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6 col-xs-offset-6">
                        <table _t92sadbc2>
                            <tr>
                                <td><strong>Sub Total:</strong></td>
                                <td style="text-align:right">{{ quotation.SaleMaster_SubTotalAmount }}</td>
                            </tr>
                            <tr>
                                <td><strong>VAT:</strong></td>
                                <td style="text-align:right">{{ quotation.SaleMaster_TaxAmount }}</td>
                            </tr>
                            <tr>
                                <td><strong>Discount:</strong></td>
                                <td style="text-align:right">{{ quotation.SaleMaster_TotalDiscountAmount }}</td>
                            </tr>
                            <tr><td colspan="2" style="border-bottom: 1px solid #ccc"></td></tr>
                            <tr>
                                <td><strong>Total:</strong></td>
                                <td style="text-align:right">{{ quotation.SaleMaster_TotalSaleAmount }}</td>
                            </tr>
                        </table>

                        
                    </div>

                    
                </div>
                <p style="margin-top:28px">Note : {{ quotation.note}}</p>
            </div>
            
        </div>
    `,
  props: ["quotation_id"],
  data() {
    return {
      quotation: {
        SaleMaster_InvoiceNo: null,
        SaleMaster_customer_name: null,
        SaleMaster_customer_mobile: null,
        SaleMaster_customer_address: null,
        SaleMaster_SaleDate: null,
        SaleMaster_TotalSaleAmount: null,
        SaleMaster_TotalDiscountAmount: null,
        SaleMaster_TotalQuantity: null,
        SaleMaster_TaxAmount: null,
        SaleMaster_SubTotalAmount: null,
        AddBy: null,
      },
      cart: [],
      style: null,
      companyProfile: null,
      currentBranch: null,
    };
  },
  created() {
    this.setStyle();
    this.getQuotations();
    this.getCompanyProfile();
    this.getCurrentBranch();
  },

  computed: {
    totalQty() {
      let total = 0;
      this.cart.forEach((qty) => {
        total += parseFloat(qty.SaleDetails_TotalQuantity);
      });

      return total;
    },
  },

  methods: {
    getQuotations() {
      axios
        .post("/get_quotations", { quotationId: this.quotation_id })
        .then((res) => {
          this.quotation = res.data.quotations[0];
          this.cart = res.data.quotationDetails;
        });
    },
    getCompanyProfile() {
      axios.get("/get_company_profile").then((res) => {
        this.companyProfile = res.data;
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
            `;
      document.head.appendChild(this.style);
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
        
                    </div>
                    <div class="container">
                        <div class="row">
                            <div class="col-xs-12">
                                ${invoiceContent}
                            </div>
                        </div>
                    </div>
                    <div class="container" style="position:fixed;bottom:15px;width:100%;">
                        <div class="row" style="border-bottom:1px solid #ccc;margin-bottom:5px;padding-bottom:6px;">
                            <div class="col-xs-6">
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
                                )}, Printed by: ${this.quotation.AddBy}
                            </div>
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
  },
});
