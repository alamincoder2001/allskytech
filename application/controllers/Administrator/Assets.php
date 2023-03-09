<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Assets extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->brunch = $this->session->userdata('BRANCHid');
        $access = $this->session->userdata('userId');
        $this->accountType = $this->session->userdata('accountType');
        if ($access == '') {
            redirect("Login");
        }
        $this->load->model("Model_myclass", "mmc", TRUE);
        $this->load->model('Model_table', "mt", TRUE);
    }


    public function index()
    {
        $access = $this->mt->userAccess();
        if (!$access) {
            redirect(base_url());
        }

        $query = $this->db->query("SELECT asset_id FROM tbl_assets ORDER by as_id DESC LIMIT 1")->row();
        $data['assetserial'] = "A0001";
        // $serial = "A0001";
        if ($query) {
            $serial =  substr($query->asset_id, -4) + 1;
            if (strlen($serial) == 1) {
                $data['assetserial'] = "A000" . $serial;
            } else if (strlen($serial) == 2) {
                $data['assetserial'] = "A00" . $serial;
            } else if (strlen($serial) == 3) {
                $data['assetserial'] = "A0" . $serial;
            } else {
                $data['assetserial'] = "A" . $serial;
            }
        }
        $data['title'] = "Assets Entry";
        $data['assets'] = $this->Other_model->get_all_asset_info();
        $data['content'] = $this->load->view('Administrator/assets/assets_entry', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }


    public function insert_Assets()
    {
        $data = array(
            "asset_id"  => $this->input->post('asset_id'),
            "as_date"   => date('Y-m-d'),
            "as_name"   => $this->input->post('assetsname'),
            "serial"    => $this->input->post('serial'),
            "as_qty"    => $this->input->post('qty'),
            "as_rate"   => $this->input->post('rate'),
            "as_amount" => $this->input->post('amount'),
            "status"    => 'a',
            "AddBy"     => $this->session->userdata("FullName"),
            "AddTime"   => date("Y-m-d H:i:s"),
            "branchid"  => $this->session->userdata('BRANCHid'),
        );
        $this->mt->save_data('tbl_assets', $data);
        echo json_encode(TRUE);
    }


    public function Assets_edit($id = null)
    {
        $data['edit'] = $this->db->where('as_id', $id)->get('tbl_assets')->row();
        $this->load->view('Administrator/assets/edit_assets', $data);
    }


    public function Update_Assets($id = null)
    {
        $data = array(
            "as_name"   => $this->input->post('assetsname'),
            "serial"    => $this->input->post('serial'),
            "as_qty"    => $this->input->post('qty'),
            "as_rate"   => $this->input->post('rate'),
            "as_amount" => $this->input->post('amount')
        );
        $up = $this->db->where('as_id', $id)->update('tbl_assets', $data);
        if ($up) :
            echo json_encode(TRUE);
        else : return false;
        endif;
    }


    public function Assets_delete($id = null)
    {
        $data = array('status' => 'd');
        $up = $this->db->where('as_id', $id)->update('tbl_assets', $data);
        if ($up) :
            echo json_encode(TRUE);
        else : return false;
        endif;
    }
}
