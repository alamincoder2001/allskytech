<style>
    .v-select {
        margin-bottom: 5px;
    }

    .v-select .dropdown-toggle {
        padding: 0px;
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
<div id="productLedger">
    <div class="row">
        <div class="col-xs-12 col-md-12 col-lg-12" style="border-bottom:1px #ccc solid;">
            <form v-on:submit.prevent="getProductLedger">
                <div class="form-group">
                    <label class="col-sm-1 control-label no-padding-right"> Product </label>
                    <div class="col-sm-2">
                        <v-select v-bind:options="products" v-model="selectedProduct" label="display_text"></v-select>
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
                        <input type="submit" class="btn btn-primary" value="Show" style="margin-top:0px;border:0px;height:28px;">
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row" style="display:none;" v-bind:style="{display: showTable ? '' : 'none'}">
        <div class="col-md-12" style="margin: 10px 0;">
            <a href="" @click.prevent="print"><i class="fa fa-print"></i> Print</a>
            <div style="float: right; display:inline;">
                <button v-on:click.prvent="pdf" style="background-color: #9aef06;border: 1px solid #9aef06;border-radius: 4px;margin-right: 5px;">
                    PDF </button>
                <button onclick="fnExcelReport();" style="background-color: rgb(140 239 232);border: 1px solid rgb(140 239 232);border-radius: 4px;">
                    EXPORT </button>
            </div>
        </div>

        <div class="col-sm-12">
            <!-- <a href="" style="margin: 7px 0;display:block;width:50px;" v-on:click.prevent="print">
				<i class="fa fa-print"></i> Print
			</a> -->
            <div class="table-responsive" id="reportTable">
                <table class="table table-bordered" id="table">
                    <thead>
                        <tr>
                            <th style="text-align:center">Date</th>
                            <th style="text-align:center">Description</th>
                            <!-- <th style="text-align:center">Reference</th> -->
                            <th style="text-align:center">Rate</th>
                            <th style="text-align:center">In Quantity</th>
                            <th style="text-align:center">Out Quantity</th>
                            <th style="text-align:center">Stock</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td></td>
                            <td style="text-align:left;">Previous Stock</td>
                            <td colspan="3"></td>
                            <td style="text-align:right;">{{ parseFloat(previousStock).toFixed(2) }}</td>
                        </tr>
                        <tr v-for="row in ledger">
                            <td>{{ row.date }}</td>
                            <td style="text-align:left;">{{ row.description }}</td>
                            <!-- <td style="text-align:left;">{{ row.ref }}</td> -->
                            <td style="text-align:right;">{{ parseFloat(row.rate).toFixed(2) }}</td>
                            <td style="text-align:right;">{{ parseFloat(row.in_quantity).toFixed(2) }}</td>
                            <td style="text-align:right;">{{ parseFloat(row.out_quantity).toFixed(2) }}</td>
                            <td style="text-align:right;">{{ parseFloat(row.stock).toFixed(2) }}</td>
                        </tr>
                        <tr style="font-weight: 600;">
                            <td colspan="3" style="text-align:right">Total</td>
                            <td style="text-align: right;">{{ (ledger.reduce((prev, curr) => { return prev + +curr.in_quantity},0)).toFixed(2) }}</td>
                            <td style="text-align: right;">{{ (ledger.reduce((prev, curr) => { return prev + +curr.out_quantity},0)).toFixed(2) }}</td>
                            <td></td>
                        </tr>
                    </tbody>
                    <tbody v-if="ledger.length == 0">
                        <tr>
                            <td colspan="6">No records found</td>
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
        el: '#productLedger',
        data() {
            return {
                products: [],
                selectedProduct: null,
                dateFrom: null,
                dateTo: null,
                ledger: [],
                previousStock: 0,
                showTable: false,
                companylogo: 'NafGroup-Logo.jpg',
                Company_Name: 'NagGroup',
                Repot_Heading: 'This is a Heading of the PDF',
                AddBy: 'Jewel'
            }
        },
        created() {
            let today = moment().format('YYYY-MM-DD');
            this.dateTo = today;
            this.dateFrom = moment().format('YYYY-MM-DD');
            this.getProducts();
        },
        methods: {
            getProducts() {
                axios.get('/get_products').then(res => {
                    this.products = res.data;
                })
            },
            getProductLedger() {
                if (this.selectedProduct == null) {
                    alert('Select product');
                    return;
                }
                let data = {
                    dateFrom: this.dateFrom,
                    dateTo: this.dateTo,
                    productId: this.selectedProduct.Product_SlNo
                }

                this.showTable = false;

                axios.post('/get_product_ledger', data).then(res => {
                    this.ledger = res.data.ledger;
                    this.previousStock = res.data.previousStock;
                    this.showTable = true;
                })
            },
            async print() {
                let reportContent = `
					<div class="container">
						<h4 style="text-align:center">Product Ledger</h4 style="text-align:center">
						<div class="row">
							<div class="col-xs-6" style="font-size:12px;">
								<strong>Product Code: </strong> ${this.selectedProduct.Product_Code}<br>
								<strong>Product Name: </strong> ${this.selectedProduct.Product_Name}
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
					<?php $this->load->view('Administrator/reports/reportHeader.php'); ?>
				`);

                mywindow.document.body.innerHTML += reportContent;

                mywindow.focus();
                await new Promise(resolve => setTimeout(resolve, 1000));
                mywindow.print();
                mywindow.close();
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
                    doc.save('product_ledger.pdf');

                });

            },
        }
    })
</script>