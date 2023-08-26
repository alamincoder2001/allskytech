<?php 
 if (!defined('BASEPATH')) exit('No direct script access allowed');

class InvoiceController extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Billing_model');
        $this->load->library('cart');
        $this->load->model('Model_table', "mt", TRUE);
        $this->load->helper('form');
    }


    public function indexInvoice($id)
    {
        $data['title'] = "Sales Invoice";
        $data['saleId'] = $id;
        $data['content'] = $this->load->view('Administrator/sales/salesClientInvoice', $data, true);
        $this->load->view('Administrator/indexInvoice', $data);
    }


    public function getSalesInvoice()
    {
        $res = new stdClass;
        $data = json_decode($this->input->raw_input_stream);
        $branchId = $this->session->userdata("BRANCHid");

        $clauses = "";


        if (isset($data->salesId) && $data->salesId != 0 && $data->salesId != '') {
            $clauses .= " and sm.SaleMaster_SlNo = '$data->salesId'";
            $saleDetails = $this->db->query("
                select 
                    sd.*,
                    p.Product_Name,
                    p.Product_Code,
                    p.Guarantee,
                    p.Warranty,
                    pc.ProductCategory_Name,
                    u.Unit_Name,
                    v.varient_name
                from tbl_saledetails sd
                join tbl_product p on p.Product_SlNo = sd.Product_IDNo
                join tbl_productcategory pc on pc.ProductCategory_SlNo = p.ProductCategory_ID
                join tbl_unit u on u.Unit_SlNo = p.Unit_ID
                join tbl_varients v on v.id = p.varient
                where sd.SaleMaster_IDNo = ?
            ", $data->salesId)->result();

            $saleDetails = array_map(function ($saleDetail) {
                $saleDetail->imei = $this->db->query("SELECT * FROM tbl_product_serial_numbers WHERE sales_details_id=?", $saleDetail->SaleDetails_SlNo)->result();
                return $saleDetail;
            }, $saleDetails);

            $res->saleDetails = $saleDetails;
        }
        $sales = $this->db->query("
            select 
            sm.*,
            c.Customer_Name,
            c.Customer_Mobile,
            c.Customer_Address,
            c.Customer_Type,
            e.Employee_Name,
            br.Brunch_name,
            concat(ba.account_name, '-', ba.account_number, '(', ba.bank_name, ')') as display_text
            from tbl_salesmaster sm
            left join tbl_customer c on c.Customer_SlNo = sm.SalseCustomer_IDNo
            left join tbl_employee e on e.Employee_SlNo = sm.employee_id
            left join tbl_brunch br on br.brunch_id = sm.SaleMaster_branchid
            left join tbl_bank_accounts ba on ba.account_id = sm.account_id
            where sm.Status = 'a'
            $clauses
            order by sm.SaleMaster_SlNo desc
        ")->result();
        // foreach ($sales as $key => $value) {
        //     $value->commission = $this->db->query("SELECT * FROM `tbl_employee_commission` WHERE `Employee_Sl` = ?", $value->employee_id)->result();
        // }

        $res->sales = $sales;

        echo json_encode($res);
    }


}