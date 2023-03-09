<style>
.v-select {
    margin-bottom: 5px;
    float: right;
    min-width: 200px;
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

#table {
    border-collapse: collapse;
    width: 100%;
}

#table td,
#table th {
    padding: 5px;
    border: 1px solid #909090;
}

#table th {
    text-align: center;
}

#table thead {
    background-color: #cbd6e7;
}
</style>
<div id="profitLoss">
    <div class="row" style="border-bottom: 1px solid #ccc;">
        <div class="col-md-12">
            <form class="form-inline" v-on:submit.prevent="getProfitLoss">
                <div class="form-group" style="margin-right: 15px;">
                    <label>Customer &nbsp;</label>
                    <v-select v-bind:options="customers" v-model="selectedCustomer" label="display_name"
                        placeholder="Select Customer"></v-select>
                </div>

                <div class="form-group">
                    <label>Date from </label>
                    <input type="date" class="form-control" v-model="filter.dateFrom">
                </div>

                <div class="form-group">
                    <label>to </label>
                    <input type="date" class="form-control" v-model="filter.dateTo">
                </div>

                <div class="form-group">
                    <input type="submit" class="btn btn-info btn-xs" value="Search"
                        style="padding-top:0px;padding-bottom:0px;margin-top:-4px;">
                </div>
            </form>
        </div>
    </div>
    <div class="row" style="padding: 10px 0;display:none;"
        v-bind:style="{display: reportData.length > 0 ? '' : 'none'}">
        <div class="col-md-12">
            <a href="" @click.prevent="print"><i class="fa fa-print"></i> Print</a>
            <div style="float: right; display:inline;">
                <button v-on:click.prvent="pdf"
                    style="background-color: #9aef06;border: 1px solid #9aef06;border-radius: 4px;margin-right: 5px;">
                    PDF </button>
                <button onclick="fnExcelReport();"
                    style="background-color: rgb(140 239 232);border: 1px solid rgb(140 239 232);border-radius: 4px;">
                    EXPORT </button>
            </div>
        </div>
        <!-- <div class="col-md-6 text-right">
			<button class="btn btn-sm btn-danger" v-on:click.prvent="exportTableToPDF">Export To pdf</button>
			<button class="btn btn-sm btn-success" onclick="exportTableToCSV('profit-loss-list.csv')">Export To CSV</button>
		</div> -->
    </div>

    <div class="row" style="margin-top: 5px;">
        <div class="col-md-12">
            <div class="table-responsive" id="reportTable">
                <table id="table">
                    <thead>
                        <tr>
                            <th>Product Id</th>
                            <th>Product</th>
                            <th>Sold Quantity</th>
                            <th>Purchase Rate</th>
                            <th>Purchase Discount</th>
                            <th>Purchased Total</th>
                            <th>Sold Amount</th>
                            <th>Profit/Loss</th>
                        </tr>
                    </thead>
                    <tbody v-for="data in reportData" style="display:none;"
                        v-bind:style="{display: reportData.length > 0 ? '' : 'none'}">
                        <tr>
                            <td colspan="8" style="background-color: #e3eae7;">
                                <strong>Invoice: </strong> {{ data.SaleMaster_InvoiceNo }} |
                                <strong>Sales Date: </strong> {{ data.SaleMaster_SaleDate }} |
                                <strong>Customer: </strong> {{ data.Customer_Name }} |
                                <strong>Discount: </strong> {{ data.SaleMaster_TotalDiscountAmount }}
                                <strong>Refenence: </strong> {{ data.reference
								 }}
                            </td>
                        </tr>
                        <tr v-for="product in data.saleDetails">
                            <td>{{ product.Product_Code }}</td>
                            <td>{{ product.Product_Name }}</td>
                            <td style="text-align:right;">{{ product.SaleDetails_TotalQuantity }}</td>
                            <td style="text-align:right;">{{ parseFloat(product.Purchase_Rate).toFixed(2) }}</td>
                            <td style="text-align:right;">{{  parseFloat(product.discount).toFixed(2) }}</td>
                            <td style="text-align:right;">{{  parseFloat(product.purchased_amount).toFixed(2) }}</td>
                            <td style="text-align:right;">{{  parseFloat(product.SaleDetails_TotalAmount).toFixed(2) }}
                            </td>
                            <td style="text-align:right;">{{ parseFloat(product.profit_loss).toFixed(2) }}</td>
                        </tr>
                        <tr style="background-color: #f0f0f0;font-weight: bold;">
                            <td colspan="5" style="text-align:right;">Total</td>
                            <td style="text-align:right;">
                                {{ data.saleDetails.reduce((prev, cur) => { return prev + parseFloat(cur.purchased_amount) }, 0) }}
                            </td>
                            <td style="text-align:right;">
                                {{ data.saleDetails.reduce((prev, cur) => { return prev + parseFloat(cur.SaleDetails_TotalAmount) }, 0) }}
                            </td>
                            <td style="text-align:right;">
                                {{ data.saleDetails.reduce((prev, cur) => { return prev + parseFloat(cur.profit_loss) }, 0) }}
                            </td>
                        </tr>
                    </tbody>
                    <tfoot style="display:none;font-weight:bold;background-color:#e9dcdc;"
                        v-bind:style="{display: reportData.length > 0 ? '' : 'none'}">
                        <tr>
                            <td style="text-align:right;" colspan="5">Total Profit</td>
                            <td style="text-align:right;">
                                {{ 
									reportData.reduce((prev, cur) => { return prev + parseFloat(
										cur.saleDetails.reduce((p, c) => { return p + parseFloat(c.purchased_amount) }, 0)
									)}, 0).toFixed(2)
								}}
                            </td>
                            <td style="text-align:right;">
                                {{ 
									reportData.reduce((prev, cur) => { return prev + parseFloat(
										cur.saleDetails.reduce((p, c) => { return p + parseFloat(c.SaleDetails_TotalAmount) }, 0)
									)}, 0).toFixed(2)
								}}
                            </td>
                            <td style="text-align:right;">
                                {{ 
									totalProfit = reportData.reduce((prev, cur) => { return prev + parseFloat(
										cur.saleDetails.reduce((p, c) => { return p + parseFloat(c.profit_loss) }, 0)
									)}, 0).toFixed(2)
								}}
                            </td>
                        </tr>

                        <tr>
                            <td colspan="4" style="text-align:right;">Other Income (+)</td>
                            <td colspan="3"></td>
                            <td style="text-align:right;">{{ otherIncomeExpense.income | decimal }}</td>
                        </tr>

                        <tr>
                            <td colspan="4" style="text-align:right;">Total Discount (-)</td>
                            <td colspan="3"></td>
                            <td style="text-align:right;">
                                {{ totalDiscount = reportData.reduce((prev, cur) => { return prev + parseFloat(cur.SaleMaster_TotalDiscountAmount) }, 0).toFixed(2) }}
                            </td>
                        </tr>

                        <tr>
                            <td colspan="4" style="text-align:right;">Total Damaged (-)</td>
                            <td colspan="3"></td>
                            <td style="text-align:right;">{{ otherIncomeExpense.damaged_amount | decimal }}</td>
                        </tr>

                        <tr>
                            <td colspan="4" style="text-align:right;">Cash Transaction (-)</td>
                            <td colspan="3"></td>
                            <td style="text-align:right;">{{ otherIncomeExpense.expense | decimal }}</td>
                        </tr>

                        <tr>
                            <td colspan="4" style="text-align:right;">Employee Payment (-)</td>
                            <td colspan="3"></td>
                            <td style="text-align:right;">{{ otherIncomeExpense.employee_payment | decimal }}</td>
                        </tr>

                        <tr>
                            <td colspan="4" style="text-align:right;">Profit</td>
                            <td colspan="3"></td>
                            <td style="text-align:right;">
                                {{  ((parseFloat(totalProfit) + parseFloat(otherIncomeExpense.income)) - 
									(parseFloat(totalDiscount) + parseFloat(otherIncomeExpense.damaged_amount) + parseFloat(otherIncomeExpense.expense) + parseFloat(otherIncomeExpense.employee_payment))).toFixed(2) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url();?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/vue-select.min.js"></script>
<script src="<?php echo base_url();?>assets/js/moment.min.js"></script>
<script src="<?php echo base_url();?>assets/js/export-to-csv.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/vuetify/2.3.10/vuetify.min.js"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.6/jspdf.plugin.autotable.js"></script> -->
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
    el: '#profitLoss',
    data() {
        return {
            filter: {
                customer: null,
                dateFrom: moment().format('YYYY-MM-DD'),
                dateTo: moment().format('YYYY-MM-DD')
            },
            customers: [],
            selectedCustomer: null,
            reportData: [],
            otherIncomeExpense: {
                income: 0,
                expense: 0,
                employee_payment: 0
            },
            heading: 'sample'
        }
    },
    filters: {
        decimal(value) {
            return value == null || value == undefined ? '0.00' : parseFloat(value).toFixed(2);
        }
    },
    created() {
        this.getCustomers();
    },
    methods: {
        getCustomers() {
            axios.get('/get_customers').then(res => {
                this.customers = res.data;
            })
        },

        async getProfitLoss() {
            if (this.selectedCustomer != null) {
                this.filter.customer = this.selectedCustomer.Customer_SlNo;
            } else {
                this.filter.customer = null;
            }
            this.reportData = await axios.post('/get_profit_loss', this.filter).then(res => {
                return res.data;
            })

            this.otherIncomeExpense = await axios.post('/get_other_income_expense', this.filter).then(
            res => {
                return res.data;
            })

        },

        async print() {
            let customerText = '';
            if (this.selectedCustomer != null) {
                customerText = `
						<strong>Customer Id: </strong> ${this.selectedCustomer.Customer_Code}<br>
						<strong>Name: </strong> ${this.selectedCustomer.Customer_Name}<br>
						<strong>Address: </strong> ${this.selectedCustomer.Customer_Address}<br>
						<strong>Mobile: </strong> ${this.selectedCustomer.Customer_Mobile}
					`;
            }

            let dateText = '';
            if (this.filter.dateFrom != '' && this.filter.dateTo != '') {
                dateText = `
						Statement from <strong>${this.filter.dateFrom}</strong> to <strong>${this.filter.dateTo}</strong>
					`;
            }
            let reportContent = `
					<div class="container">
						<h4 style="text-align:center">Profit/Loss Report</h4 style="text-align:center">
						<div class="row">
							<div class="col-md-6">${customerText}</div>
							<div class="col-md-6 text-right">${dateText}</div>
						</div>
						<div class="row">
							<div class="col-xs-12">
								${document.querySelector('#reportTable').innerHTML}
							</div>
						</div>
					</div>
				`;

            var mywindow = window.open('', 'PRINT', `width=${screen.width}, height=${screen.height}`);
            mywindow.document.write(`
					<?php $this->load->view('Administrator/reports/reportHeader.php');?>
				`);

            mywindow.document.head.innerHTML += `
					<style>
						#table {
							border-collapse: collapse;
							width: 100%;
						}

						#table td, #table th {
							padding: 5px;
							border: 1px solid #909090;
						}

						#table th{
							text-align: center;
						}

						#table thead{
							background-color: #cbd6e7;
						}
					</style>
				`;
            mywindow.document.body.innerHTML += reportContent;

            mywindow.focus();
            await new Promise(resolve => setTimeout(resolve, 1000));
            mywindow.print();
            mywindow.close();
        },

        exportTableToPDF() {
            const columns = [{
                    title: "Title",
                    dataKey: "title"
                },
                {
                    title: "Body",
                    dataKey: "body"
                }
            ];
            const doc = new jsPDF({
                // orientation: "portrait",
                unit: "in",
                format: "letter"
            });
            // text is placed using x, y coordinates
            doc.setFontSize(16).text(this.heading, 0.5, 1.0);
            // create a line under heading 
            doc.setLineWidth(0.01).line(0.5, 1.1, 8.0, 1.1);
            // Using autoTable plugin

            doc.autoTable({
                columns,
                body: this.reportData,
                margin: {
                    left: 0.5,
                    top: 1.25
                }
            });
            // Using array of sentences
            doc
                .setFont("helvetica")
                .setFontSize(12)
            // .text(this.moreText, 0.5, 3.5, { align: "left", maxWidth: "7.5" });

            // Creating footer and saving file
            doc
                .setFont("times")
                .setFontSize(11)
                .setFontStyle("italic")
                .setTextColor(0, 0, 255)
                .text(
                    "This is a simple footer located .5 inches from page bottom",
                    0.5,
                    doc.internal.pageSize.height - 0.5
                )
                .save(`${this.heading}.pdf`);
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
            var element = document.getElementById('reportTable');

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
                doc.save('profit_loss.pdf');

            });

        },

        // exportTableToPDF(){
        // 	var pdf = new jsPDF("p", "mm", "a4");
        // 	let header = `
        // 		<div class="row">
        // 			<div class="col-xs-2"><img src="/uploads/company_profile_thum/NafGroup-Logo.jpg" alt="Logo" style="height:80px;" /></div>
        // 			<div class="col-xs-10" style="padding-top:20px;">
        // 				<strong style="font-size:18px;">Company Name</strong><br>
        // 				<p style="white-space:pre-line;">Report Heading Title</p>
        // 			</div>
        // 		</div>
        // 		<div class="row">
        // 			<div class="col-xs-12">
        // 				<div style="border-bottom: 4px double #ccc;margin-top:7px;margin-bottom:7px;"></div>
        // 			</div>
        // 		</div>
        // 	`;
        // 	let footer = `
        // 		<div style="width:100%;height:80px;">&nbsp;</div>
        // 		<div class="row" style="border-bottom:1px solid #ccc;margin-bottom:5px;padding-bottom:6px;">
        // 			<div class="col-xs-6">
        // 				<span style="text-decoration:overline;">Received by</span><br><br>
        // 				* THANK YOU FOR YOUR BUSINESS *
        // 			</div>
        // 			<div class="col-xs-6 text-right">
        // 				<span style="text-decoration:overline;">Authorized by</span>
        // 			</div>
        // 		</div>
        // 		<div class="row">
        // 			<div class="col-xs-6" style="font-size:10px;">
        // 				Print Date: ${moment().format('DD-MM-YYYY h:mm a')}, Printed by: Sohel Rana
        // 			</div>
        // 			<div class="col-xs-6 text-right" style="font-size:10px;">

        // 			</div>
        // 		</div>
        // 	`;

        // 	$("#invoiceHeader").innerHTML = header;
        // 	$("#invoiceFooter").innerHTML = footer;
        // 	var element = $('#reportTable');

        // 	// var width= element.style.width;
        // 	// var height = element.style.height;
        // 	var width = element.internal.pageSize.getWidth();
        // 	var height = element.internal.pageSize.getHeight();

        // 	html2canvas(element).then(canvas => {
        // 		var image = canvas.toDataURL('image/png');

        // 		pdf.addImage(image, 'JPEG', 5, 0, width, height);
        // 		pdf.save('profit_loss' + '.pdf');
        // 	});

        // 	$("#invoiceHeader").innerHTML = "";
        // 	$("#invoiceFooter").innerHTML = "";
        // }
    }
})
</script>