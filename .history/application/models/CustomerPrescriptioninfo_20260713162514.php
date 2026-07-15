<?php
class CustomerPrescriptioninfo extends CI_Model{

    public function Customerprescriptioninsertupdate(){
        $this->db->trans_begin();

        $userID = $_SESSION['userid'];

        $customerid = $this->input->post('customerid');

        $prescriptiondate = $this->input->post('prescriptiondate');

        $distance_r_sph  = $this->input->post('distance_r_sph');
        $distance_r_cyl  = $this->input->post('distance_r_cyl');
        $distance_r_axis = $this->input->post('distance_r_axis');
        $distance_l_sph  = $this->input->post('distance_l_sph');
        $distance_l_cyl  = $this->input->post('distance_l_cyl');
        $distance_l_axis = $this->input->post('distance_l_axis');

        $readadd_r_sph  = $this->input->post('readadd_r_sph');
        $readadd_r_cyl  = $this->input->post('readadd_r_cyl');
        $readadd_r_axis = $this->input->post('readadd_r_axis');
        $readadd_l_sph  = $this->input->post('readadd_l_sph');
        $readadd_l_cyl  = $this->input->post('readadd_l_cyl');
        $readadd_l_axis = $this->input->post('readadd_l_axis');

        $lens_vision   = $this->input->post('lens_vision');
        $lens_material = $this->input->post('lens_material');

        $frame_model     = $this->input->post('frame_model');
        $rim_bridge_size = $this->input->post('rim_bridge_size');
        $colour          = $this->input->post('colour');
        $temple_length   = $this->input->post('temple_length');
        $weight          = $this->input->post('weight');

        $coat_a2      = $this->input->post('coat_a2') ? 1 : 0;
        $coat_sp20    = $this->input->post('coat_sp20') ? 1 : 0;
        $coat_hmc     = $this->input->post('coat_hmc') ? 1 : 0;
        $coat_pgx     = $this->input->post('coat_pgx') ? 1 : 0;
        $coat_pbx     = $this->input->post('coat_pbx') ? 1 : 0;
        $coat_bluecut = $this->input->post('coat_bluecut') ? 1 : 0;

        $remarks = $this->input->post('remarks');

        $recordOption = $this->input->post('recordOption');
        if(!empty($this->input->post('recordID'))){$recordID = $this->input->post('recordID');}

        $updatedatetime = date('Y-m-d H:i:s');

        header('Content-Type: application/json');

        if($recordOption==1){
            $data = array(
                'prescriptiondate' => $prescriptiondate,

                'distance_r_sph'  => $distance_r_sph,
                'distance_r_cyl'  => $distance_r_cyl,
                'distance_r_axis' => $distance_r_axis,
                'distance_l_sph'  => $distance_l_sph,
                'distance_l_cyl'  => $distance_l_cyl,
                'distance_l_axis' => $distance_l_axis,

                'readadd_r_sph'  => $readadd_r_sph,
                'readadd_r_cyl'  => $readadd_r_cyl,
                'readadd_r_axis' => $readadd_r_axis,
                'readadd_l_sph'  => $readadd_l_sph,
                'readadd_l_cyl'  => $readadd_l_cyl,
                'readadd_l_axis' => $readadd_l_axis,

                'lens_vision'   => $lens_vision,
                'lens_material' => $lens_material,

                'frame_model'     => $frame_model,
                'rim_bridge_size' => $rim_bridge_size,
                'colour'          => $colour,
                'temple_length'   => $temple_length,
                'weight'          => $weight,

                'coat_a2'      => $coat_a2,
                'coat_sp20'    => $coat_sp20,
                'coat_hmc'     => $coat_hmc,
                'coat_pgx'     => $coat_pgx,
                'coat_pbx'     => $coat_pbx,
                'coat_bluecut' => $coat_bluecut,

                'remarks' => $remarks,

                'status'=> '1',
                'insertdatetime'=> $updatedatetime,
                'tbl_user_idtbl_user'=> $userID,
                'tbl_customer_idtbl_customer'=> $customerid,
            );

            $this->db->insert('tbl_customer_prescription', $data);

            $this->db->trans_complete();

            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();

                $actionObj = new stdClass();
                $actionObj->icon = 'fas fa-save';
                $actionObj->title = '';
                $actionObj->message = 'Record Added Successfully';
                $actionObj->url = '';
                $actionObj->target = '_blank';
                $actionObj->type = 'success';

                echo json_encode($actionObj);
            } else {
                $this->db->trans_rollback();

                $actionObj = new stdClass();
                $actionObj->icon = 'fas fa-warning';
                $actionObj->title = '';
                $actionObj->message = 'Record Error';
                $actionObj->url = '';
                $actionObj->target = '_blank';
                $actionObj->type = 'danger';

                echo json_encode($actionObj);
            }
        }
        else{
            $data = array(
                'prescriptiondate' => $prescriptiondate,

                'distance_r_sph'  => $distance_r_sph,
                'distance_r_cyl'  => $distance_r_cyl,
                'distance_r_axis' => $distance_r_axis,
                'distance_l_sph'  => $distance_l_sph,
                'distance_l_cyl'  => $distance_l_cyl,
                'distance_l_axis' => $distance_l_axis,

                'readadd_r_sph'  => $readadd_r_sph,
                'readadd_r_cyl'  => $readadd_r_cyl,
                'readadd_r_axis' => $readadd_r_axis,
                'readadd_l_sph'  => $readadd_l_sph,
                'readadd_l_cyl'  => $readadd_l_cyl,
                'readadd_l_axis' => $readadd_l_axis,

                'lens_vision'   => $lens_vision,
                'lens_material' => $lens_material,

                'frame_model'     => $frame_model,
                'rim_bridge_size' => $rim_bridge_size,
                'colour'          => $colour,
                'temple_length'   => $temple_length,
                'weight'          => $weight,

                'coat_a2'      => $coat_a2,
                'coat_sp20'    => $coat_sp20,
                'coat_hmc'     => $coat_hmc,
                'coat_pgx'     => $coat_pgx,
                'coat_pbx'     => $coat_pbx,
                'coat_bluecut' => $coat_bluecut,

                'remarks' => $remarks,

                'updateuser'=> $userID,
                'updatedatetime' => $updatedatetime,
            );

            $this->db->where('idtbl_customer_prescription', $recordID);
            $this->db->update('tbl_customer_prescription', $data);

            $this->db->trans_complete();

            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();

                echo json_encode(array(
                    'status'     => 'success',
                    'message'    => 'Record Update Successfully',
                    'customerid' => $customerid
                ));
            } else {
                $this->db->trans_rollback();

                echo json_encode(array(
                    'status'  => 'error',
                    'message' => 'Record Error'
                ));
            }
        }
    }

    public function Customerprescriptionstatus($x, $y){
        $this->db->trans_begin();

        $userID = $_SESSION['userid'];
        $recordID = $x;
        $type = $y;
        $updatedatetime = date('Y-m-d H:i:s');

        // Fetch the customer id first so we can redirect back to the same customer's screen
        $existing = $this->db->select('tbl_customer_idtbl_customer')
                              ->from('tbl_customer_prescription')
                              ->where('idtbl_customer_prescription', $recordID)
                              ->get()->row();
        $customerid = $existing ? $existing->tbl_customer_idtbl_customer : null;

        if($type==1){
            $data = array(
                'status' => '1',
                'updateuser'=> $userID,
                'updatedatetime'=> $updatedatetime
            );

            $this->db->where('idtbl_customer_prescription', $recordID);
            $this->db->update('tbl_customer_prescription', $data);

            $this->db->trans_complete();

            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();

                $actionObj=new stdClass();
                $actionObj->icon='fas fa-check';
                $actionObj->title='';
                $actionObj->message='Record Activate Successfully';
                $actionObj->url='';
                $actionObj->target='_blank';
                $actionObj->type='success';

                $actionJSON=json_encode($actionObj);

                $this->session->set_flashdata('msg', $actionJSON);
                redirect('CustomerPrescription/index/'.$customerid);
            } else {
                $this->db->trans_rollback();

                $actionObj=new stdClass();
                $actionObj->icon='fas fa-warning';
                $actionObj->title='';
                $actionObj->message='Record Error';
                $actionObj->url='';
                $actionObj->target='_blank';
                $actionObj->type='danger';

                $actionJSON=json_encode($actionObj);

                $this->session->set_flashdata('msg', $actionJSON);
                redirect('CustomerPrescription/index/'.$customerid);
            }
        }
        else if($type==2){
            $data = array(
                'status' => '2',
                'updateuser'=> $userID,
                'updatedatetime'=> $updatedatetime
            );

            $this->db->where('idtbl_customer_prescription', $recordID);
            $this->db->update('tbl_customer_prescription', $data);

            $this->db->trans_complete();

            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();

                $actionObj=new stdClass();
                $actionObj->icon='fas fa-times';
                $actionObj->title='';
                $actionObj->message='Record Deactivate Successfully';
                $actionObj->url='';
                $actionObj->target='_blank';
                $actionObj->type='warning';

                $actionJSON=json_encode($actionObj);

                $this->session->set_flashdata('msg', $actionJSON);
                redirect('CustomerPrescription/index/'.$customerid);
            } else {
                $this->db->trans_rollback();

                $actionObj=new stdClass();
                $actionObj->icon='fas fa-warning';
                $actionObj->title='';
                $actionObj->message='Record Error';
                $actionObj->url='';
                $actionObj->target='_blank';
                $actionObj->type='danger';

                $actionJSON=json_encode($actionObj);

                $this->session->set_flashdata('msg', $actionJSON);
                redirect('CustomerPrescription/index/'.$customerid);
            }
        }
        else if($type==3){
            $data = array(
                'status' => '3',
                'updateuser'=> $userID,
                'updatedatetime'=> $updatedatetime
            );

            $this->db->where('idtbl_customer_prescription', $recordID);
            $this->db->update('tbl_customer_prescription', $data);

            $this->db->trans_complete();

            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();

                $actionObj=new stdClass();
                $actionObj->icon='fas fa-trash-alt';
                $actionObj->title='';
                $actionObj->message='Record Remove Successfully';
                $actionObj->url='';
                $actionObj->target='_blank';
                $actionObj->type='danger';

                $actionJSON=json_encode($actionObj);

                $this->session->set_flashdata('msg', $actionJSON);
                redirect('CustomerPrescription/index/'.$customerid);
            } else {
                $this->db->trans_rollback();

                $actionObj=new stdClass();
                $actionObj->icon='fas fa-warning';
                $actionObj->title='';
                $actionObj->message='Record Error';
                $actionObj->url='';
                $actionObj->target='_blank';
                $actionObj->type='danger';

                $actionJSON=json_encode($actionObj);

                $this->session->set_flashdata('msg', $actionJSON);
                redirect('CustomerPrescription/index/'.$customerid);
            }
        }
    }

    public function Customerprescriptionedit(){
        $recordID = $this->input->post('recordID');

        $this->db->select('*');
        $this->db->from('tbl_customer_prescription');
        $this->db->where('idtbl_customer_prescription', $recordID);
        $this->db->where('status', 1);

        $respond = $this->db->get();
        $row = $respond->row(0);

        $obj = new stdClass();
        $obj->id = $row->idtbl_customer_prescription;
        $obj->customerid = $row->tbl_customer_idtbl_customer;
        $obj->prescriptiondate = $row->prescriptiondate;

        $obj->distance_r_sph = $row->distance_r_sph;
        $obj->distance_r_cyl = $row->distance_r_cyl;
        $obj->distance_r_axis = $row->distance_r_axis;
        $obj->distance_l_sph = $row->distance_l_sph;
        $obj->distance_l_cyl = $row->distance_l_cyl;
        $obj->distance_l_axis = $row->distance_l_axis;

        $obj->readadd_r_sph = $row->readadd_r_sph;
        $obj->readadd_r_cyl = $row->readadd_r_cyl;
        $obj->readadd_r_axis = $row->readadd_r_axis;
        $obj->readadd_l_sph = $row->readadd_l_sph;
        $obj->readadd_l_cyl = $row->readadd_l_cyl;
        $obj->readadd_l_axis = $row->readadd_l_axis;

        $obj->lens_vision = $row->lens_vision;
        $obj->lens_material = $row->lens_material;

        $obj->frame_model = $row->frame_model;
        $obj->rim_bridge_size = $row->rim_bridge_size;
        $obj->colour = $row->colour;
        $obj->temple_length = $row->temple_length;
        $obj->weight = $row->weight;

        $obj->coat_a2 = $row->coat_a2;
        $obj->coat_sp20 = $row->coat_sp20;
        $obj->coat_hmc = $row->coat_hmc;
        $obj->coat_pgx = $row->coat_pgx;
        $obj->coat_pbx = $row->coat_pbx;
        $obj->coat_bluecut = $row->coat_bluecut;

        $obj->remarks = $row->remarks;

        echo json_encode($obj);
    }

    public function Customerprescriptionview(){
        $recordID = $this->input->post('recordID');

        $this->db->select('p.*, c.name as customername, c.customercode, c.contact, c.address');
        $this->db->from('tbl_customer_prescription p');
        $this->db->join('tbl_customer c', 'c.idtbl_customer = p.tbl_customer_idtbl_customer');
        $this->db->where('p.idtbl_customer_prescription', $recordID);

        $respond = $this->db->get();

        echo json_encode($respond->row(0));
    }

    public function Customerprescriptionlist(){
        $customerid = $this->input->post('customerid');

        $this->db->select('idtbl_customer_prescription, prescriptiondate, frame_model, lens_vision, lens_material, status');
        $this->db->from('tbl_customer_prescription');
        $this->db->where('tbl_customer_idtbl_customer', $customerid);
        $this->db->where('status', 1);
        $this->db->order_by('idtbl_customer_prescription', 'DESC');

        $respond = $this->db->get();

        echo json_encode($respond->result());
    }

    public function Customersearch(){
        $term = $this->input->post('term');

        $this->db->select('idtbl_customer, customercode, name, contact');
        $this->db->from('tbl_customer');
        $this->db->where('status', 1);
        $this->db->group_start();
            $this->db->like('name', $term);
            $this->db->or_like('contact', $term);
            $this->db->or_like('customercode', $term);
        $this->db->group_end();
        $this->db->order_by('name', 'ASC');
        $this->db->limit(20);

        $customers = $this->db->get()->result();

        $results = array();
        foreach ($customers as $c) {
            $results[] = array(
                'id' => $c->idtbl_customer,
                'text' => $c->name.' - '.$c->contact.' ('.$c->customercode.')'
            );
        }

        echo json_encode(array('results' => $results));
    }

    public function Getcustomer($customerid){
        $this->db->select('idtbl_customer, customercode, name, contact');
        $this->db->from('tbl_customer');
        $this->db->where('idtbl_customer', $customerid);
        return $this->db->get()->row();
    }
}