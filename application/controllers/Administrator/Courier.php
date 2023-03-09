<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Courier extends CI_Controller {
    /**
        * Courier controller
        * Add Courier
    */

    public function __construct()
    {
        parent::__construct();
        $access = $this->session->userdata('userId');
         if($access == '' ){
            redirect("Login");
        }

        $this->load->model('Model_table', "mt", TRUE);

    }

    public function index() {
        $data['title'] = 'Courier Entry';
        $data['id'] = 0;
        $data['invoice'] = $this->mt->generateCourierInvoice();
        $data['content'] = $this->load->view('Administrator/courier/courier', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }

    public function courierEdit($id) {
        $data['title'] = 'Courier Edit';
        $courier = $this->db->query("select invoice from couriers where id = ?", $id)->row();
        $data['id'] = $id;
        $data['invoice'] = $courier->invoice;
        $data['content'] = $this->load->view('Administrator/courier/courier', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }

    public function addCourier() {
        $res = new stdClass;
        try {
            $this->db->trans_begin();
            $data = json_decode($this->input->raw_input_stream);
       
            // insert courier data
            $courier = array(
                'invoice' => $data->courier->invoice,
                'date' => $data->courier->date,
                'customer_id' => $data->courier->customer_id,
                'mobile' => $data->courier->mobile,
                'address' => $data->courier->address,
                'note' => $data->courier->note,
                'status' => 'a', 
                'added_by' => $this->session->userdata('FullName'), 
                'add_time' => date("Y-m-d H:i:s"), 
                'branch_id' => $this->session->userdata('BRANCHid') 
            );

            $this->db->insert('couriers', $courier);
            $courierId = $this->db->insert_id();

            // courier detail data
            $newArr = [];
            foreach($data->cart as $product) {
                $detail = array(
                    'courier_id' => $courierId,
                    'name' => $product->name,
                    'quantity' => $product->quantity,
                    'status' => 'a',
                    'branch_id' => $this->session->userdata('BRANCHid')
                );

                array_push($newArr, $detail);
            }

            // insert detail data
            $this->db->insert_batch('courier_details', $newArr);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
                $res->success = true;
                $res->courierId = $courierId;
                $res->message = 'Courier added successfully';
            }
        } catch (\Exception $e) {
            $this->db->trans_rollback();
            $res->success = false;
            $res->message = 'failed '. $e->getMessage();
        }

        echo json_encode($res);
    }

    public function updateCourier() {
        $res = new stdClass;
        try {
            $this->db->trans_begin();
            $data = json_decode($this->input->raw_input_stream);
            $courierId = $data->courier->id;
       
            // update courier data
            $courier = array(
                'date' => $data->courier->date,
                'customer_id' => $data->courier->customer_id,
                'mobile' => $data->courier->mobile,
                'address' => $data->courier->address,
                'note' => $data->courier->note,
                'update_by' => $this->session->userdata('FullName'), 
                'update_time' => date("Y-m-d H:i:s"), 
                'branch_id' => $this->session->userdata('BRANCHid') 
            );

            $this->db->where('id', $courierId)->update('couriers', $courier);

            // old detail data deleted
            $oldDetails = $this->db->query("
                delete from courier_details 
                where courier_id = ? 
                and branch_id = ?
                ", [$courierId, $this->session->userdata('BRANCHid')]);

            // new courier detail data insert
            $newArr = [];
            foreach($data->cart as $product) {
                $detail = array(
                    'courier_id' => $courierId,
                    'name' => $product->name,
                    'quantity' => $product->quantity,
                    'status' => 'a',
                    'branch_id' => $this->session->userdata('BRANCHid')
                );

                array_push($newArr, $detail);
            }

            // new insert detail data
            $this->db->insert_batch('courier_details', $newArr);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
                $res->success = true;
                $res->courierId = $courierId;
                $res->message = 'Courier update successfully';
            }
        } catch (\Exception $e) {
            $this->db->trans_rollback();
            $res->success = false;
            $res->message = 'failed '. $e->getMessage();
        }

        echo json_encode($res);
    }

    public function getCouriers() {
        $res = new stdClass;
        $data = json_decode($this->input->raw_input_stream);
        $clauses = "";

        if(isset($data->courierId) && $data->courierId != '') {
            $clauses .= " and cr.id = $data->courierId";

            $res->details = $this->db->query("select * from courier_details where courier_id = ? and status = 'a'", $data->courierId)->result();
        }

        if(isset($data->dateFrom) && $data->dateFrom != '' && isset($data->dateTo) && $data->dateTo != '') {
            $clauses .= " and cr.date between '$data->dateFrom' and '$data->dateTo'";
        }

        $res->couriers = $this->db->query("
            select 
                cr.*,
                c.Customer_Name
            from couriers cr
            join tbl_customer c on c.Customer_SlNo = cr.customer_id
            where cr.status = 'a'
            and cr.branch_id = ?
            $clauses
        ", $this->session->userdata('BRANCHid'))->result();
        
        echo json_encode($res);
    }
    public function getCourierProducts() {
        $res = new stdClass;
        $res->couriers = $this->db->query("
            select id,name,branch_id
            from courier_details
            where status = 'a'
            and branch_id = ?", $this->session->userdata('BRANCHid'))->result();
        
        echo json_encode($res);
    }
    public function courierRecord() {
        $data['title'] = 'Courier Record';
        $data['content'] = $this->load->view('Administrator/courier/courier_record', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }

    public function invoice($id) {
        $data['title'] = 'Courier Invoice';
        $data['courierId'] = $id;
        $data['content'] = $this->load->view('Administrator/courier/courier_invoice', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }

    public function deleteCourier() {
        $res = new stdClass;
        try {
            $this->db->trans_begin();
            $data = json_decode($this->input->raw_input_stream);

            $findRecord = $this->db->query("select * from couriers where id = ? and status = 'a' and branch_id = ?", [$data->courierId, $this->session->userdata('BRANCHid')]);
            if($findRecord->num_rows() == 0) {
                $res->success = false;
                $res->message = 'Courier not found';
                echo json_encode($res);
                exit;
            }

            // deleted courier data
            $this->db->set('status', 'd')->where('id', $data->courierId)->update('couriers');

            // deleted courier details data
            $this->db->set('status', 'd')->where('courier_id', $data->courierId)->update('courier_details');

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
                $res->success = true;
                $res->message = 'Courier deleted successfully';
            }
        } catch (\Exception $e) {
            $this->db->trans_rollback();
            $res->success = false;
            $res->message = 'failed '. $e->getMessage();
        }

        echo json_encode($res);
    }
}