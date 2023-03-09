<style>
	.v-select{
		margin-bottom: 5px;
	}
	.v-select .dropdown-toggle{
		padding: 0px;
	}
	.v-select input[type=search], .v-select input[type=search]:focus{
		margin: 0px;
	}
	.v-select .vs__selected-options{
		overflow: hidden;
		flex-wrap:nowrap;
	}
	.v-select .selected-tag{
		margin: 2px 0px;
		white-space: nowrap;
		position:absolute;
		left: 0px;
	}
	.v-select .vs__actions{
		margin-top:-5px;
	}
	.v-select .dropdown-menu{
		width: auto;
		overflow-y:auto;
	}
</style>
<div id="customerPaymentReport">
	<div class="row">

		<div class="col-xs-12 col-md-12 col-lg-12" style="border-bottom:1px #ccc solid;">
			<div class="form-group">
				<label class="col-sm-1 control-label no-padding-right"> Customer </label>
				<div class="col-sm-2">
					<v-select v-bind:options="customers" v-model="selectedCustomer" label="display_name"></v-select>
				</div>
			</div>
	
			<div class="form-group">
				<label class="col-sm-1 control-label no-padding-right"> Date from </label>
				<div class="col-sm-2">
					<input type="date" class="form-control" v-model="dateFrom">
				</div>
				<label class="col-sm-1 control-label no-padding-right text-center" style="width:30px"> to </label>
				<div class="col-sm-2">
					<input type="date" class="form-control" v-model="dateTo">
				</div>
			</div>
	
			<div class="form-group">
				<div class="col-sm-1">
					<input type="button" class="btn btn-primary" value="Show" v-on:click="getReport" style="margin-top:0px;border:0px;height:28px;">
				</div>
			</div>
		</div>
	</div>
	<div class="row" style="display:none;" v-bind:style="{display: showTable ? '' : 'none'}">
		<div class="col-md-12" style="margin: 10px 0;">
			<a href="" @click.prevent="print"><i class="fa fa-print"></i> Print</a>
			<div style="float: right; display:inline;">
				<button v-on:click.prvent="pdf" style="background-color: #9aef06;border: 1px solid #9aef06;border-radius: 4px;margin-right: 5px;"> PDF </button>
				<button onclick="fnExcelReport();" style="background-color: rgb(140 239 232);border: 1px solid rgb(140 239 232);border-radius: 4px;"> EXPORT </button>
			</div>
		</div>

		<div class="col-sm-12">
			<!-- <div class="row" style="margin-top: 7px; margin-bottom: 7px;">
				<div class="col-sm-6">
					<a href="" style="margin: 7px 0;display:block;width:50px;" v-on:click.prevent="print">
						<i class="fa fa-print"></i> Print
					</a>
				</div>
				<div class="col-md-6 text-right">
					<button class="btn btn-sm btn-success" onclick="exportTableToCSV('customer-payment-report.csv')">Export To CSV</button>
				</div>
			</div> -->
			<div class="table-responsive" id="reportTable">
				<table id="table" class="table table-bordered">
					<thead>
						<tr>
							<th style="text-align:center">Date</th>
							<th style="text-align:center">Description</th>
							<th style="text-align:center">Reference</th>
							<th style="text-align:center">Bill</th>
							<th style="text-align:center">Paid</th>
							<th style="text-align:center">Inv.Due</th>
							<th style="text-align:center">Retruned</th>
							<th style="text-align:center">Paid to customer</th>
							<th style="text-align:center">Balance</th>
						</tr>
					</thead>
					<tbody>
						<tr> 
							<td></td>
							<td style="text-align:left;">Previous Balance</td>
							<td colspan="6"></td>
							<td style="text-align:right;">{{ parseFloat(previousBalance).toFixed(2) }}</td>
						</tr>
						<tr v-for="payment in payments">
							<td>{{ payment.date }}</td> 
							<td style="text-align:left;">{{ payment.description }}</td>
							<td style="text-align:left;">{{ payment.reference }}</td>
							<td style="text-align:right;">{{ parseFloat(payment.bill).toFixed(2) }}</td>
							<td style="text-align:right;">{{ parseFloat(payment.paid).toFixed(2) }}</td>
							<td style="text-align:right;">{{ parseFloat(payment.due).toFixed(2) }}</td>
							<td style="text-align:right;">{{ parseFloat(payment.returned).toFixed(2) }}</td>
							<td style="text-align:right;">{{ parseFloat(payment.paid_out).toFixed(2) }}</td>
							<td style="text-align:right;">{{ parseFloat(payment.balance).toFixed(2) }}</td>
						</tr>
					</tbody>
					<tbody v-if="payments.length == 0">
						<tr>
							<td colspan="8">No records found</td>
						</tr>
					</tbody>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.4.1/jspdf.min.js"></script>

<script type="text/javascript">
	function fnExcelReport()
	{
	    var tab_text="<table border='2px'><tr bgcolor='#87AFC6'>";
	    var textRange; var j=0;
	    tab = document.getElementById('table'); // id of table

	    for(j = 0 ; j < tab.rows.length ; j++) 
	    {     
	        tab_text=tab_text+tab.rows[j].innerHTML+"</tr>";
	        //tab_text=tab_text+"</tr>";
	    }

	    tab_text=tab_text+"</table>";
	    tab_text= tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
	    tab_text= tab_text.replace(/<img[^>]*>/gi,""); // remove if u want images in your table
	    tab_text= tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params

	    var ua = window.navigator.userAgent;
	    var msie = ua.indexOf("MSIE "); 

	    if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer
	    {
	        txtArea1.document.open("txt/html","replace");
	        txtArea1.document.write(tab_text);
	        txtArea1.document.close();
	        txtArea1.focus(); 
	        sa=txtArea1.document.execCommand("SaveAs",true,"purchase_record.xls");
	    }  
	    else                 //other browser not tested on IE 11
	        sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));  

	    return (sa);
	}
</script>

<script>
	Vue.component('v-select', VueSelect.VueSelect);
	new Vue({
		el: '#customerPaymentReport',
		data(){
			return {
				customers: [],
				selectedCustomer: null,
				dateFrom: null,
				dateTo: null,
				payments: [],
				previousBalance: 0.00,
				showTable: false
			}
		},
		created(){
			let today = moment().format('YYYY-MM-DD');
			this.dateTo = today;
			this.dateFrom = moment().format('YYYY-MM-DD');
			this.getCustomers();
		},
		methods:{
			getCustomers(){
				axios.get('/get_customers').then(res => {
					this.customers = res.data;
				})
			},
			getReport(){
				if(this.selectedCustomer == null){
					alert('Select customer');
					return;
				}
				let data = {
					dateFrom: this.dateFrom,
					dateTo: this.dateTo,
					customerId: this.selectedCustomer.Customer_SlNo
				}

				axios.post('/get_customer_ledger', data).then(res => {
					this.payments = res.data.payments;
					this.previousBalance = res.data.previousBalance;
					this.showTable = true;
				})
			},
			async print(){
				let reportContent = `
					<div class="container">
						<h4 style="text-align:center">Customer payment report</h4 style="text-align:center">
						<div class="row">
							<div class="col-xs-6" style="font-size:12px;">
								<strong>Customer Code: </strong> ${this.selectedCustomer.Customer_Code}<br>
								<strong>Name: </strong> ${this.selectedCustomer.Customer_Name}<br>
								<strong>Address: </strong> ${this.selectedCustomer.Customer_Address}<br>
								<strong>Mobile: </strong> ${this.selectedCustomer.Customer_Mobile}<br>
							</div>
							<div class="col-xs-6 text-right">
								<strong>Statement from</strong> ${this.dateFrom} <strong>to</strong> ${this.dateTo}
							</div>
						</div>
					</div>
					<div class="container">
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

				mywindow.document.body.innerHTML += reportContent;

				mywindow.focus();
				await new Promise(resolve => setTimeout(resolve, 1000));
				mywindow.print();
				mywindow.close();
			},
			async pdf(){
				
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

				var width= element.style.width;
				var height = element.style.height;

				await html2canvas(element).then(canvas => {
					var imgData = canvas.toDataURL('image/png');
					var imgWidth = 200; 
					var pageHeight = 295;  
					var imgHeight = canvas.height * imgWidth / canvas.width;
					var heightLeft = imgHeight;
					var doc = new jsPDF('p', 'mm', 'a4');
					var position = 5;

					doc.addImage(imgData, 'PNG', 5, position, imgWidth, (imgHeight-10));
					heightLeft -= pageHeight;

					while (heightLeft >= 0) {
					position = heightLeft - imgHeight;
					doc.addPage();
					doc.addImage(imgData, 'PNG', 5, position, imgWidth, imgHeight);
					// doc.addImage(imgData, 'PNG', 5, position, imgWidth, imgHeight);
					heightLeft -= pageHeight;
					}
					doc.save( 'customer_payment_report.pdf');

				});

        	},
		}
	})
</script>