<div id="productList">
    <div style="display:none;" v-bind:style="{display: products.length > 0 ? '' : 'none'}">
        <div class="row">
            <div class="col-md-6">
                <a href="" style="margin: 7px 0;display:block;width:50px;" v-on:click.prevent="print">
                    <i class="fa fa-print"></i> Print
                </a>
            </div>
            <div class="col-md-6 text-right">
                <button class="btn btn-sm btn-success" onclick="exportTableToCSV('product-list.csv')">Export To CSV</button>
            </div>
        </div>
        <div class="row" style="margin-top: 5px;">
            <div class="col-md-12">
                <div class="table-responsive" id="reportTable">
                    <table id="table" class="table table-bordered table-condensed">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Product Id</th>
                                <th>Product Name</th>
                                <th>Category</th>
                                <th>Specification</th>
                                <th>Sale Price</th>
                                <th>Guarantee</th>
                                <th>Warranty</th>
                                <th>Is Serial</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(product, sl) in products">
                                <td style="text-align:center;">{{ sl + 1 }}</td>
                                <td>{{ product.Product_Code }}</td>
                                <td>{{ product.Product_Name }}</td>
                                <td>{{ product.ProductCategory_Name }}</td>
                                <td>{{ product.varient_name }}</td>
                                <td style="text-align:right;">{{ product.Product_SellingPrice }}</td>
                                <td>{{ product.Guarantee }} Days</td>
                                <td>{{ product.Warranty }} Days</td>
                                <td>{{ product.Is_Serial == 'true' ? 'Yes' : 'No' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url(); ?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/export-to-csv.js"></script>

<script>
    new Vue({
        el: '#productList',
        data() {
            return {
                products: [],
            }
        },
        created() {
            this.getProducts();
        },
        methods: {
            getProducts() {
                axios.get('/get_products').then(res => {
                    this.products = res.data;
                })
            },
            async print() {
                let reportContent = `
					<div class="container">
                        <div class="row">
                            <div class="col-xs-12">
                                <h4 style="text-align:center">Product List</h4 style="text-align:center">
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
            }
        }
    })
</script>