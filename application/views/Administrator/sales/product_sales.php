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

    #branchDropdown .vs__actions button {
        display: none;
    }

    #branchDropdown .vs__actions .open-indicator {
        height: 15px;
        margin-top: 7px;
    }

    .modal-mask {
        position: fixed;
        z-index: 9998;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, .5);
        display: table;
        transition: opacity .3s ease;
    }

    .modal-wrapper {
        display: table-cell;
        vertical-align: middle;
    }

    .modal-container {
        width: 450px;
        margin: 0px auto;
        background-color: #fff;
        border-radius: 2px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, .33);
        transition: all .3s ease;
        font-family: Helvetica, Arial, sans-serif;
    }

    .modal-header {
        padding-bottom: 0 !important;
    }

    .modal-header h3 {
        margin-top: 0;
        color: #42b983;
    }

    .modal-body {
        overflow-y: auto !important;
        height: 300px !important;
        margin: -8px -14px -44px !important;
    }

    .modal-default-button {
        float: right;
    }

    .serialBtn {
        border: none;
        font-size: 13px;
        line-height: 0.38;
        margin-left: 2px;
        background-color: rgb(0 189 133) !important;
        height: 51px;
        padding: 18px;
        border-radius: 4px;
    }

    @media screen and (max-width:767px) {
        .mobile-full {
            width: 100% !important;
        }

        #sales {
            padding-top: 46px !important;
        }

        .mobile-left {
            width: 90% !important;
            float: left !important;
            display: inline-block;
        }

        .mobile-right {
            width: 10% !important;
            float: right;
        }

        .due-left {
            width: 50% !important;
            float: left;
        }

        .due-right {
            width: 50% !important;
            float: right;
        }

        .due,
        .discount,
        .transport-cost,
        .total,
        .paid,
        .vat,
        .sub-total {
            width: 100%;
        }

        .discount-left {
            width: 30% !important;
            float: left;
        }

        .discount-middle {
            width: 10%;
        }

        .discount-right {
            width: 60%;
            float: right;
        }

        .mobile-stock-design {
            width: 50% !important;
            float: left !important;
        }

        .formobile {
            margin-left: 0px;
            margin-right: 0px;
        }
    }
</style>
<div id="sales" class="row">


    <div style="display:none" id="serial-modal" v-if="" v-bind:style="{display:serialModalStatus?'block':'none'}">
        <transition name="modal">
            <div class="modal-mask">
                <div class="modal-wrapper">
                    <div class="modal-container">
                        <div class="modal-header">
                            <slot name="header">
                                <h3>IMEI Number Add</h3>
                            </slot>
                        </div>
                        <div class="modal-body" style="overflow: hidden; height: 100%; margin: -8px -14px -44px;">
                            <slot name="body">
                                <form @submit.prevent="imei_add_action">
                                    <div class="form-group">
                                        <div class="col-sm-12" style="display: flex;margin-bottom: 10px;">
                                            <textarea autocomplete="off" ref="imeinumberadd" id="imei_number" name="imei_number" v-model="get_imei_number" class="form-control" placeholder="please Enter Serial Number" cols="30" rows="2"></textarea>
                                            <input type="submit" class="btn btn-sm btn primary serialBtn" value="Add">
                                        </div>
                                    </div>
                                </form>
                            </slot>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">SL</th>
                                        <th scope="col">Serial</th>
                                        <th scope="col">Product</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(product, sl) in imei_cart">
                                        <th scope="row">{{ imei_cart.length - sl }}</th>
                                        <td>{{product.imeiNumber}}</td>
                                        <td>{{product.Product_Name}}</td>
                                        <td @click="remove_imei_item(product.imeiNumber)"> <span class="badge badge-danger badge-pill" style="cursor:pointer"><i class="fa fa-times"></i></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <a class="btn" @click.prevent="serialHideModal" style="background: rgb(255 0 0) !important;border: none;font-size: 16px;padding: 5px 12px;
}">Close</a>

                            <a class="btn" @click.prevent="serialHideModal" style="background: rgb(0 175 70) !important;border: none;font-size: 16px;padding: 5px 25px;">OK</a>
                        </div>
                    </div>
                </div>
            </div>
        </transition>
    </div>



    <div class="col-xs-12 col-md-12 col-lg-12" style="border-bottom:1px #ccc solid;margin-bottom:5px;">
        <div class="row formobile">
            <div class="form-group">
                <label class="col-xs-4 col-lg-1 control-label no-padding-right"> Invoice no </label>
                <div class="col-xs-8 col-lg-2">
                    <input type="text" id="invoiceNo" class="form-control" v-model="sales.invoiceNo" readonly />
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-4 col-lg-1 control-label no-padding-right"> Sales By </label>
                <div class="col-xs-8 col-lg-2">
                    <v-select v-bind:options="employees" v-model="selectedEmployee" label="Employee_Name" placeholder="Select Employee"></v-select>
                </div>
            </div>

            <div class="form-group">
                <label class="col-xs-4 col-lg-1 control-label no-padding-right"> Sales From </label>
                <div class="col-xs-8 col-lg-2">
                    <v-select id="branchDropdown" v-bind:options="branches" label="Brunch_name" v-model="selectedBranch" disabled></v-select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-4 col-lg-1 control-label no-padding-right"> Sales From </label>
                <div class="col-xs-8 col-lg-2">
                    <input class="form-control" id="salesDate" type="date" v-model="sales.salesDate" v-bind:disabled="userType == 'u' ? true : false" />
                </div>
            </div>
            <!-- <div class="form-group">
                <label class="col-xs-4 col-lg-1 control-label no-padding-right"> Reference : </label>
                <div class="col-xs-8 col-lg-2">
                    <input class="form-control" id="reference" type="text" v-model="sales.reference" />
                </div>
            </div> -->
        </div>
    </div>

    <div class="col-xs-9 col-md-9 col-lg-9 mobile-full">
        <div class="widget-box">
            <div class="widget-header">
                <h4 class="widget-title">Sales Information</h4>
                <div class="widget-toolbar">
                    <a href="#" data-action="collapse">
                        <i class="ace-icon fa fa-chevron-up"></i>
                    </a>

                    <a href="#" data-action="close">
                        <i class="ace-icon fa fa-times"></i>
                    </a>
                </div>
            </div>

            <div class="widget-body">
                <div class="widget-main">

                    <div class="row">

                        <form v-on:submit.prevent="addToCart">
                            <div class="col-xs-12 col-lg-5">
                                <div class="form-group clearfix" style="margin-bottom: 8px;">
                                    <label class="col-xs-4 control-label no-padding-right"> Sales Type </label>
                                    <div class="col-xs-8">
                                        <input type="radio" name="salesType" value="wholesale" v-model="sales.salesType" v-on:change="onSalesTypeChange"> Wholesale
                                        <input type="radio" name="salesType" value="retail" v-model="sales.salesType" v-on:change="onSalesTypeChange"> Retail &nbsp;
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-4 col-lg-4 control-label no-padding-right"> Customer </label>
                                    <div class="col-xs-7">
                                        <v-select v-bind:options="customers" label="display_name" v-model="selectedCustomer" v-on:input="customerOnChange"></v-select>
                                    </div>
                                    <div class="col-xs-1" style="padding: 0;">
                                        <a href="<?= base_url('customer') ?>" class="btn btn-xs btn-danger" style="height: 25px; border: 0; width: 27px; margin-left: -10px;" target="_blank" title="Add New Customer"><i class="fa fa-plus" aria-hidden="true" style="margin-top: 5px;"></i></a>
                                    </div>
                                </div>

                                <div class="form-group" style="display:none;" v-bind:style="{display: selectedCustomer.Customer_Type == 'G' ? '' : 'none'}">
                                    <label class="col-xs-4 control-label no-padding-right"> Name </label>
                                    <div class="col-xs-8">
                                        <input type="text" id="customerName" placeholder="Customer Name" class="form-control" v-model="selectedCustomer.Customer_Name" v-bind:disabled="selectedCustomer.Customer_Type == 'G' ? false : true" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-xs-4 control-label no-padding-right"> Mobile No </label>
                                    <div class="col-xs-8">
                                        <input type="text" id="mobileNo" placeholder="Mobile No" class="form-control" v-model="selectedCustomer.Customer_Mobile" v-bind:disabled="selectedCustomer.Customer_Type == 'G' ? false : true" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-xs-4 control-label no-padding-right"> Address </label>
                                    <div class="col-xs-8">
                                        <textarea id="address" placeholder="Address" class="form-control" v-model="selectedCustomer.Customer_Address" v-bind:disabled="selectedCustomer.Customer_Type == 'G' ? false : true"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-12 col-lg-5">
                                <div class="form-group">
                                    <label class="col-xs-4 control-label no-padding-right"> Product </label>
                                    <div class="col-xs-7">
                                        <v-select v-bind:options="products" v-model="selectedProduct" label="display_text" id="product" v-on:input="productOnChange"></v-select>
                                    </div>
                                    <div class="col-xs-1" style="padding: 0;">
                                        <a href="<?= base_url('product') ?>" class="btn btn-xs btn-danger" style="height: 25px; border: 0; width: 27px; margin-left: -10px;" target="_blank" title="Add New Product"><i class="fa fa-plus" aria-hidden="true" style="margin-top: 5px;"></i></a>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-xs-4 control-label no-padding-right"> Sale Rate </label>
                                    <div class="col-xs-8">
                                        <input type="number" id="salesRate" placeholder="Rate" step="0.01" class="form-control" v-model="selectedProduct.Product_SellingPrice" v-on:input="calCulateCart" />
                                    </div>
                                </div>

                                <div class="form-group" style="display: none;" :style="{display: selectedProduct.Is_Serial != 'true' ? '' : 'none'}">
                                    <label class="col-xs-4 control-label no-padding-right"> Quantity </label>
                                    <div class="col-xs-8">
                                        <input type="number" step="0.01" id="quantity" placeholder="Qty" class="form-control" ref="quantity" v-model="selectedProduct.quantity" v-on:input="productTotal" autocomplete="off" v-bind:disabled="selectedProduct.Is_Serial == 'true' ? true : false" required />
                                    </div>
                                </div>

                                <div class="form-group" style="display: none;" :style="{display: selectedProduct.Is_Serial == 'true' ? '' : 'none'}">
                                    <label class="col-xs-4 control-label no-padding-right"> Quantity </label>
                                    <div class="col-xs-8">
                                        <div class="row">
                                            <div class="col-xs-10 no-padding-right">
                                                <input type="number" step="0.01" id="quantity" placeholder="Qty" class="form-control" ref="quantity" v-model="selectedProduct.quantity" v-on:input="productTotal" autocomplete="off" v-bind:disabled="selectedProduct.Is_Serial == 'true' ? true : false" required />
                                            </div>
                                            <div class="col-xs-2 no-padding-left">
                                                <button type="button" id="show-modal" @click="serialShowModal" style="background: rgb(210, 0, 0);color: white;border: none;font-size: 15px;height: 24px;margin-left: 1px;"><i class="fa fa-plus"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-4 control-label no-padding-right">Discount %</label>
                                    <div class="col-xs-8">
                                        <div class="row">
                                            <div class="col-xs-5">
                                                <input type="number" id="p_d_percent" placeholder="%" ref="p_d_percent" step="0.01" min="0.00" class="form-control" v-model="p_discount_percent" v-on:input="productTotal" />
                                            </div>
                                            <label class="col-xs-2 control-label no-padding-right">Taka</label>
                                            <div class="col-xs-5">
                                                <input type="number" id="p_d_percent_taka" placeholder="Taka" ref="p_d_percent_taka" step="0.01" min="0.00" class="form-control" v-model="selectedProduct.discount" v-on:input="productTotal" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-4 control-label no-padding-right"> Amount </label>
                                    <div class="col-xs-8">
                                        <input type="text" placeholder="Amount" class="form-control" v-model="selectedProduct.total" readonly="" />
                                    </div>
                                </div>

                                <!-- <div class="form-group">
                                    <label class="col-xs-4 control-label no-padding-right"> </label>
                                    <div class="col-xs-8">
                                        <button type="submit" class="btn btn-default pull-right">Add to Cart</button>
                                    </div>
                                </div> -->
                            </div>

                            <div class="col-xs-2">
                                <div style="height:90px">
                                    <button type="submit" class="btn" style="margin-top: 28px;border: none;padding: 12px 21px;background: #007ec7 !important;">Add to Cart</button>
                                </div>
                                <div style="display:none" v-bind:style="{display:sales.isService == 'true' ? 'none' : ''}">
                                    <p style="display:none;margin: 0px;text-align: center;" v-bind:style="{color: productStock > 0 ? '#02a2ff' : 'red', display: selectedProduct.Product_SlNo == '' ? 'none' : ''}">{{ productStockText }}
                                    </p>
                                    <p style="display:none;;font-weight: 600;font-size: 17px;text-align: center;" v-bind:style="{color: productStock > 0 ? '#0400ff' : 'red', display: selectedProduct.Product_SlNo == '' ? 'none' : ''}">
                                        {{ productStock }} {{ selectedProduct.Unit_Name }}
                                    </p>
                                    <!-- <input type="text" id="productStock" v-model="productStock" readonly style="border:none;font-size:20px;width:100%;text-align:center;color:green">  -->

                                    <!-- <input type="text" id="stockUnit" v-model="selectedProduct.Unit_Name" readonly style="border:none;font-size:12px;width:100%;text-align: center;"><br><br> -->
                                </div>
                                <input type="password" ref="productPurchaseRate" v-model="selectedProduct.Product_Purchase_Rate" v-on:mousedown="toggleProductPurchaseRate" v-on:mouseup="toggleProductPurchaseRate" readonly title="Purchase rate (click & hold)" style="font-size:12px;width:100%;text-align: center;">

                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-md-12 col-lg-12" style="padding-left: 0px;padding-right: 0px;">
            <div class="table-responsive">
                <table class="table table-bordered" style="color:#000;margin-bottom: 5px;">
                    <thead>
                        <tr class="">
                            <th style="width:10%;color:#000;">Sl</th>
                            <th style="width:25%;color:#000;">Product Name</th>
                            <th style="width:12%;color:#000;">Product Code</th>
                            <th style="width:12%;color:#000;">Unit</th>
                            <th style="width:7%;color:#000;">Qty</th>
                            <th style="width:8%;color:#000;">Rate</th>
                            <th style="width:8%;color:#000;">Discount</th>
                            <th style="width:15%;color:#000;">Total Amount</th>
                            <th style="width:15%;color:#000;">Action</th>
                        </tr>
                    </thead>
                    <tbody style="display:none;" v-bind:style="{display: cart.length > 0 ? '' : 'none'}">
                        <tr v-for="(product, sl) in cart">
                            <td>{{ sl + 1 }}</td>
                            <td v-if="product.SerialStore.length > 0">{{ product.name }}<br>
                                ({{ product.SerialStore.map(obj => obj.imeiNumber).join(', ') }})
                            </td>
                            <td v-else>{{ product.name }}</td>
                            <td>{{ product.productCode }}</td>
                            <td>{{ product.UnitName }}</td>
                            <td>{{ product.quantity }}</td>
                            <td>{{ product.salesRate }}</td>
                            <td>{{ product.SaleDetails_Discount }}</td>
                            <td>{{ product.total }}</td>
                            <td><a href="" v-on:click.prevent="removeFromCart(sl,product.SerialStore)"><i class="fa fa-trash"></i></a></td>
                        </tr>

                        <tr>
                            <td colspan="7"></td>
                        </tr>

                        <tr style="font-weight: bold;">
                            <td colspan="2">Note</td>
                            <td colspan="2" style="text-align:right">Total Qty = {{
								cart.reduce((prev, cur) => { return prev + parseFloat(cur.quantity)}, 0).toFixed(2)
							 }}</td>
                            <td colspan="4">Total</td>
                        </tr>

                        <tr>
                            <td colspan="4"><textarea style="width: 100%;font-size:13px;" placeholder="Note" v-model="sales.note"></textarea></td>
                            <td colspan="5" style="padding-top: 15px;font-size:18px;">{{ sales.total }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <div class="col-xs-3 col-xs-3 col-md-3 col-lg-3 mobile-full">
        <div class="widget-box">
            <div class="widget-header">
                <h4 class="widget-title">Amount Details</h4>
                <div class="widget-toolbar">
                    <a href="#" data-action="collapse">
                        <i class="ace-icon fa fa-chevron-up"></i>
                    </a>

                    <a href="#" data-action="close">
                        <i class="ace-icon fa fa-times"></i>
                    </a>
                </div>
            </div>

            <div class="widget-body">
                <div class="widget-main">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="table-responsive">
                                <table style="color:#000;margin-bottom: 0px;border-collapse: collapse;">
                                    <tr>
                                        <td>
                                            <div class="form-group">
                                                <label class="col-xs-12 control-label no-padding-right sub-total">Sub
                                                    Total</label>
                                                <div class="col-xs-12 sub-total">
                                                    <input type="number" id="subTotal" class="form-control" v-model="sales.subTotal" readonly />
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <div class="form-group">
                                                <label class="col-xs-12 control-label no-padding-right vat"> Vat
                                                </label>
                                                <div class="col-xs-4 discount-left">
                                                    <input type="number" id="vatPercent" class="form-control" v-model="vatPercent" v-on:input="calculateTotal" />
                                                </div>
                                                <label class="col-xs-1 control-label no-padding-right discount-middle">%</label>
                                                <div class="col-xs-7 discount-right">
                                                    <input type="number" id="vat" readonly="" class="form-control" v-model="sales.vat" />
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <div class="form-group">
                                                <label class="col-xs-12 control-label no-padding-right discount">Discount
                                                    Persent</label>

                                                <div class="col-xs-4 discount-left">
                                                    <input type="number" id="discountPercent" class="form-control" v-model="discountPercent" v-on:input="calculateTotal" />
                                                </div>

                                                <label class="col-xs-1 control-label no-padding-right discount-middle">%</label>

                                                <div class="col-xs-7 discount-right">
                                                    <input type="number" id="discount" class="form-control" v-model="sales.discount" v-on:input="calculateTotal" />
                                                </div>

                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <div class="form-group">
                                                <label class="col-xs-12 control-label no-padding-right transport-cost">Transport
                                                    Cost</label>
                                                <div class="col-xs-12 transport-cost">
                                                    <input type="number" class="form-control" v-model="sales.transportCost" v-on:input="calculateTotal" />
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr style="display:none;">
                                        <td>
                                            <div class="form-group">
                                                <label class="col-xs-12 control-label no-padding-right">Round Of</label>
                                                <div class="col-xs-12">
                                                    <input type="number" id="roundOf" class="form-control" />
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <div class="form-group">
                                                <label class="col-xs-12 control-label no-padding-right total">Total</label>
                                                <div class="col-xs-12 total">
                                                    <input type="text" id="total" class="form-control" v-model="sales.total" readonly />
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <div class="form-group">
                                                <label class="col-xs-12 control-label no-padding-right paid">cashPaid</label>
                                                <div class="col-xs-12 cashPaid">
                                                    <input type="number" id="cashPaid" class="form-control" v-model="sales.cashPaid" v-on:input="calculateTotal" />
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <div class="form-group">
                                                <label class="col-xs-12 control-label no-padding-right paid">bankPaid</label>
                                                <div class="col-xs-12 bankPaid">
                                                    <input type="number" id="bankPaid" class="form-control" v-model="sales.bankPaid" @input="onChangeBank" />
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr v-if="sales.bankPaid > 0">
                                        <td>
                                            <div class="form-group">
                                                <label class="col-xs-12 control-label no-padding-right total">Account Name</label>
                                                <div class="col-xs-12 total">
                                                    <v-select v-bind:options="filteredAccounts" v-model="selectedAccount" label="display_text" placeholder="Select account"></v-select>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <div class="form-group">
                                                <label class="col-xs-12 control-label due">Due</label>
                                                <div class="col-xs-6 due-left">
                                                    <input type="number" id="due" class="form-control" v-model="sales.due" readonly />
                                                </div>
                                                <div class="col-xs-6 due-right">
                                                    <input type="number" id="previousDue" class="form-control" v-model="sales.previousDue" readonly style="color:red;" />
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <div class="form-group">
                                                <div class="col-xs-6 due-left">
                                                    <input type="button" class="btn btn-default btn-sm" value="Sale" v-on:click="saveSales" v-bind:disabled="saleOnProgress ? true : false" style="color: black!important;margin-top: 0px;width:100%;padding:5px;font-weight:bold;">
                                                </div>
                                                <div class="col-xs-6 due-right">
                                                    <a class="btn btn-info btn-sm" v-bind:href="`/sales/${sales.isService == 'true' ? 'service' : 'product'}`" style="color: black!important;margin-top: 0px;width:100%;padding:5px;font-weight:bold;">New
                                                        Sale</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                </table>
                            </div>
                        </div>
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
    Vue.component('v-select', VueSelect.VueSelect);
    new Vue({
        el: '#sales',
        data() {
            return {
                sales: {
                    salesId: parseInt('<?php echo $salesId; ?>'),
                    invoiceNo: '<?php echo $invoice; ?>',
                    salesBy: '<?php echo $this->session->userdata("FullName"); ?>',
                    salesType: 'wholesale',
                    salesFrom: '',
                    salesDate: '',
                    customerId: '',
                    employeeId: null,
                    subTotal: 0.00,
                    discount: 0.00,
                    vat: 0.00,
                    transportCost: 0.00,
                    total: 0.00,
                    paid: 0.00,
                    cashPaid: 0.00,
                    bankPaid: 0.00,
                    previousDue: 0.00,
                    due: 0.00,
                    payment_type: '',
                    account_id: '',
                    isService: '<?php echo $isService; ?>',
                    note: '',
                },
                isSerial: 'true',
                p_discount_percent: 0.00,
                vatPercent: 0,
                discountPercent: 0,
                cart: [],
                employees: [],
                selectedEmployee: null,
                branches: [],
                selectedBranch: {
                    brunch_id: "<?php echo $this->session->userdata('BRANCHid'); ?>",
                    Brunch_name: "<?php echo $this->session->userdata('Brunch_name'); ?>"
                },
                customers: [],
                selectedCustomer: {
                    Customer_SlNo: '',
                    Customer_Code: '',
                    Customer_Name: '',
                    display_name: 'Select Customer',
                    Customer_Mobile: '',
                    Customer_Address: '',
                    Customer_Type: ''
                },
                oldCustomerId: null,
                oldPreviousDue: 0,
                products: [],
                IMEIStore: [],
                selectedIEMI: {
                    Product_SlNo: '',
                    display_text: 'Select Product',
                    Product_Name: '',
                    Unit_Name: '',
                    quantity: 1,
                    Product_Purchase_Rate: '',
                    Product_SellingPrice: 0.00,
                    vat: 0.00,

                    total: 0.00,
                    ps_prod_id: '',
                    ps_imei_number: '',
                    SaleDetails_Discount: 0,
                    ProductCategory_Name: '',
                    ProductCategory_ID: ''
                },
                selectedProduct: {
                    Product_SlNo: '',
                    display_text: 'Select Product',
                    Product_Name: '',
                    Unit_Name: '',
                    quantity: 0,
                    Product_Purchase_Rate: '',
                    Product_SellingPrice: 0.00,
                    vat: 0.00,
                    total: 0.00
                },
                allIMEIStore: [],
                allProducts: [],
                productPurchaseRate: '',
                productStockText: '',
                productStock: '',
                saleOnProgress: false,
                userType: '<?php echo $this->session->userdata("accountType"); ?>',
                employeeId: '<?php echo $this->session->userdata("employee_id"); ?>',

                serialModalStatus: false,
                get_imei_number: "",
                imei_cart: [],
                accounts: [],
                selectedAccount: null,
            }
        },
        created() {
            this.sales.salesDate = moment().format('YYYY-MM-DD');
            this.getEmployees();
            this.getBranches();
            this.getCustomers();
            this.getProducts();
            this.GetIMEIList();
            this.getAccounts();
            if (this.sales.salesId != 0) {
                this.getSales();
            }
        },
        // watch: {
        //     isSerial(value) {
        //         if (value == 'false') {
        //             this.selectedIEMI = {
        //                 Product_SlNo: '',
        //                 display_text: 'Select Product',
        //                 Product_Name: '',
        //                 Unit_Name: '',
        //                 quantity: 1,
        //                 Product_Purchase_Rate: '',
        //                 Product_SellingPrice: 0.00,
        //                 vat: 0.00,

        //                 total: 0.00,
        //                 ps_prod_id: '',
        //                 ps_imei_number: '',
        //                 SaleDetails_Discount: 0,
        //                 ProductCategory_Name: '',
        //                 ProductCategory_ID: ''
        //             }
        //         } else {
        //             this.selectedProduct = {
        //                 Product_SlNo: '',
        //                 display_text: 'Select Product',
        //                 Product_Name: '',
        //                 Unit_Name: '',
        //                 quantity: 0,
        //                 Product_Purchase_Rate: '',
        //                 Product_SellingPrice: 0.00,
        //                 vat: 0.00,
        //                 total: 0.00
        //             }
        //         }
        //         // if (this.sales.salesType == 'wholesale') {
        //         //     this.products = this.allProducts.filter((product) => product.Product_WholesaleRate > 0 && product.Is_Serial == this.isSerial);
        //         //     this.products.map((product) => {
        //         //         return product.Product_SellingPrice = product.Product_WholesaleRate;
        //         //     })
        //         // } else {
        //         //     this.products = this.allProducts.filter((product) => product.Is_Serial == this.isSerial);
        //         // }
        //         // this.products = this.allProducts.filter(p => p.Is_Serial == value);
        //     }
        // },
        computed: {
            filteredAccounts(){
                let accounts = this.accounts.filter(account => account.status == '1');
                return accounts.map(account => {
                    account.display_text = `${account.account_name} - ${account.account_number} (${account.bank_name})`;
                    return account;
                })
            },
        },
        methods: {
            getAccounts(){
                axios.get('/get_bank_accounts')
                .then(res => {
                    this.accounts = res.data;
                })
            },

            onChangeBank(){
                if (this.sales.bankPaid == 0) {
                    this.selectedAccount = null
                }
                this.calculateTotal();
            },

            getEmployees() {
                axios.get('/get_employees').then(res => {
                    this.employees = res.data;

                    let data = res.data.filter((e) => {
                        return e.Employee_SlNo == this.employeeId
                    })
                    this.selectedEmployee = data[0]
                })

            },
            getBranches() {
                axios.get('/get_branches').then(res => {
                    this.branches = res.data;
                });
            },
            getCustomers() {
                axios.post('/get_customers', {
                    customerType: this.sales.salesType
                }).then(res => {
                    this.customers = res.data;
                    this.customers.unshift({
                        Customer_SlNo: 'C01',
                        Customer_Code: '',
                        Customer_Name: '',
                        display_name: 'General Customer',
                        Customer_Mobile: '',
                        Customer_Address: '',
                        Customer_Type: 'G'
                    })
                })
            },
            getProducts() {
                axios.post('/get_products', {
                    isService: this.sales.isService
                }).then(res => {
                    this.products = res.data;
                })
            },
            async GetIMEIList() {
                await axios.get('/GetIMEIList').then(res => {
                    this.IMEIStore = res.data;
                })
            },
            serialShowModal() {
                this.serialModalStatus = true;
            },
            serialHideModal() {
                this.serialModalStatus = false;

                this.selectedProduct.quantity = this.imei_cart.length
                this.selectedProduct.total = (this.selectedProduct.quantity * this.selectedProduct.Product_Purchase_Rate).toFixed(2)
                this.productTotal()
                this.calculateTotal();
            },
            async imei_add_action() {
                if (this.selectedProduct.Product_SlNo == '') {
                    alert("Please select a product");
                    return false;
                } else {

                    if (this.get_imei_number.trim() == '') {
                        alert("IMEI Number is Required.");
                        return false;
                    }

                    var lines = this.get_imei_number.split(/\n/);
                    var output = [];
                    for (var i = 0; i < lines.length; i++) {
                        if (/\S/.test(lines[i])) {
                            output.push($.trim(lines[i]));
                        }
                    }

                    for (let index = 0; index < output.length; index++) {

                        let imeiObj = this.IMEIStore.find(obj => obj.ps_imei_number == output[index]);


                        let cartInd = this.imei_cart.findIndex(p => p.imeiNumber == output[index].trim());
                        if (cartInd > -1) {
                            alert('IMEI Number already exists in IMEI List');
                            return false;
                        } else {

                            if (!imeiObj) {
                                alert(output[index] + ' not valid IMEI Number')
                            } else {

                                let imei_cart_obj = {
                                    ps_id: imeiObj.ps_id,
                                    imeiNumber: imeiObj.ps_imei_number,
                                    Product_SlNo: imeiObj.Product_SlNo,
                                    Product_Name: imeiObj.Product_Name,
                                }
                                this.imei_cart.unshift(imei_cart_obj);
                            }
                        }
                    }

                    this.selectedProduct.quantity = output.length;
                    this.selectedProduct.total = (this.selectedProduct.quantity * this.selectedProduct.Product_SellingPrice).toFixed(2)
                    this.get_imei_number = '';
                }
            },
            async remove_imei_item(imeiNumber) {
                var newImeiCart = this.imei_cart.filter((el) => {
                    return el.imeiNumber != imeiNumber;
                });
                this.imei_cart = newImeiCart;
            },
            calCulateCart() {
                this.productTotal()
                // 	alert()

                // if (this.selectedIEMI.Product_SlNo <= 0) {
                //     alert("please select a product");
                //     return false;
                // }
                // var numVal1 = this.selectedIEMI.Product_SellingPrice*1;
                //          var numVal2 = this.selectedIEMI.SaleDetails_Discount/ 100;
                //          var totalValue = numVal1- (numVal1 * numVal2)
                // this.selectedIEMI.total  = totalValue.toFixed(2);
                //           console.log(this.selectedIEMI.total)
            },

            async productTotal() {
                if (event.target.id == 'p_d_percent') {
                    this.selectedProduct.discount = ((parseFloat(this.selectedProduct.Product_SellingPrice) * parseFloat(this.p_discount_percent)) / 100).toFixed(2)
                } else {
                    this.p_discount_percent = ((parseFloat(this.selectedProduct.discount) * 100) / parseFloat(this.selectedProduct.Product_SellingPrice)).toFixed(2)
                }

                this.selectedProduct.total = ((parseFloat(this.selectedProduct.Product_SellingPrice) - parseFloat(this.selectedProduct.discount)) * parseFloat(this.selectedProduct.quantity)).toFixed(2)
            },
            onSalesTypeChange() {
                this.selectedCustomer = {
                    Customer_SlNo: '',
                    Customer_Code: '',
                    Customer_Name: '',
                    display_name: 'Select Customer',
                    Customer_Mobile: '',
                    Customer_Address: '',
                    Customer_Type: ''
                }
                this.getCustomers();

                this.clearProduct();
                this.getProducts();
                this.GetIMEIList();
                this.cart = [];

            },
            customerOnChange() {
                if (this.selectedCustomer.Customer_SlNo == '') {
                    return;
                }
                if (event.type == 'readystatechange') {
                    return;
                }

                if (this.sales.salesId != 0 && this.oldCustomerId != parseInt(this.selectedCustomer
                        .Customer_SlNo)) {
                    let changeConfirm = confirm(
                        'Changing customer will set previous due to current due amount. Do you really want to change customer?'
                    );
                    if (changeConfirm == false) {
                        return;
                    }
                } else if (this.sales.salesId != 0 && this.oldCustomerId == parseInt(this.selectedCustomer
                        .Customer_SlNo)) {
                    this.sales.previousDue = this.oldPreviousDue;
                    return;
                }
                axios.post('/get_customer_due', {
                    customerId: this.selectedCustomer.Customer_SlNo
                }).then(res => {
                    if (res.data.length > 0) {
                        this.sales.previousDue = res.data[0].dueAmount;
                    } else {
                        this.sales.previousDue = 0;
                    }
                })

                this.calculateTotal()
            },
            async productOnChange() {
                if (this.selectedProduct.Product_SlNo == '') {
                    return
                }

                if ((this.selectedProduct.Product_SlNo != '' || this.selectedProduct.Product_SlNo != 0) && this
                    .sales.isService == 'false') {
                    this.productStock = await axios.post('/get_product_stock', {
                        productId: this.selectedProduct.Product_SlNo
                    }).then(res => {
                        return res.data;
                    })
                    this.productStockText = this.productStock > 0 ? "Available Stock" : "Stock Unavailable";

                }
                this.$refs.quantity.focus();
                this.p_discount_percent = 0;
                this.imei_cart = [];
            },
            toggleProductPurchaseRate() {
                //this.productPurchaseRate = this.productPurchaseRate == '' ? this.selectedProduct.Product_Purchase_Rate : '';
                this.$refs.productPurchaseRate.type = this.$refs.productPurchaseRate.type == 'text' ? 'password' :
                    'text';
            },
            addToCart() {

                if (this.selectedProduct.Product_SlNo == '') {
                    alert('Select a product');
                    return;
                }

                if (this.selectedProduct.Product_SellingPrice == '' || this.selectedProduct.Product_SellingPrice == 0) {
                    alert('Enter sales rate');
                    return;
                }

                if (this.selectedProduct.quantity == 0 || this.selectedProduct.quantity == '') {
                    alert('Enter quantity');
                    return;
                }

                if (parseFloat(this.selectedProduct.quantity) > parseFloat(this.productStock) && this.sales.isService == 'false') {
                    alert('Stock unavailable');
                    return;
                }

                let product = {
                    productId: this.selectedProduct.Product_SlNo,
                    productCode: this.selectedProduct.Product_Code,
                    UnitName: this.selectedProduct.Unit_Name,
                    name: this.selectedProduct.Product_Name,
                    salesRate: this.selectedProduct.Product_SellingPrice,
                    vat: this.selectedProduct.vat,
                    quantity: this.selectedProduct.quantity,
                    total: this.selectedProduct.total,
                    purchaseRate: this.selectedProduct.Product_Purchase_Rate,
                    SaleDetails_Discount: this.selectedProduct.discount,
                    SerialStore: this.selectedProduct.Is_Serial == 'true' ? this.imei_cart : [],
                }

                let getCurrentInd = this.cart.findIndex((item) => {
                    return (item.productId == this.selectedProduct.Product_SlNo);
                });

                if (getCurrentInd > -1) {
                    alert("The Product already added in the cart!");
                    return;
                }

                this.cart.push(product);
                // document.querySelector('#product input[role="combobox"]').focus();

                this.clearProduct();
                this.calculateTotal();
                this.imei_cart = [];
            },
            removeFromCart(ind, imei) {
                if (this.sales.salesId != 0) {
                    axios.post('/remove_cart_imei', imei)
                        .then((res) => {
                            if (res.data == 'deleted') {
                                this.cart.splice(ind, 1);
                                this.calculateTotal();
                            }
                        })
                } else {
                    this.cart.splice(ind, 1);
                    this.calculateTotal();
                }
            },
            clearProduct() {
                this.selectedProduct = {
                    Product_SlNo: '',
                    display_text: 'Select Product',
                    Product_Name: '',
                    Unit_Name: '',
                    quantity: 0,
                    Product_Purchase_Rate: '',
                    Product_SellingPrice: 0.00,
                    vat: 0.00,
                    total: 0.00
                }
                this.productStock = '';
                this.productStockText = '';
                this.p_discount_percent = 0;
            },
            calculateTotal() {
                this.sales.subTotal = this.cart.reduce((prev, curr) => {
                    return prev + parseFloat(curr.total)
                }, 0).toFixed(2);
                this.sales.vat = ((parseFloat(this.sales.subTotal) * parseFloat(this.vatPercent)) / 100).toFixed(2);
                if (event.target.id == 'discountPercent') {
                    this.sales.discount = ((parseFloat(this.sales.subTotal) * parseFloat(this.discountPercent)) /
                        100).toFixed(2);
                } else {
                    this.discountPercent = (parseFloat(this.sales.discount) / parseFloat(this.sales.subTotal) * 100)
                        .toFixed(2);
                }
                this.sales.total = ((parseFloat(this.sales.subTotal) + parseFloat(this.sales.vat) + parseFloat(this
                    .sales.transportCost)) - parseFloat(this.sales.discount)).toFixed(2);
                if (this.selectedCustomer.Customer_Type == 'G') {
                    this.sales.paid = (parseFloat(this.sales.cashPaid) + parseFloat(this.sales.bankPaid)).toFixed(2)
                    this.sales.due = (parseFloat(this.sales.total) - parseFloat(this.sales.paid)).toFixed(2);
                } else {
                    this.sales.paid = (parseFloat(this.sales.cashPaid) + parseFloat(this.sales.bankPaid)).toFixed(2)
                    this.sales.due = (parseFloat(this.sales.total) - parseFloat(this.sales.paid)).toFixed(2);
                }
            },
            // onChangeIMEI() {
            //     if (this.selectedIEMI.Product_SlNo == '') {
            //         return;
            //     }

            //     this.selectedProduct.quantity = 1;
            //     this.selectedIEMI.SaleDetails_Discount = this.selectedIEMI.discount;
            //     // this.selectedIEMI.total = (parseFloat(this.selectedIEMI.quantity.quantity) * parseFloat(this.selectedIEMI.Product_SellingPrice)).toFixed(2);

            //     this.productTotal()
            //     this.$refs.p_d_percent.focus();
            //     // 	this.selectedProduct.total = (parseFloat(this.selectedProduct.quantity) * parseFloat(this.selectedProduct.Product_SellingPrice)).toFixed(2);

            // },
            saveSales() {

                if (this.selectedCustomer.Customer_SlNo == '') {
                    alert('Select Customer');
                    return;
                }
                if (this.selectedCustomer.Customer_Type == 'G' && parseFloat(this.sales.total) != parseFloat(this.sales.paid) ) {
                    alert('Paid Amount not equal');
                    return;
                }
                if (parseFloat(this.sales.total) < parseFloat(this.sales.paid)) {
                    alert('Paid Amount does not greater than Total amount');
                    return;
                }
                if (this.cart.length == 0) {
                    alert('Cart is empty');
                    return;
                }
                if (parseFloat(this.sales.bankPaid) > 0 && this.selectedAccount == null) {
                    alert("Select Bank Account")
                    return;
                }

                if (parseFloat(this.selectedCustomer.Customer_Credit_Limit) < (parseFloat(this.sales.due) +
                        parseFloat(this.sales.previousDue))) {
                    alert(`Customer credit limit (${this.selectedCustomer.Customer_Credit_Limit}) exceeded`);
                    return;
                }

                if (this.selectedEmployee != null && this.selectedEmployee.Employee_SlNo != null) {
                    this.sales.employeeId = this.selectedEmployee.Employee_SlNo;
                } else {
                    this.sales.employeeId = null;
                }

                let url = "/add_sales";
                if (this.sales.salesId != 0) {
                    url = "/update_sales";
                }

                this.sales.customerId = this.selectedCustomer.Customer_SlNo;
                this.sales.salesFrom = this.selectedBranch.brunch_id;
                // this.sales.commission = (parseFloat(this.sales.total) * parseFloat(this.selectedEmployee.Commission)) / 100;

                this.saleOnProgress = true;

                let data = {
                    sales: this.sales,
                    cart: this.cart
                }

                if (parseFloat(this.sales.bankPaid) > 0) {
                    this.sales.account_id = this.selectedAccount.account_id
                }

                if (this.selectedCustomer.Customer_Type == 'G') {
                    data.customer = this.selectedCustomer;
                }

                axios.post(url, data).then(async res => {
                    let r = res.data;
                    if (r.success) {
                        let conf = confirm('Sale success, Do you want to view invoice?');
                        if (conf) {
                            window.open('/sale_invoice_print/' + r.salesId, '_blank');
                            await new Promise(r => setTimeout(r, 1000));
                            window.location = this.sales.isService == 'false' ? '/sales/product' :
                                '/sales/service';
                        } else {
                            window.location = this.sales.isService == 'false' ? '/sales/product' :
                                '/sales/service';
                        }
                    } else {
                        alert(r.message);
                        this.saleOnProgress = false;
                    }
                })
            },
            async getSales() {
                await axios.post('/get_sales', {
                    salesId: this.sales.salesId
                }).then(res => {
                    let r                        = res.data;
                    let sales                    = r.sales[0];
                        this.sales.salesBy       = sales.AddBy;
                        this.sales.salesFrom     = sales.SaleMaster_branchid;
                        this.sales.salesDate     = sales.SaleMaster_SaleDate;
                        this.sales.salesType     = sales.SaleMaster_SaleType;
                        this.sales.customerId    = sales.SalseCustomer_IDNo;
                        this.sales.employeeId    = sales.Employee_SlNo;
                        this.sales.subTotal      = sales.SaleMaster_SubTotalAmount;
                        this.sales.discount      = sales.SaleMaster_TotalDiscountAmount;
                        this.sales.vat           = sales.SaleMaster_TaxAmount;
                        this.sales.transportCost = sales.SaleMaster_Freight;
                        this.sales.total         = sales.SaleMaster_TotalSaleAmount;
                        this.sales.paid          = sales.SaleMaster_PaidAmount;
                        this.sales.cashPaid      = sales.cashPaid;
                        this.sales.bankPaid      = sales.bankPaid;
                        this.sales.previousDue   = sales.SaleMaster_Previous_Due;
                        this.sales.due           = sales.SaleMaster_DueAmount;
                        this.sales.payment_type  = sales.payment_type;
                        this.sales.account_id    = sales.account_id;
                        this.sales.note          = sales.SaleMaster_Description;
                    // this.sales.commission = sales.commission;

                    this.oldCustomerId = sales.SalseCustomer_IDNo;
                    this.oldPreviousDue = sales.SaleMaster_Previous_Due;

                    this.vatPercent = parseFloat(this.sales.vat) * 100 / parseFloat(this.sales.subTotal);
                    this.discountPercent = parseFloat(this.sales.discount) * 100 / parseFloat(this.sales
                        .subTotal);

                    this.selectedEmployee = {
                        Employee_SlNo: sales.employee_id,
                        Employee_Name: sales.Employee_Name,
                        // Commission: sales.Commission
                    }
                    this.selectedAccount = {
                        account_id: sales.account_id,
                        display_text: sales.display_text,
                    }

                    this.selectedCustomer = {
                        Customer_SlNo: sales.SalseCustomer_IDNo,
                        Customer_Code: sales.Customer_Code,
                        Customer_Name: sales.Customer_Name,
                        display_name: sales.Customer_Type == 'G' ? 'General Customer' : `${sales.Customer_Code} - ${sales.Customer_Name}`,
                        Customer_Mobile: sales.Customer_Mobile,
                        Customer_Address: sales.Customer_Address,
                        Customer_Type: sales.Customer_Type
                    }

                    r.saleDetails.forEach(product => {

                        product.imei.map(async p => {
                            return p.imeiNumber = p.ps_imei_number;
                        })

                        let cartProduct = {
                            productId: product.Product_IDNo,
                            productCode: product.Product_Code,
                            UnitName: product.Unit_Name,
                            categoryName: product.ProductCategory_Name,
                            name: product.Product_Name,
                            salesRate: product.SaleDetails_Rate,
                            vat: product.SaleDetails_Tax,
                            quantity: product.SaleDetails_TotalQuantity,
                            total: product.SaleDetails_TotalAmount,
                            purchaseRate: product.Purchase_Rate,
                            purchase_discount: product.Discount_amount,
                            SaleDetails_Discount: product.SaleDetails_Discount,
                            SerialStore: product.imei

                        }

                        this.cart.push(cartProduct);
                        // console.log(this.cart)

                    })

                    this.getCustomers();
                    this.getProducts();
                })


            }
        }
    })
</script>